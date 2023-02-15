const db = require('../../../config/database').db;
require('dotenv').config();
const md5 = require('md5');
const common = require('../../../helpers/common');
var randomize = require("randomatic");
const moment = require('moment');
const jwt = require('jsonwebtoken');
//var async = require('asyncawait/async');
//var await = require('asyncawait/await');
//const { queue, times } = require('async');
//var { result } = require('asyncawait/async');


/*===================Check email/mobile/username exist=============================*/

let checkfieldExist = async (post, callback) => {
    try {
        if(post.field_type=='email'){
            //check email exist in user table
            if(post.user_id){
                var is_exist_email = await db.query(`Select user_id from b_user where email='${post['field_value']}' and user_id!='${post['user_id']}'`);
            }else{
                var is_exist_email = await db.where('email', post.field_value).get('b_user');
            }            
            if (is_exist_email.length > 0) {
                callback(null, 'email_already_exist');
                return;
            }else{
                callback(null, 'email_success');
                return;
            }
        }
        if(post.field_type=='mobile'){
            //check email exist in user table
            if(post.country_code){
                var is_exist_mobile = await db.where('country_code',post.country_code).where('mobile', post.field_value).get('b_user');
            }else{
                var is_exist_mobile = await db.where('mobile', post.field_value).get('b_user');
            }
            if (is_exist_mobile.length > 0) {
                callback(null, 'mobile_already_exist');
                return;
            }else{
                callback(null, 'mobile_success');
                return;
            }
        }
    } catch (err) {
        console.log(err);
        callback(err);
    }
}

/*===================Sign Up Model=============================*/
let signup = async (postData, callback) => {

    try {
            var is_exist_email = await db.where('email', postData['email']).get('b_user');
            if (is_exist_email.length > 0) {
                callback(null, 'email_already_exist', postData);
                return;
            }

            var is_exist_mobile = await db.where('mobile', postData['mobile']).get('b_user');
            if (is_exist_mobile.length > 0) {
                callback(null, 'mobile_already_exist', postData);
                return;
            }
            referalcode = randomize('0', '4');
            var is_exist_ref = await db.where('referral_code', referalcode).get('b_user');
            if (is_exist_ref.length > 0) {
                callback(null, 'email_already_exist', postData);
                return;
            }
            var set = false;
            while(set !== true) {
                referalcode = randomize('0', '4');
                var check = await common.is_exist_ref(referalcode);
                if(check === false) {
                    referal_code = referalcode;
                    set = true;
                } 
            }
            if(postData['referral_code']){
                var getUser = await db.select('user_id').where('referral_code', postData['referral_code']).get('b_user');
                var refer_by = getUser[0]['user_id'];
            }else{
                var refer_by = 0;
            }
            var signupData = {
                "full_name": postData['full_name'],
                "email": postData['email'],
                "country_code": postData['country_code'],
                "mobile": postData['mobile'],
                "referral_code":referal_code,
                "refer_by":refer_by,
                "verified": "true",
                "profile_completed": "Yes"
            }
            /*=======Insert Data======*/
            insertres = await db.returning('user_id').insert('b_user', signupData);
            var accesstoken = jwt.sign({ user_id: insertres.insertId }, 'BOATAPP', {});

            /*=======Get insert Data=======*/
            //Common Response for user
            response = await common.userDetail(insertres.insertId,accesstoken);

            callback(null, 'success', response);
            return;
        } catch (err) {
        console.log(err);
        callback(err);
    }
}


/*===================Normal login=============================*/
let signin = async (post, callback) => {
    try {
        var is_exist = await db.select('user_id').where('mobile', post['mobile']).where('status','active').get('b_user');
        if (is_exist.length > 0) {
                var accesstoken = jwt.sign({ user_id: is_exist[0]['user_id'] }, 'BOATAPP', {});
                response = await common.userDetail(is_exist[0]['user_id'],accesstoken);
                callback(null, 'verified_success', response);
                return;
        }else{
            callback(null, 'invalid_mobile', post);
            return
        }
    } catch (err) {
        console.log(err);
        callback(err);
    }
}

/*===================Reset Password=============================*/
let resetPassword = async (post, callback) => {
    try {
        //check user exist in user tabl
        var is_exist_mobile = await db.select('u_id').where('u_mobile', post['mobile']).get('b_user');

        if (is_exist_mobile.length > 0) {
                var PostData = {
                'u_password': md5(post['password'])
                };
            await db.where('u_id', is_exist_mobile[0]['u_id']).update('b_user', PostData);        
            callback(null, 'success', PostData);
            return;
        } else 
        {
            callback(null, 'mobile_not_exist', post);
            return;
        }
    } catch (err) {
        console.log(err)
        callback(err);
    }
}

/*===================Social Login/Signup Model=============================*/
let socialSignin = async (postData, callback) => {
    try {
            if(postData['social_id'] !== "") {
                if(postData['social_type']=='facebook'){
                    var is_exist_socialact = await db.select('user_id').where('facebook_id', postData['social_id']).get('b_user');
                    var PostData = {
                        'email': postData['email'],
                        'profile_pic': postData['profile_pic'],
                        'act_type':'social',
                        'facebook_id': postData['social_id']
                    };
                }else if(postData['social_type']=='google'){
                    var is_exist_socialact = await db.select('user_id').where('google_id', postData['social_id']).get('b_user');
                    var PostData = {
                        'email': postData['email'],
                        'profile_pic': postData['profile_pic'],
                        'google_id': postData['social_id'],
                        'act_type':'social'
                    };
                }else{ return; }
                if (is_exist_socialact.length > 0) {
                    await db.where('user_id', is_exist_socialact[0]['user_id']).update('b_user', PostData); 
                    var accesstoken = jwt.sign({ user_id: is_exist_socialact[0]['user_id'] }, 'BOATAPP', {});
                    reponse = await common.userDetail(is_exist_socialact[0]['user_id'],accesstoken);
                    callback(null, 'success', reponse);
                    return;
                }else{
                    if(postData['email'] !== "") {
                    var is_exist_email = await db.select('user_id').where('email', postData['email']).get('b_user');
                    if (is_exist_email.length > 0) {
                        if(postData['social_type']=='facebook'){
                            var PostData = {
                                'profile_pic': postData['profile_pic'],
                                'act_type':'social',
                                'facebook_id': postData['social_id']
                            };
                        }else if(postData['social_type']=='google'){
                            var PostData = {
                                'profile_pic': postData['profile_pic'],
                                'google_id': postData['social_id'],
                                'act_type':'social'
                            };
                        }else{ }
                        await db.where('user_id', is_exist_email[0]['user_id']).update('b_user', PostData);
                        var accesstoken = jwt.sign({ user_id: is_exist_email[0]['user_id'] }, 'BOATAPP', {}); 
                        reponse = await common.userDetail(is_exist_email[0]['user_id'],accesstoken);
                        callback(null, 'success', reponse);
                        return;
                    }{
                    var set = false;
                    while(set !== true) {
                        referalcode = randomize('0', '4');
                        var check = await common.is_exist_ref(referalcode);
                        if(check === false) {
                            referal_code = referalcode;
                            set = true;
                        } 
                    }
                    if(postData['referral_code']){
                        var getUser = await db.select('user_id').where('referral_code', postData['referral_code']).get('b_user');
                        var refer_by = getUser[0]['user_id'];
                    }else{
                        var refer_by = 0;
                    }
                    if(postData['social_type']=='facebook'){
                            var signupData = {
                                "full_name": postData['full_name'],
                                "email": postData['email'],
                                "profile_pic": postData['profile_pic'],
                                "act_type":"social",
                                "country_code": postData['country_code'],
                                "mobile": postData['mobile'],
                                'facebook_id': postData['social_id'],
                                "referral_code":referal_code,
                                "refer_by":refer_by,
                                "verified": "true"
                            }
                        }else if(postData['social_type']=='google'){
                            var signupData = {
                                "full_name": postData['full_name'],
                                "email": postData['email'],
                                "profile_pic": postData['profile_pic'],
                                "act_type":"social",
                                "country_code": postData['country_code'],
                                "mobile": postData['mobile'],
                                'google_id': postData['social_id'],
                                "referral_code":referal_code,
                                "refer_by":refer_by,
                                "verified": "true"
                            }
                        }else{ 
                            return;
                        }
                            /*=======Insert Data======*/
                        insertres = await db.returning('user_id').insert('b_user', signupData);
                        var user_id = insertres.insertId;
                        var accesstoken = jwt.sign({ user_id: user_id}, 'BOATAPP', {});
                        reponse = await common.userDetail(user_id,accesstoken);

                        callback(null, 'success', reponse);
                        return;
                    }
                }else{
                    var set = false;
                    while(set !== true) {
                        referalcode = randomize('0', '4');
                        var check = await common.is_exist_ref(referalcode);
                        if(check === false) {
                            referal_code = referalcode;
                            set = true;
                        } 
                    }
                    if(postData['referral_code']){
                        var getUser = await db.select('user_id').where('referral_code', postData['referral_code']).get('b_user');
                        var refer_by = getUser[0]['user_id'];
                    }else{
                        var refer_by = 0;
                    }
                    if(postData['social_type']=='facebook'){
                        var signupData = {
                            "full_name": postData['full_name'],
                            "email": postData['email'],
                            "profile_pic": postData['profile_pic'],
                            "act_type":"social",
                            "country_code": postData['country_code'],
                            "mobile": postData['mobile'],
                            'facebook_id': postData['social_id'],
                            "referral_code":referal_code,
                            "refer_by":refer_by,
                            "verified": "true"
                        }
                    }else if(postData['social_type']=='google'){
                        var signupData = {
                           "full_name": postData['full_name'],
                            "email": postData['email'],
                            "profile_pic": postData['profile_pic'],
                            "act_type":"social",
                            "country_code": postData['country_code'],
                            "mobile": postData['mobile'],
                            'google_id': postData['social_id'],
                            "referral_code":referal_code,
                            "refer_by":refer_by,
                            "verified": "true"
                        }
                    }else{ 
                        return;
                    }
                        /*=======Insert Data======*/
                    insertres = await db.returning('user_id').insert('b_user', signupData);
                    var user_id = insertres.insertId;
                    var accesstoken = jwt.sign({ user_id: user_id}, 'BOATAPP', {});
                    reponse = await common.userDetail(user_id,accesstoken);
                    callback(null, 'success', reponse);
                    return;
                }

                }
            }else{
                return;
            }
        } catch (err) {
        console.log(err);
        callback(err);
    }
}


/*===================Edit Profile Model=============================*/
let editProfile = async (postData, files, callback) => {
    try {

            var is_exist_email = await db.query(`Select user_id from b_user where email='${postData['email'][0]}' and user_id!='${postData['user_id'][0]}'`);
            if (is_exist_email.length > 0) {
                callback(null, 'email_already_exist', postData);
                return;
            }

            var is_exist_account = await db.select('user_id,mobile').where('user_id', postData['user_id'][0]).where('status','active').get('b_user');
            if (is_exist_account.length > 0) {
                // Profile Pictrure
                var path = 'uploads/userprofile';

                var profile_picture = [];
                // if (files['profile_picture'][0]['originalFilename'] != '')
                profile_picture = await common.uploadImages(files, path, 'profile_pic');

                var signupData = {
                    "full_name": postData['full_name'][0],
                    "email": postData['email'][0]
                }
                if (profile_picture.length > 0) {
                    signupData['profile_pic'] = profile_picture[0];
                }
                /*=======Insert Data======*/
                await db.where('user_id', is_exist_account[0]['user_id']).update('b_user', signupData);
               
                //Common Response for user
                response = await common.userDetail(is_exist_account[0]['user_id'],'');
                callback(null, 'success', response);
                return;
            }else{
                callback(null, 'invalid_user', postData);
                return;
            }
        } catch (err) {
        console.log(err);
        callback(err);
    }
}


/*===================Delete Account Model=============================*/
let deleteAccount = async (postData,callback) => {
    try {
        var is_user_exist = await db.where('u_id', postData['user_id']).where('u_status', 'active').get('b_user');
        if (is_user_exist.length > 0) {
            var updateData = {
                "u_status": "inactive"
            }
            await db.where('u_id', postData['user_id']).update('b_user', updateData);
            callback(null, 'success');
            return;
        }else{
            callback(null, 'invalid_user');
            return;
        }
    } catch (err) {
        console.log(err);
        callback(err);
    }
}



/*===================Get Faq Model=============================*/

let getFaq = async (post, callback) => {
    try {
        var faq = await db.select('post_id,post_name,post_description').where('post_category','faq').get('q_post');
        if (faq.length > 0) {
            for (let i = 0; i < faq.length; i++) {
                faq[i]['post_id'] = faq[i]['post_id'].toString();
                faq[i]['post_name'] = faq[i]['post_name'];
                faq[i]['post_description'] = faq[i]['post_description'];
            }
        }else{
            faq = {};
        }
        callback(null,'success',faq);
        return;
    } catch (err) {
        console.log(err);
        callback(err);
    }
}


/*===================Get Pages Model=============================*/

let getPages = async (post, callback) => {
    try {
        var faq = await db.select('post_id,post_name,post_description').where('post_category','page').where('post_identifier',post.identifier).get('q_post');
        if (faq.length > 0) {
            page = {
                "post_id":faq[0]['post_id'].toString(),
                "post_name":faq[0]['post_name'] ? faq[0]['post_name']:'',
                "post_description":faq[0]['post_description'] ? faq[0]['post_description']:''
            }
            callback(null,'success',page);
            return;
        }else{
            console.log(err);
            callback(err);
        }
    } catch (err) {
        console.log(err);
        callback(err);
    }
}


/*===================Update Device token Model=============================*/
let updateToken = async (postData,callback) => {
    try {
        var is_user_exist = await db.where('u_id', postData['user_id']).where('u_status', 'active').get('b_user');
        if (is_user_exist.length > 0) {
                var updateData = {
                    "u_devicetype":postData['device_type'],
                    "u_devicetoken": postData['device_token'],
                }
                await db.where('u_id', postData['user_id']).update('b_user_info', updateData);
                callback(null, 'success');
                return;
        }else{
            callback(null, 'invalid_user');
            return;
        }
    } catch (err) {
        console.log(err);
        callback(err);
    }
}

/*===================Notification on off Model=============================*/
let setNotification = async (postData,callback) => {
    try {
        var is_user_exist = await db.where('u_id', postData['user_id']).where('u_status', 'active').get('b_user');
        if (is_user_exist.length > 0) {
            await db.query(`UPDATE b_user SET notification = IF(notification='1', '0', '1') where u_id='${postData.user_id}'`);
            result =  await db.select('notification').where('u_id', postData['user_id']).get('b_user');
                callback(null, 'success',result[0]['notification']);
                return;
        }else{
            callback(null, 'invalid_user');
            return;
        }
    } catch (err) {
        console.log(err);
        callback(err);
    }
}

/*===================Get Notification list Model=============================*/

let getNotification = async (post, callback) => {
    try {
        if(post.limit){
            offset = post.offset; limit = post.limit;
        }else{
            offset = 0; limit = 10;
        }
        var notify = await db.query(`Select n.activity,n.action_id,n.from_user,n.to_user,n.message,n.created_on,u.u_username,u.u_profilepic from q_notification as n join b_user as u on n.from_user=u.u_id where n.to_user='${post.user_id}' order by notification_id desc limit ${limit} offset ${offset}`);
        if (notify.length > 0) {
            var arr = [];
            for (let q = 0; q < notify.length; q++) {
                if (notify[q]['u_profilepic'] != '' && notify[q]['u_profilepic']!== null){
                  var pat = /^https?:\/\//i;
                  if (pat.test(notify[q]['u_profilepic'])){
                    profile_pic = notify[q]['u_profilepic'];
                  }
                  else {
                    profile_pic = process.env.BASE_URL + 'uploads/userprofile/' + notify[q]['u_profilepic'];
                  }   
                }else{
                   var profile_pic = '';
                }
                arr.push({
                    user_id: notify[q]['from_user'].toString(),
                    username:  notify[q]['u_username'],
                    profilepic: profile_pic,
                    activity:notify[q]['activity'],
                    message:notify[q]['message'],
                    created_on:notify[q]['created_on']
                });
            }
        }else{
            arr = [];
        }
        callback(null,'success',arr);
        return;
    } catch (err) {
        console.log(err);
        callback(err);
    }
}

/*===================Log out Model=============================*/
let signOff = async (postData,callback) => {
    try {
        var updateData = {
            "u_devicetoken": ''
        }
        await db.where('u_id', postData['user_id']).update('b_user_info', updateData);
        callback(null, 'success');
        return;
    } catch (err) {
        console.log(err);
        callback(err);
    }
}

/*===================Upload Image Example=============================*/
let uploadBuild = async (postData, files, callback) => {
    let path = 'uploads';

    uploadBuildUrl = await common.uploadBuild(files, path, 'upload_build');

    baseurl = process.env.BASE_URL + 'uploads/' + uploadBuildUrl[0];

    callback(null, baseurl);
}

/*===================Upload S3 Example=============================*/
let uploadS3 = async (postData, files, callback) => {
    let path = 'uploads';
    common.uploadS3Files(files, (images) => {
        callback(null, images);
    });
}

/*===================sendMail Example=============================*/
let sendMail = async (postData, callback) => {

    var mailOptions = {
        from: postData['from'],
        to: postData['to'],
        subject: postData['subject'],
        text: postData['text'],
    };

    common.transporter.sendMail(mailOptions, (err, info) => {
        if (err) {
            console.log(err);
        } else {

            console.log(info.response);

            callback(null, info);
        }
    });
}



module.exports = {
    signup,
    signin,
    checkfieldExist,
    resetPassword,
    socialSignin,
    editProfile,
    deleteAccount,
    getFaq,
    getPages,
    updateToken,
    setNotification,
    getNotification,
    signOff
}
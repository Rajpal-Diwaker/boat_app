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
                var is_exist_email = await db.query(`Select user_id from b_captain where email='${post['field_value']}' and user_id!='${post['user_id']}'`);
            }else{
                var is_exist_email = await db.where('email', post.field_value).get('b_captain');
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
                var is_exist_mobile = await db.where('country_code',post.country_code).where('mobile', post.field_value).get('b_captain');
            }else{
                var is_exist_mobile = await db.where('mobile', post.field_value).get('b_captain');
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
let signup = async (postData,files, callback) => {

    try {
            var is_exist_email = await db.where('email', postData['email'][0]).get('b_captain');
            if (is_exist_email.length > 0) {
                callback(null, 'email_already_exist', postData);
                return;
            }

            var is_exist_mobile = await db.where('mobile', postData['mobile'][0]).get('b_captain');
            if (is_exist_mobile.length > 0) {
                callback(null, 'mobile_already_exist', postData);
                return;
            }

            var path = 'uploads/captain';
            var profile_pic = [];
            profile_pic = await common.uploadImages(files, path, 'profile_pic');

            var path = 'uploads/captainid';
            var idproof1 = [];
            idproof1 = await common.uploadImages(files, path, 'idproof1');

            var path = 'uploads/captainid';
            var idproof2 = [];
            idproof2 = await common.uploadImages(files, path, 'idproof2');
           
            var signupData = {
                "full_name": postData['full_name'][0],
                "email": postData['email'][0],
                "country_code": postData['country_code'][0],
                "mobile": postData['mobile'][0],
                "info":postData['info'][0],
                "amenities":postData['amenities'][0],
                "verified": "false",
                "profile_completed": "Yes"
            }
            if (profile_pic.length > 0) {
                signupData['profile_pic'] = profile_pic[0];
            }
            if (idproof1.length > 0) {
                signupData['idproof1'] = idproof1[0];
            }
            if (idproof2.length > 0) {
                signupData['idproof2'] = idproof2[0];
            }
            console.log(signupData);
            /*=======Insert Data======*/
            insertres = await db.returning('captain_id').insert('b_captain', signupData);
            var accesstoken = jwt.sign({ captain_id: insertres.insertId }, 'BOATAPP', {});
            var captain_id = insertres.insertId;
            var path = 'uploads/boat';
            var type = 'boatimage';
            boat_pic = await common.uploadmultipleImages(files, path, 'boat_pic',captain_id,type);
            /*=======Get insert Data=======*/
            //Common Response for user
            response = await common.captainDetail(captain_id,accesstoken);

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
        var is_exist = await db.select('captain_id').where('mobile', post['mobile']).where('status','active').get('b_captain');
        if (is_exist.length > 0) {
                var accesstoken = jwt.sign({ user_id: is_exist[0]['captain_id'] }, 'BOATAPP', {});
                response = await common.captainDetail(is_exist[0]['captain_id'],accesstoken);
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
        var is_exist_mobile = await db.select('u_id').where('u_mobile', post['mobile']).get('b_captain');

        if (is_exist_mobile.length > 0) {
                var PostData = {
                'u_password': md5(post['password'])
                };
            await db.where('u_id', is_exist_mobile[0]['u_id']).update('b_captain', PostData);        
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

/*===================Edit Profile Model=============================*/
let editProfile = async (postData, files, callback) => {
    try {

            var is_exist_email = await db.query(`Select user_id from b_captain where email='${postData['email'][0]}' and user_id!='${postData['user_id'][0]}'`);
            if (is_exist_email.length > 0) {
                callback(null, 'email_already_exist', postData);
                return;
            }

            var is_exist_account = await db.select('user_id,mobile').where('user_id', postData['user_id'][0]).where('status','active').get('b_captain');
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
                await db.where('user_id', is_exist_account[0]['user_id']).update('b_captain', signupData);
               
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


/*===================Update Device token Model=============================*/
let updateToken = async (postData,callback) => {
    try {
        var is_user_exist = await db.where('u_id', postData['user_id']).where('u_status', 'active').get('b_captain');
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

/*===================Get Notification list Model=============================*/

let getNotification = async (post, callback) => {
    try {
        if(post.limit){
            offset = post.offset; limit = post.limit;
        }else{
            offset = 0; limit = 10;
        }
        var notify = await db.query(`Select n.activity,n.action_id,n.from_user,n.to_user,n.message,n.created_on,u.u_username,u.u_profilepic from q_notification as n join b_captain as u on n.from_user=u.u_id where n.to_user='${post.user_id}' order by notification_id desc limit ${limit} offset ${offset}`);
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

/*===================Get Amenities Model=============================*/

let getAmenities = async (post, callback) => {
    try {
        var response = [];
        var amenArr = await db.select('parent_amenities').group_by('parent_amenities').get('b_amenities');
        if (amenArr.length > 0) {
           for (let a = 0; a < amenArr.length; a++) {
                var amenitiesArr = await db.select('amenitis_id,amenities,image').where('parent_amenities',amenArr[a]['parent_amenities']).where('status','active').get('b_amenities');
                if (amenitiesArr.length > 0) {
                    var arr = [];
                    for (let q = 0; q < amenitiesArr.length; q++) {
                        if (amenitiesArr[q]['image'] != '' && amenitiesArr[q]['image']!== null){
                            var image = process.env.BASE_URL + 'uploads/' + amenitiesArr[q]['image'];
                        }else{
                           var image = '';
                        }
                        arr.push({
                            amenitis_id:  amenitiesArr[q]['amenitis_id'].toString(),
                            amenities:  amenitiesArr[q]['amenities'],
                            image: image,
                        });
                    }
                }else{
                    var arr = [];
                }
                response.push({
                    parent_amenities:  amenArr[a]['parent_amenities'],
                    amenitiesArr: arr,
                });
            }
        }else{
            response = {};
        }
        callback(null,'success',response);
        return;
    } catch (err) {
        console.log(err);
        callback(err);
    }
}


module.exports = {
    signup,
    signin,
    checkfieldExist,
    resetPassword,
    editProfile,
    updateToken,
    getNotification,
    signOff,
    getAmenities
}
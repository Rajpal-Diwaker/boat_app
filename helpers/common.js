let fs = require('fs');
require('dotenv').config()
const config = require('../config/config').config;
const db = require('../config/database').db;
const AWS = require('aws-sdk');
const nodemailer = require('nodemailer');
const moment = require('moment');
const FCM = require('fcm-push');
const { resolve } = require('path');
const { reject } = require('async');
const serverKey = process.env.SERVER_KEY;
const fcm = new FCM(serverKey);
var accountSid = process.env.TWILIO_ACCOUNT_SID; // Your Account SID from www.twilio.com/console
var authToken = process.env.TWILIO_AUTH_TOKEN; // Your Auth Token from www.twilio.com/console
const client = require('twilio')(accountSid, authToken);
const twilio_from = process.env.TWILIO_FROM;


// ================== Upload Image Function Start==================//

let uploadImages = (files, path, fileName, cb) => {
    var fileExtension;
    var imagesNames = [];
    var imgArray = files[fileName];
    if (typeof imgArray !== 'undefined' && imgArray.length > 0) {
        for (var i = 0; i < imgArray.length; i++) {
            var newPath = process.env.ROOT_DIR + path + '/';
            var singleImg = imgArray[i];
            let date = new Date();
            fileExtension = imgArray[i].path.replace(/^.*\./, '');
            newPath += date.getTime() + i + '.' + fileExtension;
            imagesNames.push(date.getTime() + i + '.' + fileExtension);
            readAndWriteFile(singleImg, newPath);
        }
        return imagesNames
        //cb(imagesNames);
    } else {
        return [];
    }
}


// ================== Upload Multiple Image Function Start==================//

async function uploadmultipleImages(files, path, fileName, id, type){
    var fileExtension;
    var imagesNames = '';
    var imgArray = files[fileName];
    if (typeof imgArray !== 'undefined' && imgArray.length > 0) {
        for (var i = 0; i < imgArray.length; i++) {
            var newPath = process.env.ROOT_DIR + path + '/';
           // console.log(newPath);
            var singleImg = imgArray[i];
            let date = new Date();
            fileExtension = imgArray[i].path.replace(/^.*\./, '');
            newPath += date.getTime() + i + '.' + fileExtension;
            imagesNames = date.getTime() + i + '.' + fileExtension;
            readAndWriteFile(singleImg, newPath);
         //   console.log(imagesNames);
            if(type=='boatimage'){
                var imgdata = {
                "captain_id": id,
                "image": imagesNames
                }
                insertres = await db.returning('boat_img_id').insert('b_boat_images', imgdata);
            }else if(type=='product'){
                var imgdata = {
                    "captain_id": id,
                    "image": imagesNames
                }
                insertres = await db.returning('boat_img_id').insert('b_boat_images', imgdata);
            }else{

            }            
        }
        
        return true;
        //cb(imagesNames);
    } else {
        return [];
    }
}



// ================== Upload build Start==================//

let uploadBuild = (files, path, fileName, cb) => {
    var fileExtension;
    var imagesNames = [];
    var imgArray = files[fileName];
    if (typeof imgArray !== 'undefined' && imgArray.length > 0) {
        for (var i = 0; i < imgArray.length; i++) {
            var newPath = process.env.ROOT_DIR + path + '/';
            var singleImg = imgArray[i];
            let date = new Date();
            fileExtension = imgArray[i].path.replace(/^.*\./, '');
            newPath += 'reachout.' + fileExtension;
            imagesNames.push('reachout.' + fileExtension);
            readAndWriteFile(singleImg, newPath);
        }
        return imagesNames
        //cb(imagesNames);
    } else {
        return [];
    }
}


function readAndWriteFile(singleImg, newPath) {
    fs.readFile(singleImg.path, function (err, data) {
        fs.writeFile(newPath, data, function (err) {
            if (err) console.log('ERRRRRR!! :' + err);
            console.log('success: ' + singleImg.originalFilename + ' - ' + newPath);
        })
    })
}


// ================== Upload S3  Function Start==================//

let uploadS3Files = (files, callback) => {

    var s3 = new AWS.S3();

    s3.config.update({
        accessKeyId: '*****************',
        secretAccessKey: '*********************************'
    });
    s3.config.region = 'us-west-2';
    let fileData = fs.readFileSync(files.images[0].path);
    var params = {
        Bucket: '********************',
        Key: files.images[0].originalFilename,
        Body: fileData,
        ContentType: files.images[0].type,
        ACL: 'public-read'
    };



    s3.putObject(params, function (err, pres) {
        if (err) {
            console.log("Error uploading image: ", err);
            return;
        } else {
            console.log("uploading image successfully URL: https://bucketName.s3-us-west-2.amazonaws.com/" + files.images[0].originalFilename);
            callback(files.images[0].originalFilename);
            return
        }
    });

}


let transporter = nodemailer.createTransport({
    service: 'gmail',
    auth: {
        user: process.env.EMAIL_USER,
        pass: process.env.EMAIL_PASS
    }
});



async function sendNotification(user_id = null, messageObj = null) {
    try {
        if(messageObj.action_id){
            var insertdata = {
                "activity":messageObj.type,
                "action_id": messageObj.action_id,
                "from_user": messageObj.from_user,
                "to_user":messageObj.to_user,
                "message": messageObj.msg
                }
            }else{
                var insertdata = {
                "activity":messageObj.type,
                "from_user": messageObj.from_user,
                "to_user":messageObj.to_user,
                "message": messageObj.msg
                }
            }
        
        insertres = await db.insert('q_notification', insertdata);
        var user = await db.select('u_devicetoken,u_devicetype').where('u_id', user_id).get('q_user_info');
        if(user[0]['u_devicetoken']){
            if(user[0]['u_devicetype']=='ios'){
            var message = {
                registration_ids: [user[0].u_devicetoken],
                "notification": {
                    "title": messageObj.title,
                    "type": messageObj.type,
                    "body": messageObj.msg,
                    "noti_data": messageObj
                },
                "sound": "NotificationTone.wav",
                'badge': 1,
                "priority": "high"
            };
            }else{
                var message = {
                    registration_ids: [user[0].u_devicetoken],
                    "data": {
                        "title": messageObj.title,
                        "type": messageObj.type,
                        "body": messageObj.msg,
                        "noti_data": messageObj
                    },
                    "sound": "NotificationTone.wav",
                    'badge': 1,
                    "priority": "high"
                };
            }
        }else{
            return;
        }
    

        return new Promise((resolve) => {
            fcm.send(message, function (err, response) {
                if (err) {
                    console.log("errror" + err);
                } else {
                    console.log("Successfully sent with response: " + response);
                    resolve(true)
                }
            });

        });
    }
    catch (err) {
        console.log(err);
    }

}


async function sendSMS(message, number) {

    return new Promise((resolve) => {

        client.messages.create({
            body: message,
            from: twilio_from,
            to: number
        }).then((message) => {
            resolve(true)

        })
            .catch((response) => {
                console.log(response);
            })

    });
}


async function userDetail(user_id,accesstoken ) {
    var result = await db.select('user_id,full_name,email,country_code,mobile,profile_pic,referral_code,status,act_type').where('user_id', user_id).where('status', 'active').get('b_user');
    if (result[0]['profile_pic'] != '' && result[0]['profile_pic']!== null){
      var pat = /^https?:\/\//i;
      if (pat.test(result[0]['profile_pic'])){
        profile_pic = result[0]['profile_pic'];
      }
      else {
        profile_pic = process.env.BASE_URL + 'uploads/userprofile/' + result[0]['profile_pic'];
      }   
    }else{
       var profile_pic = '';
    }
   
    response = {
        "user_id":result[0]['user_id'].toString(),
        "full_name":result[0]['full_name'] ? result[0]['full_name']:'',
        "country_code":result[0]['country_code'].toString(),
        "mobile":result[0]['mobile'].toString(),
        "profile_pic":profile_pic,
        "email":result[0]['email'] ? result[0]['email']:'',
        "act_type":result[0]['act_type'] ? result[0]['act_type']:'',
        "referral_code":result[0]['referral_code'],
        "accesstoken":accesstoken?accesstoken:''
    }
   // console.log(response);
    return response;
}




async function captainDetail(captain_id,accesstoken ) {
    var result = await db.select('captain_id,full_name,email,country_code,mobile,profile_pic,info,amenities,status').where('captain_id', captain_id).where('status', 'active').get('b_captain');
    //console.log(result);
    if (result[0]['profile_pic'] != '' && result[0]['profile_pic']!== null){
        profile_pic = process.env.BASE_URL + 'uploads/captain/' + result[0]['profile_pic'];
    }else{
       var profile_pic = '';
    }
    var boatArr = await db.select('image').where('captain_id', captain_id).get('b_boat_images');
    if(boatArr.length > 0){
        var arr = [];
        for (let q = 0; q < boatArr.length; q++) {
            if (boatArr[q]['image'] != '' && boatArr[q]['image']!== null){
                boatimage = process.env.BASE_URL + 'uploads/boat/' + boatArr[q]['image'];
                arr.push({
                    boatimage
                });
            }         
        }
    }else{
        arr = [];
    }
   
    response = {
        "captain_id":result[0]['captain_id'].toString(),
        "full_name":result[0]['full_name'] ? result[0]['full_name']:'',
        "country_code":result[0]['country_code'].toString(),
        "mobile":result[0]['mobile'].toString(),
        "profile_pic":profile_pic,
        "info":result[0]['info'] ? result[0]['info']:'',
        "email":result[0]['email'] ? result[0]['email']:'',
        "accesstoken":accesstoken?accesstoken:'',
        "boat_image":arr
    }
   // console.log(response);
    return response;
}

async function is_exist_ref(referalcode ) {
    var is_exist = await db.where('referral_code', referalcode).get('b_user');
    if (is_exist.length > 0) {
        return true;
    }else{
        return false;
    }
}






module.exports = {
    uploadBuild,
    uploadImages,
    uploadS3Files,
    transporter,
    sendNotification,
    userDetail,
    is_exist_ref,
    captainDetail,
    uploadmultipleImages
}
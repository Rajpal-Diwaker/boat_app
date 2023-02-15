const userModel = require('../../../models/api/v1/user');


// ==================== Get Error Message====================== //
let getErrorMsg = (required, request, cb) => {
    var count = required.length;
    var i = 0;
    required.forEach(function (value, key) {
        if ((request[value] == '' || request[value] == undefined) && i == key && i < count) {
            var data = {};
            if (key < count - (count - (key + 1))) {
                var data = {
                    "statusCode": 400,
                    "APICODERESULT": value + " Key is empty or missing"
                };

                cb(data);
                return true;
            }
        }
        i++;
    });
    if (i == count) {
        cb('');
    }
}


/*===================Check email/mobile exist Controler=============================*/
let checkfieldExist = (data, callback) => {
   var req = {'field_value':data.field_value,'field_type':data.field_type,'country_code':data.country_code,'user_id':data.user_id}
    userModel.checkfieldExist(req, (err, api_status, response) => {
        if (api_status == 'username_success') {
            callback({
                "statusCode": 200,
                "type":"Username",
                "APICODERESULT": "Available"
            });
        } else if (api_status == 'email_success') {
            callback({
                "statusCode": 200,
                "type":"Email",
                "APICODERESULT": "Available."
            });
        }else if (api_status == 'mobile_success') {
            callback({
                "statusCode": 200,
                "type":"Mobile",
                "APICODERESULT": "Available."
            });
        } else if (api_status == 'email_already_exist') {
            callback({
                "statusCode": 201,
                "type":"Email",
                "APICODERESULT": "This email is already registered with us."
            });
        }else if (api_status == 'mobile_already_exist') {
            callback({
                "statusCode": 201,
                "type":"Mobile",
                "APICODERESULT": "This mobile number is already registered with us."
            });
        } else {
            callback({
                "statusCode": 200,
                "APICODERESULT": "User Result",
                "result": response
            });
        }
    });
}


/*===================Sign Up Controler=============================*/
let signup = (data, callback) => {

    userModel.signup(data, (err, api_status, response) => {
        if (err) {
            callback({
                "statusCode": 500,
                "APICODERESULT": "Something went wrong",
                "error": err
            });
        } else if (api_status == 'username_already_exist') {
            callback({
                "statusCode": 201,
                "APICODERESULT": "Username already exists."
            });
        } else if (api_status == 'email_already_exist') {
            callback({
                "statusCode": 201,
                "APICODERESULT": "Email is already registered."
            });
        }else if (api_status == 'mobile_already_exist') {
            callback({
                "statusCode": 201,
                "APICODERESULT": "Mobile is already registered."
            });
        } else {
            callback({
                "statusCode": 200,
                "APICODERESULT": "User Result",
                "result": response
            });
        }
    });
}

/*===================Normal Signin Controler=============================*/

let signin = (data, callback) => {
    userModel.signin(data, (err, api_status, response) => {
        if (err) {
            callback({
                "statusCode": 500,
                "APICODERESULT": "Something went wrong",
                "error": err
            });
        } else if (api_status == 'verified_success') {
            callback({
                "statusCode": 200,
                "APICODERESULT": "User Result",
                "result": response
            });
        } else {
           callback({
                "statusCode": 201,
                "APICODERESULT": "Your account is temporary suspended pls contact support team."
            });
        }
    });
}


/*===================Reset Password Controler=============================*/
let resetPassword = (data, callback) => {
    userModel.resetPassword(data, (err, api_status, response) => {
        if (err) {
            callback({
                "statusCode": 500,
                "APICODERESULT": "Something went wrong",
                "error": err
            });
        } else if (api_status == 'mobile_not_exist') {
            callback({
                "statusCode": 201,
                "APICODERESULT": "Invalid Mobile Number."
            });
        } else {
            callback({
                "statusCode": 200,
                "APICODERESULT": "Password changed successfully.",
            //    "result": response
            });
        }
    });
}

/*===================Social Login/Signup Controler=============================*/

let socialSignin = (data, callback) => {
    userModel.socialSignin(data, (err, api_status, response) => {
        if (err) {
            callback({
                "statusCode": 500,
                "APICODERESULT": "Something went wrong",
                "error": err
            });
        } else if (api_status == 'success') {
            callback({
                "statusCode": 200,
                "APICODERESULT": "User Result",
                "result": response
            });
        }else {
           callback({
                "statusCode": 201,
                "APICODERESULT": "Invalid Email."
            });
        }
    });
}

/*===================Edit Profile Controler=============================*/
let editProfile = (data, files, callback) => {

    userModel.editProfile(data, files, (err, api_status, response) => {
        if (err) {
            callback({
                "statusCode": 500,
                "APICODERESULT": "Something went wrong",
                "error": err
            });
        } else if (api_status == 'invalid_user') {
            callback({
                "statusCode": 300,
                "APICODERESULT": "User does not exist."
            });
        }else if (api_status == 'email_already_exist') {
            callback({
                "statusCode": 201,
                "APICODERESULT": "Email is already in use."
            });
        }else if (api_status == 'mobile_already_exist') {
            callback({
                "statusCode": 201,
                "APICODERESULT": "Mobile is already registered."
            });
        } else {
            callback({
                "statusCode": 200,
                "APICODERESULT": "Your profile has been updated successfully.",
                "result": response
            });
        }
    });
}

/*===================User Profile Controler=============================*/
let getProfile = (data, callback) => {
   var req = {'user_id':data.user_id,'date':data.date,'filter_by':data.filter_by,'type':data.type,'offset':data.offset,'limit':data.limit,'login_id':data.login_id}
    userModel.getProfile(req, (err, api_status, response) => {
        if (api_status == 'success') {
            callback({
                "statusCode": 200,
                "APICODERESULT": "Response.",
                "result": response
            });
        } else if (api_status == 'answer_list') {
            callback({
                "statusCode": 200,
                "APICODERESULT": "Answer List.",
                "result": response
            });
        }else if (api_status == 'invalid_user') {
            callback({
                "statusCode": 300,
                "APICODERESULT": "User does not exist."
            });
        }else {
            callback({
                "statusCode": 200,
                "APICODERESULT": "Nothing found"
            });
        }
    });
}


/*===================Contact Us Controler=============================*/
let contactUs = (data, files, callback) => {

    userModel.contactUs(data, files, (err, api_status, response) => {
        if (err) {
            callback({
                "statusCode": 500,
                "APICODERESULT": "Something went wrong",
                "error": err
            });
        }else if (api_status == 'invalid_user') {
            callback({
                "statusCode": 300,
                "APICODERESULT": "User does not exist."
            });
        } else {
            callback({
                "statusCode": 200,
                "APICODERESULT": "Successfully posted",
             //   "result": response
            });
        }
    });
}

/*===================Delete Account Controler=============================*/

let deleteAccount = (data, callback) => {
    userModel.deleteAccount(data, (err, api_status, response) => {
        if (err) {
            callback({
                "statusCode": 500,
                "APICODERESULT": "Something went wrong",
                "error": err
            });
        } else if (api_status == 'success') {
            callback({
                "statusCode": 200,
                "APICODERESULT": "Success."
            });
        }else {
           callback({
                "statusCode": 300,
                "APICODERESULT": "User does not exist."
            });
        }
    });
}

/*===================Get Faq Controler=============================*/
let getFaq = (data, callback) => {
    userModel.getFaq(data,(err, api_status, response) => {
            callback({
                "statusCode": 200,
                "APICODERESULT": "Faq",
                "result":response
            });
    });
}


/*===================Get Pages Controler=============================*/
let getPages = (data, callback) => {
   var req = {'identifier':data.identifier}
    userModel.getPages(req, (err, api_status, response) => {
        if (api_status == 'success') {
            callback({
                "statusCode": 200,
                "APICODERESULT": "Response.",
                "result": response
            });
        }else {
            callback({
                "statusCode": 200,
                "APICODERESULT": "Nothing found"
            });
        }
    });
}

/*===================Update Device token Controler=============================*/
let updateToken = (data, callback) => {

    userModel.updateToken(data, (err, api_status, response) => {
        if (err) {
            callback({
                "statusCode": 500,
                "APICODERESULT": "Something went wrong",
                "error": err
            });
        }else if (api_status == 'invalid_user') {
            callback({
                "statusCode": 300,
                "APICODERESULT": "User does not exist."
            });
        } else {
            callback({
                "statusCode": 200,
                "APICODERESULT": "Successfully updated",
             //   "result": response
            });
        }
    });
}

/*===================Notification on off Controler=============================*/
let setNotification = (data, callback) => {

    userModel.setNotification(data, (err, api_status, response) => {
        if (err) {
            callback({
                "statusCode": 500,
                "APICODERESULT": "Something went wrong",
                "error": err
            });
        }else if (api_status == 'invalid_user') {
            callback({
                "statusCode": 300,
                "APICODERESULT": "User does not exist."
            });
        } else {
            callback({
                "statusCode": 200,
                "APICODERESULT": "Successfully updated",
                "result": response
            });
        }
    });
}

/*===================Get Notification Controler=============================*/
let getNotification = (data, callback) => {
    var req = {'user_id':data.user_id,'offset':data.offset,'limit':data.limit}
    userModel.getNotification(data, (err, api_status, response) => {
        if (err) {
            callback({
                "statusCode": 500,
                "APICODERESULT": "Something went wrong",
                "error": err
            });
        }else if (api_status == 'invalid_user') {
            callback({
                "statusCode": 300,
                "APICODERESULT": "User does not exist."
            });
        } else {
            callback({
                "statusCode": 200,
                "APICODERESULT": "Notification List",
                "result": response
            });
        }
    });
}

/*===================Log out Controler=============================*/
let signOff = (data, callback) => {

    userModel.signOff(data, (err, api_status, response) => {
        if (err) {
            callback({
                "statusCode": 500,
                "APICODERESULT": "Something went wrong",
                "error": err
            });
        } else {
            callback({
                "statusCode": 200,
                "APICODERESULT": "Success",
             //   "result": response
            });
        }
    });
}


/*===================Upload Image Controler=============================*/
let uploadBuild = (data, files, callback) => {
    userModel.uploadBuild(data, files, (err, response) => {
        if (err) {
            callback({
                "statusCode": 500,
                "APICODERESULT": "Something went wrong",
                "error": err
            });
        } else {
            callback({
                "statusCode": 200,
                "APICODERESULT": "Success",
                "result": response
            })
        }
    });
}


/*===================Upload Image Controler=============================*/
let uploadS3 = (data, files, callback) => {
    userModel.uploadS3(data, files, (err, response) => {
        if (err) {
            callback({
                "statusCode": 500,
                "APICODERESULT": "Something went wrong",
                "error": err
            });
        } else {
            callback({
                "statusCode": 200,
                "APICODERESULT": "Success",
                "result": response
            })
        }
    });
}


/*===================sendMail Controler=============================*/
let sendMail = (data, callback) => {
    userModel.sendMail(data, (err, response) => {
        if (err) {
            callback({
                "statusCode": 500,
                "APICODERESULT": "Something went wrong",
                "error": err
            });
        } else {
            callback({
                "statusCode": 200,
                "APICODERESULT": "Success",
                "result": response
            });
        }
    });
}





module.exports = {
    getErrorMsg,
    signup,
    signin,
    checkfieldExist,
    resetPassword,
    socialSignin,
    editProfile,
    getProfile,
    contactUs,
    deleteAccount,
    getFaq,
    getPages,
    updateToken,
    setNotification,
    getNotification,
    signOff
}
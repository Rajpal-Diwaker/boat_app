const express = require('express');
const router = express.Router();
let captainController = require('../../../controllers/api/v1/captain');
const authHandler = require('../../../config/middleware/verifyToken');
const multiparty = require('multiparty');




/*===================Test Middelware Function=============================*/
router.post('/testMiddelware', authHandler.verifyToken, (req, res) => {
    let required = ['user_id', 'test_key'];
    captainController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            // captainController.testMiddelware(req.body, (data) => {
            //     res.send(data);
            // });
            res.send(req.body);
        } else {
            res.send(callback);
        }
    })
});


/*===================Check email/mobile/username exist=============================*/

router.get('/checkfieldExist', (req, res) => {
    let required = ['field_value', 'field_type'];
    captainController.getErrorMsg(required, req.query, (callback) => {
        if (callback == '') {
            captainController.checkfieldExist(req.query, (data) => {
                    res.send(data);
                });
        } else {
            res.send(callback);
        }
    })
});
/*===================Function for Signup=============================*/

router.post('/signup', (req, res) => {

    let form = new multiparty.Form();
    form.parse(req, function(err, fields, files) {
        console.log('filed and files', fields, files)

        let required =  ['full_name', 'email','mobile'];
        captainController.getErrorMsg(required, fields, (callback) => {
            if (callback == '') {
                captainController.signup(fields, files, (data) => {
                    res.send(data);
                });
            } else {
                res.send(callback);
            }
        })
    });
});

/*===================Normal login=============================*/
router.post('/signin', (req, res) => {
    console.log(req.body);
    let required = ['mobile'];
    captainController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            captainController.signin(req.body, (data) => {
                res.send(data);
            });
        } else {
            res.send(callback);
        }
    })
});

/*===================reset Password=============================*/
router.post('/resetPassword', (req, res) => {
    let required = ['mobile','password'];
    captainController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            captainController.resetPassword(req.body, (data) => {
                res.send(data);
            });
        } else {
            res.send(callback);
        }
    })
});

/*===================Function for Complete Profile=============================*/

router.post('/completeProfile',authHandler.verifyToken, (req, res) => {

    let form = new multiparty.Form();
    form.parse(req, function(err, fields, files) {
        console.log('filed and files', fields, files)

        let required = ['user_id','username', 'email', 'countrycode', 'mobile','dob'];
        captainController.getErrorMsg(required, fields, (callback) => {
            if (callback == '') {
                captainController.completeProfile(fields, files, (data) => {
                    res.send(data);
                });
            } else {
                res.send(callback);
            }
        })
    });
});

/*===================Function for Edit Profile=============================*/

router.post('/editProfile',authHandler.verifyToken, (req, res) => {

    let form = new multiparty.Form();
    form.parse(req, function(err, fields, files) {
        console.log('filed and files', fields, files)

        let required = ['user_id'];
        captainController.getErrorMsg(required, fields, (callback) => {
            if (callback == '') {
                captainController.editProfile(fields, files, (data) => {
                    res.send(data);
                });
            } else {
                res.send(callback);
            }
        })
    });
});



/*===================User Profile =============================*/

router.get('/getProfile',authHandler.verifyToken, (req, res) => {
    let required = ['user_id', 'date','type'];
    captainController.getErrorMsg(required, req.query, (callback) => {
        if (callback == '') {
            captainController.getProfile(req.query, (data) => {
                    res.send(data);
                });
        } else {
            res.send(callback);
        }
    })
});



/*===================Update Device token=============================*/

router.post('/updateToken',authHandler.verifyToken, (req, res) => {
    let required = ['user_id'];
    captainController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            captainController.updateToken(req.body, (data) => {
                res.send(data);
            });
        } else {
            res.send(callback);
        }
    })
});


/*===================Get Notification=============================*/

router.get('/getNotification',authHandler.verifyToken, (req, res) => {
    let required = ['user_id'];
    captainController.getErrorMsg(required, req.query, (callback) => {
        if (callback == '') {
            captainController.getNotification(req.query, (data) => {
                    res.send(data);
                });
        } else {
            res.send(callback);
        }
    })
});


/*===================Log out =============================*/

router.post('/signOff',authHandler.verifyToken, (req, res) => {
    let required = ['user_id'];
    captainController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            captainController.signOff(req.body, (data) => {
                res.send(data);
            });
        } else {
            res.send(callback);
        }
    })
});


/*===================Get Amenities=============================*/

router.get('/getAmenities', (req,res) => {
    captainController.getAmenities(req.query,(data) => {
            res.send(data);
        });
});




module.exports = router;
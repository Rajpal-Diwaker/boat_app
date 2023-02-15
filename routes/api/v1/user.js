const express = require('express');
const router = express.Router();
let userController = require('../../../controllers/api/v1/user');
const authHandler = require('../../../config/middleware/verifyToken');
const multiparty = require('multiparty');




/*===================Test Middelware Function=============================*/
router.post('/testMiddelware', authHandler.verifyToken, (req, res) => {
    let required = ['user_id', 'test_key'];
    userController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            // userController.testMiddelware(req.body, (data) => {
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
    userController.getErrorMsg(required, req.query, (callback) => {
        if (callback == '') {
            userController.checkfieldExist(req.query, (data) => {
                    res.send(data);
                });
        } else {
            res.send(callback);
        }
    })
});
/*===================Function for Signup=============================*/


router.post('/signup', (req, res) => {
    let required = ['full_name', 'email','mobile'];
    userController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            userController.signup(req.body, (data) => {
                res.send(data);
            });
        } else {
            res.send(callback);
        }
    })
});

/*===================Normal login=============================*/
router.post('/signin', (req, res) => {
    console.log(req.body);
    let required = ['mobile'];
    userController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            userController.signin(req.body, (data) => {
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
    userController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            userController.resetPassword(req.body, (data) => {
                res.send(data);
            });
        } else {
            res.send(callback);
        }
    })
});

/*===================Social Login/Signup=============================*/
router.post('/socialSignin', (req, res) => {
    let required = ['social_type','social_id'];
    userController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            userController.socialSignin(req.body, (data) => {
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
        userController.getErrorMsg(required, fields, (callback) => {
            if (callback == '') {
                userController.completeProfile(fields, files, (data) => {
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
        userController.getErrorMsg(required, fields, (callback) => {
            if (callback == '') {
                userController.editProfile(fields, files, (data) => {
                    res.send(data);
                });
            } else {
                res.send(callback);
            }
        })
    });
});

/*===================Get Question =============================*/

router.get('/getQuestion',authHandler.verifyToken, (req, res) => {
    let required = ['user_id', 'date'];
    userController.getErrorMsg(required, req.query, (callback) => {
        if (callback == '') {
            userController.getQuestion(req.query, (data) => {
                    res.send(data);
                });
        } else {
            res.send(callback);
        }
    })
});

/*===================Function for Post Answer=============================*/

router.post('/postAnswer',authHandler.verifyToken, (req, res) => {

    let form = new multiparty.Form();
    form.parse(req, function(err, fields, files) {

        let required = ['user_id','quesh_id','respo','created_on'];
        userController.getErrorMsg(required, fields, (callback) => {
            if (callback == '') {
                userController.postAnswer(fields, files, (data) => {
                    res.send(data);
                });
            } else {
                res.send(callback);
            }
        })
    });
});

/*===================Function for Edit Answer=============================*/

router.post('/editAnswer',authHandler.verifyToken, (req, res) => {

    let form = new multiparty.Form();
    form.parse(req, function(err, fields, files) {

        let required = ['user_id','respo_id','respo'];
        userController.getErrorMsg(required, fields, (callback) => {
            if (callback == '') {
                userController.editAnswer(fields, files, (data) => {
                    res.send(data);
                });
            } else {
                res.send(callback);
            }
        })
    });
});


/*===================Get Smiley=============================*/

router.get('/getSmiley',authHandler.verifyToken, (req,res) => {
    userController.getSmiley(req.query,(data) => {
            res.send(data);
        });
});

/*===================Post Today's mood=============================*/

router.post('/postFeeling',authHandler.verifyToken, (req, res) => {
    let required = ['user_id','quesh_id'];
    userController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            userController.postFeeling(req.body, (data) => {
                res.send(data);
            });
        } else {
            res.send(callback);
        }
    })
});

/*===================Post How did you like the question=============================*/

router.post('/postabtQues',authHandler.verifyToken, (req, res) => {
    let required = ['user_id','quesh_id','quesh_rating','comment'];
    userController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            userController.postabtQues(req.body, (data) => {
                res.send(data);
            });
        } else {
            res.send(callback);
        }
    })
});


/*===================User Profile =============================*/

router.get('/getProfile',authHandler.verifyToken, (req, res) => {
    let required = ['user_id', 'date','type'];
    userController.getErrorMsg(required, req.query, (callback) => {
        if (callback == '') {
            userController.getProfile(req.query, (data) => {
                    res.send(data);
                });
        } else {
            res.send(callback);
        }
    })
});

/*===================Add to collection=============================*/

router.post('/addCollection',authHandler.verifyToken, (req, res) => {
    let required = ['user_id','quesh_id','respo_id'];
    userController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            userController.addCollection(req.body, (data) => {
                res.send(data);
            });
        } else {
            res.send(callback);
        }
    })
});

/*===================Subscribe/Unsubscribe=============================*/

router.post('/subscribeAction',authHandler.verifyToken, (req, res) => {
    let required = ['subscribe_by','subscribe_to','type'];
    userController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            userController.subscribeAction(req.body, (data) => {
                res.send(data);
            });
        } else {
            res.send(callback);
        }
    })
});


/*===================Accept/Reject Subscribe=============================*/

router.post('/acceptRequest',authHandler.verifyToken, (req, res) => {
    let required = ['login_id','action_id','type'];
    userController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            userController.acceptRequest(req.body, (data) => {
                res.send(data);
            });
        } else {
            res.send(callback);
        }
    })
});

/*===================Get Subscriber =============================*/

router.get('/getSubscribelist',authHandler.verifyToken, (req, res) => {
    let required = ['user_id', 'type'];
    userController.getErrorMsg(required, req.query, (callback) => {
        if (callback == '') {
            userController.getSubscribelist(req.query, (data) => {
                    res.send(data);
                });
        } else {
            res.send(callback);
        }
    })
});


/*===================Report Respo=============================*/

router.post('/reportRespo',authHandler.verifyToken, (req, res) => {
    let required = ['user_id','quesh_id','respo_id'];
    userController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            userController.reportRespo(req.body, (data) => {
                res.send(data);
            });
        } else {
            res.send(callback);
        }
    })
});


/*===================User Profile =============================*/

router.get('/getCollection',authHandler.verifyToken, (req, res) => {
    let required = ['user_id'];
    userController.getErrorMsg(required, req.query, (callback) => {
        if (callback == '') {
            userController.getCollection(req.query, (data) => {
                    res.send(data);
                });
        } else {
            res.send(callback);
        }
    })
});


/*===================Search =============================*/

router.get('/searchResult',authHandler.verifyToken, (req, res) => {
    let required = ['user_id','type'];
    userController.getErrorMsg(required, req.query, (callback) => {
        if (callback == '') {
            userController.searchResult(req.query, (data) => {
                    res.send(data);
                });
        } else {
            res.send(callback);
        }
    })
});

/*===================Search =============================*/

router.get('/tagResult',authHandler.verifyToken, (req, res) => {
    let required = ['user_id','keyword'];
    userController.getErrorMsg(required, req.query, (callback) => {
        if (callback == '') {
            userController.tagResult(req.query, (data) => {
                    res.send(data);
                });
        } else {
            res.send(callback);
        }
    })
});

/*===================Post question=============================*/

router.post('/submitQuesh',authHandler.verifyToken, (req, res) => {
    let required = ['user_id','quesh','why'];
    userController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            userController.submitQuesh(req.body, (data) => {
                res.send(data);
            });
        } else {
            res.send(callback);
        }
    })
});


/*===================Get Community Feed =============================*/

router.get('/getCommunityfeed',authHandler.verifyToken, (req, res) => {
    let required = ['user_id', 'date'];
    userController.getErrorMsg(required, req.query, (callback) => {
        if (callback == '') {
            userController.getCommunityfeed(req.query, (data) => {
                    res.send(data);
                });
        } else {
            res.send(callback);
        }
    })
});


/*===================Get Mood by month Feed =============================*/

router.get('/getMoodbymonth',authHandler.verifyToken, (req, res) => {
    let required = ['user_id', 'date'];
    userController.getErrorMsg(required, req.query, (callback) => {
        if (callback == '') {
            userController.getMoodbymonth(req.query, (data) => {
                    res.send(data);
                });
        } else {
            res.send(callback);
        }
    })
});


/*===================Contact Us=============================*/

router.post('/contactUs',authHandler.verifyToken, (req, res) => {

    let form = new multiparty.Form();
    form.parse(req, function(err, fields, files) {

        let required = ['user_id','description'];
        userController.getErrorMsg(required, fields, (callback) => {
            if (callback == '') {
                userController.contactUs(fields, files, (data) => {
                    res.send(data);
                });
            } else {
                res.send(callback);
            }
        })
    });
});


/*=================== Test Function for image upload =============================*/
router.post('/uploadBuild', (req, res) => {
    let form = new multiparty.Form();
    form.parse(req, function(err, fields, files) {
       
        userController.uploadBuild(req.body, files, (data) => {
            res.send(data);
        });
           
    });
});

/*===================Delete Act=============================*/

router.post('/deleteAccount', (req, res) => {
    let required = ['user_id'];
    userController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            userController.deleteAccount(req.body, (data) => {
                res.send(data);
            });
        } else {
            res.send(callback);
        }
    })
});


/*===================Check Question Answered =============================*/

router.get('/checkifAnswered', (req, res) => {
    let required = ['user_id', 'date'];
    userController.getErrorMsg(required, req.query, (callback) => {
        if (callback == '') {
            userController.checkifAnswered(req.query, (data) => {
                    res.send(data);
                });
        } else {
            res.send(callback);
        }
    })
});

/*===================Get Faq=============================*/

router.get('/getFaq', (req,res) => {
    userController.getFaq(req.query,(data) => {
            res.send(data);
        });
});

/*===================Get Pages =============================*/

router.get('/getPages', (req, res) => {
    let required = ['identifier'];
    userController.getErrorMsg(required, req.query, (callback) => {
        if (callback == '') {
            userController.getPages(req.query, (data) => {
                    res.send(data);
                });
        } else {
            res.send(callback);
        }
    })
});

/*===================Get Question Detail =============================*/

router.get('/getQuesdetail',authHandler.verifyToken, (req, res) => {
    let required = ['user_id', 'date'];
    userController.getErrorMsg(required, req.query, (callback) => {
        if (callback == '') {
            userController.getQuesdetail(req.query, (data) => {
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
    userController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            userController.updateToken(req.body, (data) => {
                res.send(data);
            });
        } else {
            res.send(callback);
        }
    })
});

/*===================Notification on off=============================*/

router.post('/setNotification',authHandler.verifyToken, (req, res) => {
    let required = ['user_id'];
    userController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            userController.setNotification(req.body, (data) => {
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
    userController.getErrorMsg(required, req.query, (callback) => {
        if (callback == '') {
            userController.getNotification(req.query, (data) => {
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
    userController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            userController.signOff(req.body, (data) => {
                res.send(data);
            });
        } else {
            res.send(callback);
        }
    })
});


/*=================== Test Function for image upload On S3 =============================*/
router.post('/uploadS3', authHandler.verifyToken, (req, res) => {
    let form = new multiparty.Form();
    form.parse(req, function(err, fields, files) {
        let required = ['test_key'];
        userController.getErrorMsg(required, fields, (callback) => {
            if (callback == '') {
                userController.uploadS3(req.body, files, (data) => {
                    res.send(data);
                });

            } else {
                res.send(callback);
            }
        })
    });
});

/*===================Test Function for send mail=============================*/
router.post('/sendMail', (req, res) => {
    let required = ['from', 'to', 'subject', 'text'];
    userController.getErrorMsg(required, req.body, (callback) => {
        if (callback == '') {
            userController.sendMail(req.body, (data) => {
                res.send(data);
            });
        } else {
            res.send(callback);
        }
    })
});



module.exports = router;
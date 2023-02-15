const jwt = require('jsonwebtoken');
//const config = require('../config/config.dev')
const auth = {
    verifyToken: (req, res, next) => {
        // console.log("req.headers.token-=========>>>>", req.headers.accesstoken , req.body)
        if (!req.headers.accesstoken || req.headers.accesstoken == "") {
            // console.log("token not verified" ,req.headers.accessToken)
            res.send({ "statusCode": 401, "statusMessage": "provide access token " })
            return
        }
        jwt.verify(req.headers.accesstoken, 'BOATAPP', (err, decoded) => {

            if (err) {
                res.send({ "statusCode": 501, "statusMessage": "access token related error=", "error": err })
                return
            } else {
                req.body.user_id = decoded.user_id;
                // console.log("fdhfd")
                next();
            }
        })
    }
};



module.exports = auth;
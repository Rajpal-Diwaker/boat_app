/*
Development Start Date : 05-Aug-2021
Developer : Alok Jha
*/

require('dotenv').config();
const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const path = require('path');
const app = express();
const server = require('http').Server(app);
var morgan = require('morgan');
const user_v1 = require('./routes/api/v1/user');
const captain_v1 = require('./routes/api/v1/captain');

require('./config/constants');
require('./config/database').con; // Database connection

app.use(cors());
app.use(bodyParser.json({ limit: '100mb' }));
app.use(bodyParser.urlencoded({ extended: true }));

app.use(function (req, res, next) {
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');
    res.setHeader('Access-Control-Allow-Credentials', true);
    next()
});



app.set('view engine', 'ejs');
app.use(morgan('dev'));
app.use('/api/v1/user', user_v1);
app.use('/api/v1/captain', captain_v1);
// app.use('/admin', admin);

app.use("/", express.static(path.join(__dirname, '/dist')));
app.get('/*', (req, res) => {
    console.log(`${__dirname}/dist/index.html`);
    res.sendFile(`${__dirname}/dist/index.html`);
})

//setup port for server
server.listen(process.env.NODE_SERVER_PORT, function () {
    console.log("Server now connected on port " + process.env.NODE_SERVER_PORT + ".");
});

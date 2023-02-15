const mysql = require('mysql');
const QueryBuilder = require('node-querybuilder');

var con = mysql.createConnection({
    host: process.env.MYSQL_HOST,
    user: process.env.MYSQL_USER,
    password: process.env.MYSQL_PASSWORD,
    database: process.env.MYSQL_DATABASE,
    charset: 'utf8mb4',
    dbcollat: 'utf8mb4_unicode_ci'

});



con.connect((err) => {
    if (err) {
        throw err;
    }
    console.log("Database connect successfully.");
});


//Query Builder Connection
const settings = {
    host: process.env.MYSQL_HOST,
    user: process.env.MYSQL_USER,
    password: process.env.MYSQL_PASSWORD,
    database: process.env.MYSQL_DATABASE,
    charset: 'utf8mb4',
    dbcollat: 'utf8mb4_unicode_ci'
};
const db = new QueryBuilder(settings, 'mysql', 'single');

// Return Connection status


module.exports = {
    db,
    con
}
var express = require('./config/express');
var config  = require("./config/config");
var app 	= express();
app.listen(config.port,function(){
	console.log('app running at http://127.0.0.1:',config.port);
});
module.exports = app;
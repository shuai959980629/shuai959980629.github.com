/**
 * 初始化：express
 */
var path       = require('path');
var express    = require('express');
var bodyParser = require('body-parser');
var news       = require('../app/routes/news.server.routes');
module.exports =  function(){
	console.log("init express");
	var app = express();
	app.use(bodyParser.urlencoded({ extended: false }));
	app.use(bodyParser.json());
	app.set('views',__dirname+'/../app/views/templates');
	app.set("view engine",'jade');
	app.use('/asset',express.static(path.join(__dirname,'../public')));
	app.use('/', news);
	app.use(function(req,res,next){
		res.status(404);
		try{
			return res.json('Not Found');
		}catch(e){
			console.error("404 set header after sent");
		}
	});
	app.use(function(err,req,res,next){
		if(!err){return next();}
		res.status(500);
		try{
			return res.json(err.message || "server error");
		}catch(e){
			console.log("500 set header after sent");
		}
	});
	return app;
};
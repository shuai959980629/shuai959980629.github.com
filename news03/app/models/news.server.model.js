var db   = require('./db.model');
var news = module.exports;

news.getNewslist = function(sql,args,next,callback){
	db.query(sql,args,next,function(err,res){
		if(err) next();
		callback.apply(null,[err,res]);
	});
};

news.add = function(sql,args,next,callback){
	db.insert(sql,args,next,function(err,res){
		if(err) next();
		callback.apply(null,[err,res]);
	});
};


news.update = function(sql,args,next,callback){
	db.update(sql,args,next,function(err,res){
		if(err) next();
		callback.apply(null,[err,res]);
	});
};


news.delete = function(sql,args,next,callback){
	db.delete(sql,args,next,function(err,res){
		if(err) next();
		callback.apply(null,[err,res]);
	});
};
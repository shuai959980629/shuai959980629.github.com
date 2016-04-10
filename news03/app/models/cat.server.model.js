var db   = require('./db.model');
var cat = module.exports;

cat.getCatlist = function(sql,args,next,callback){
	db.query(sql,args,next,function(err,res){
		if(err) next();
		callback.apply(null,[err,res]);
	});
};

cat.add = function(sql,args,next,callback){
	db.insert(sql,args,next,function(err,res){
		if(err) next();
		callback.apply(null,[err,res]);
	});
};

cat.update = function(sql,args,next,callback){
	db.update(sql,args,next,function(err,res){
		if(err) next();
		callback.apply(null,[err,res]);
	});
};

cat.delete = function(sql,args,next,callback){
	db.delete(sql,args,next,function(err,res){
		if(err) next();
		callback.apply(null,[err,res]);
	});
};
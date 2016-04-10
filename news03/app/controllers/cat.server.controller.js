var cat 	 = require('../models/cat.server.model');
module.exports = {
    list:function(req, res, next){
    	var sql  = 'select * from category'; 
		var args = [];
    	cat.getCatlist(sql, args,next,function(err,result){
    		if(err)  next(err);
    		res.json(result);
    	});
    },
    add:function(req, res, next){
        var param = req.body;
        if(param.name == null) {
           res.json(null);
        }
    	var sql  = 'INSERT INTO category(id, name, created) VALUES(0,?,?)'; 
		var args = [param.name, parseInt(new Date().getTime()/1000)];
    	cat.add(sql, args,next,function(err,result){
    		if(err)  next(err);
    		res.json(result);
    	});
    },
    delete:function(req, res, next){
    	var param = req.body;
        if(param.id == null && param.type=='category') {
           res.json(null);
        }
        var news = require('../models/news.server.model');
        var sql  = 'delete from newslist where cat_id=?';
        news.delete(sql,parseInt(param.id),next,function(err,result){
    		if(err)  next(err);
    		var sql = 'delete from category where id=?'; 
	    	cat.delete(sql,parseInt(param.id),next,function(err,result){
	    		if(err)  next(err);
	    		res.json(result);
	    	});
    	});
    }
};
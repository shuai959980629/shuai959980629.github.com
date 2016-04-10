var news 	 = require('../models/news.server.model');
var config   = require("../../config/config");
var $util    = require('../../lib/util/util');
module.exports = {
    list:function(req, res, next){
    	var where  =  '';
    	var limit  =  '';
        var param  = req.query  || req.params;
        var page   = param.page || 1;
        if(param.cat_id!=0){
        	where = "where cat_id = "+param.cat_id;
        }
        var offset = page*config.size;
    	var sql  = 'select * from newslist '+where+' order by id desc limit '+offset+','+config.size; 
		var args = [];
    	news.getNewslist(sql, args,next,function(err,result){
    		if(err)  next(err);
            for(var index in result){
                    result[index].created = $util.jsDateDiff(result[index].created);
            }
    		res.json(result);
    	});
    },
    add:function(req, res, next){
        var param = req.body;
        if(param.cat_id == null || param.title==''||param.introduction=='') {
           res.json(null);
        }
        var sql  = 'INSERT INTO newslist(id,cat_id,title,introduction,created) VALUES(0,?,?,?,?)'; 
        var args = [parseInt(param.cat_id),param.title,param.introduction,parseInt(new Date().getTime()/1000)];
        news.add(sql, args,next,function(err,result){
            if(err)  next(err);
            res.json(result);
        });
    },
    update:function(req, res, next){
        var param = req.body;
        if(param.id == null || param.cat_id == null || param.title==''||param.introduction=='') {
           res.json(null);
        }
        var sql  = 'update newslist set cat_id=?, title=?,introduction=? where id=?'; 
        var args = [parseInt(param.cat_id),param.title,param.introduction,parseInt(param.id)];
        news.update(sql, args,next,function(err,result){
            if(err)  next(err);
            res.json(result);
        });
    },
    delete:function(req, res, next){
        var param = req.body;
        if(param.id == null && param.type=='newslist') {
           res.json(null);
        }
        var sql  = 'delete from newslist where id=?';
        news.delete(sql,parseInt(param.id),next,function(err,result){
            if(err)  next(err);
            res.json(result);
        });
    },
    getById:function(req, res, next){
        var param  = req.query || req.params;
        if(param.id == null) {
           res.json(null);
        }
        var sql  = 'select * from newslist where id=?'; 
        news.getNewslist(sql,parseInt(param.id),next,function(err,result){
            if(err)  next(err);
            res.json(result[0]);
        });
    },

};
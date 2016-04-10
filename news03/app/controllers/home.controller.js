var cat 	 = require('../models/cat.server.model');
var news 	 = require('../models/news.server.model');
var config   = require("../../config/config");
var $util    = require('../../lib/util/util');
module.exports = {
    home:function(req, res, next){
        var sql  = 'select * from category'; 
		var args = [];
		var data = {};
    	cat.getCatlist(sql, args,next,function(err,Category){
    		if(err)  next(err);
    		for(var index in Category){
				Category[index].created = $util.jsDateDiff(Category[index].created);
			}
    		data.category = Category;
    		var sql  = 'select * from newslist order by id desc limit 0,'+config.size; 
	    	news.getNewslist(sql, args,next,function(err,Newslist){
	    		if(err)  next(err);
		    	for(var index in Newslist){
					Newslist[index].created = $util.jsDateDiff(Newslist[index].created);
				}
	    		data.newslist = Newslist;
	    		res.render('index',data);
	    	});
    	});
    }
};
var express = require('express');
var orm 	= require('orm');
var path    = require('path');
var bodyParser = require('body-parser');
var app     = express();
app.use(bodyParser.urlencoded({ extended: false }));
app.use(bodyParser.json());
var port    = process.env.PORT || 3000;
app.set('views','./views/templates');
app.set("view engine",'jade');
app.use('/asset',express.static(path.join(__dirname,'public')));
app.listen(port);
console.log("news server startted on port:"+port);
//新闻首页
app.get("/",function(req,res){
	var data ={};
	var db = orm.connect('mysql://root:@localhost/news');
	db.on('connect', function(err) {
		if (err) return console.error('Connection error: ' + err);
		//新闻列表
		var Newslist = db.define("newslist",{
			id		: { type: 'serial', key: true },
			cat_id	: Number,
			title	: String,
			introduction:String,
			created :Number
		});
		Newslist.find({},['id','Z'],{limit:4},{offset:0},function(err,Newslist){
			if(err) throw err;
			for(var index in Newslist){
				Newslist[index].created = jsDateDiff(Newslist[index].created);
			}
			data.newslist = Newslist;
		});
		//新闻分类
		var Category = db.define('category',{
			id 	: { type: 'serial', key: true },
			name:String,
			created :Number
		});
		Category.find({},function(err,Category){
			if(err) throw err;
			data.category = Category;
			res.render("index",data);
		});
	});
});

//获取新闻列表。分页
app.get('/newslist',function(req,res){
	//新闻列表
	var perPage = 4;
	var offset = req.query.page*perPage;
	var db = orm.connect('mysql://root:@localhost/news');
	var Newslist = db.define("newslist",{
		id		: { type: 'serial', key: true },
		cat_id	: Number,
		title	: String,
		introduction:String,
		created :Number
	});
	var where = {};
	if(req.query.cat_id!=0){
		where.cat_id = req.query.cat_id;
	}
	Newslist.find(where,['id','Z']).limit(perPage).offset(offset).run(function (err, Newslist) {
		if(err) throw err;
		for(var index in Newslist){
			Newslist[index].created = jsDateDiff(Newslist[index].created);
		}
		res.json(Newslist);
	});
});


/**
 * @后台
 * */
app.get('/admin',function(req,res){
	var data ={};
	var db = orm.connect('mysql://root:@localhost/news');
	db.on('connect', function(err) {
		if (err) return console.error('Connection error: ' + err);
		//新闻列表
		var Newslist = db.define("newslist",{
			id		: { type: 'serial', key: true },
			cat_id	: Number,
			title	: String,
			introduction:String,
			created :Number
		});
		Newslist.find({},['id','Z'],function(err,Newslist){
			if(err) throw err;
			for(var index in Newslist){
				Newslist[index].created = jsDateDiff(Newslist[index].created);
			}
			data.newslist = Newslist;
		});
		//新闻分类
		var Category = db.define('category',{
			id 	: { type: 'serial', key: true },
			name:String,
			created :Number
		});
		Category.find({},['id','Z'],function(err,Category){
			if(err) throw err;
			for(var index in Category){
				Category[index].created = jsDateDiff(Category[index].created);
			}
			data.category = Category;
			res.render("admin",data);
		});
	});
});

//添加新闻分类
app.post('/category/add', function (req, res) {
	//新闻分类
	var db = orm.connect('mysql://root:@localhost/news');
	var Category = db.define('category',{
		id 	: { type: 'serial', key: true },
		name:String,
		created :Number
	});
	Category.create({name: req.body.name,created:Date.now()}, function(err) {
		if (err) throw err;
		Category.find({ name: req.body.name }, function (err, category) {
			if(category.length){
				res.json(true);
			}else{
				res.json(false);
			}
		});
	});
});


/*删除*/
app.delete('/del', function (req, res) {
	var db = orm.connect('mysql://root:@localhost/news');
	if(req.body.type=='category'){
		var Newslist = db.define("newslist",{
			id		: { type: 'serial', key: true },
			cat_id	: Number,
			title	: String,
			introduction:String,
			created :Number
		});
		Newslist.find({cat_id: req.body.id}).remove(function (err) {
			var Category = db.define('category',{
				id 	: { type: 'serial', key: true },
				name:String,
				created :Number
			});
			Category.find({id:req.body.id}, function (err, Category) {
				if(err) throw err;
				Category[0].remove(function (err) {
					if(err) throw err;
					res.json(true);
				});
			});
		});
	}else if(req.body.type=='newslist'){
		var Newslist = db.define("newslist",{
			id		: { type: 'serial', key: true },
			cat_id	: Number,
			title	: String,
			introduction:String,
			created :Number
		});
		Newslist.find({id:req.body.id},function(err,Newslist){
			if(err) throw err;
			Newslist[0].remove(function (err) {
				if(err) throw err;
				res.json(true);
			});
		});
	}
});

/*新增新闻*/
app.post('/news/add', function (req, res) {
	//新闻列表 {"cat_id":cat_id,"title":title,"introduction":introduction,'id':id}
	var db = orm.connect('mysql://root:@localhost/news');
	var Newslist = db.define("newslist",{
		id		: { type: 'serial', key: true },
		cat_id	: Number,
		title	: String,
		introduction:String,
		created :Number
	});
	if(req.body.id){
		Newslist.find({id:req.body.id},function(err,Newslist){
			if(err) throw err;
			Newslist[0].save({cat_id: req.body.cat_id,title:req.body.title,introduction:req.body.introduction,created:Date.now()},function (err) {
				if(err) throw err;
				res.json(true);
			});
		});
	}else{
		Newslist.create({cat_id: req.body.cat_id,title:req.body.title,introduction:req.body.introduction,created:Date.now()}, function(err) {
			if (err) throw err;
			Newslist.find({title: req.body.title}, function (err, category) {
				if(category.length){
					res.json(true);
				}else{
					res.json(false);
				}
			});
		});
	}
});


app.get('/news',function(req,res){
	var data ={};
	var db = orm.connect('mysql://root:@localhost/news');
	db.on('connect', function(err) {
		if (err) return console.error('Connection error: ' + err);
		//新闻列表
		var Newslist = db.define("newslist",{
			id		: { type: 'serial', key: true },
			cat_id	: Number,
			title	: String,
			introduction:String,
			created :Number
		});
		Newslist.find({id:req.query.id},function(err,Newslist){
			if(err) throw err;
			res.json(Newslist[0]);
		});
	});
});







function jsDateDiff(publishTime){
	var d_minutes,d_hours,d_days;
	var timeNow = parseInt(new Date().getTime()/1000);
	var d;
	d = timeNow - publishTime;
	d_days = parseInt(d/86400);
	d_hours = parseInt(d/3600);
	d_minutes = parseInt(d/60);
	if(d_days>0 && d_days<4){
		return d_days+"天前";
	}else if(d_days<=0 && d_hours>0){
		return d_hours+"小时前";
	}else if(d_hours<=0 && d_minutes>0){
		return d_minutes+"分钟前";
	}else if(d_days==0 && d_hours ==0 && d_minutes==0 ) {
		return '刚刚';
	}else{
		var s = new Date(publishTime*1000);
		return (s.getMonth()+1)+"月"+s.getDate()+"日";
	}
}


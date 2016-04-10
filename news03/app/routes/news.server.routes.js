var express = require('express');
var router  = express.Router();
var news    = require('../controllers/news.server.controller');
var cat     = require('../controllers/cat.server.controller');
var home    = require('../controllers/home.controller');
var admin   = require('../controllers/admin.controller');
/**
 * [百度新闻首页]
 */
router.get('/', function(req, res, next) {
  home.home(req,res,next);
});

/**
 * [后台]
 */
router.get('/admin', function(req, res, next) {
  admin.home(req,res,next);
});


/**
 * [新闻]
 */
router.post('/news/add', function(req, res, next) {
    news.add(req, res, next);
});

router.get('/news/list', function(req, res, next) {
    news.list(req, res, next);
});

router.get('/news', function(req, res, next) {
    news.getById(req, res, next);
});

router.delete('/news/delete', function(req, res, next) {
    news.delete(req, res, next);
});

router.post('/news/update', function(req, res, next) {
    news.update(req, res, next);
});

/**
 * [新闻分类]
 */
router.post('/cat/add', function(req, res, next) {
    cat.add(req, res, next);
});

router.get('/cat/list', function(req, res, next) {
    cat.list(req, res, next);
});

router.get('/cat/list/:id', function(req, res, next) {
    cat.listById(req, res, next);
});

router.delete('/cat/delete', function(req, res, next) {
    cat.delete(req, res, next);
});

router.post('/cat/update', function(req, res, next) {
    cat.update(req, res, next);
});

module.exports = router;
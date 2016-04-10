/**
 *初始化mysql,创建连接池
 */
var mysql    = require('mysql');
var $config  = require("./config");
var $util    = require('../lib/util/util');
// 使用连接池，提升性能
var pool  	   = mysql.createPool($util.extend({}, $config.mysql));
module.exports = pool;
/*
Navicat MySQL Data Transfer

Source Server         : 本地环境
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : news

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2016-04-04 23:48:17
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(50) NOT NULL COMMENT '类名',
  `created` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES ('11', '推荐', '1458990451');
INSERT INTO `category` VALUES ('12', '百家', '1458990499');
INSERT INTO `category` VALUES ('13', '本地', '1458990759');
INSERT INTO `category` VALUES ('14', '社会', '1458990799');
INSERT INTO `category` VALUES ('16', '科技', '1458992817');

-- ----------------------------
-- Table structure for newslist
-- ----------------------------
DROP TABLE IF EXISTS `newslist`;
CREATE TABLE `newslist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '新闻ID',
  `cat_id` int(11) unsigned NOT NULL COMMENT '分类id',
  `title` varchar(100) NOT NULL COMMENT '新闻标题',
  `introduction` text NOT NULL COMMENT '新闻简介',
  `created` int(16) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of newslist
-- ----------------------------
INSERT INTO `newslist` VALUES ('18', '11', '二孩政策正式落地 算算北京养个娃要花多少钱', '一般的孩子考上大学后，四年学费2万元左右；杂费、生活费一个月1000元左右，加上来回家和旅游的...', '1458990834');
INSERT INTO `newslist` VALUES ('19', '11', '一线楼市“调控风”来袭：北京广州或有调整', '一年半时间内，二套房首付比例从7成到4成再到5成，一线城市经历了从加杠杆到降杠杆的过程。', '1458990865');
INSERT INTO `newslist` VALUES ('20', '11', '八大主力牵头涉及九千商户 京大红门南下永清建服装城', '服装城不只面向大红门八大主力市场承接疏解商户，而是面向整个北京地区的服装批发市场，以大红门和动...', '4294967295');
INSERT INTO `newslist` VALUES ('21', '11', '北京五环内住宅库存锐减 房价持续走高', '从市场比例看，北京五环外成交占比已经超过90%', '1458990919');
INSERT INTO `newslist` VALUES ('22', '11', '北京市10万余家单位降低工伤保险费率', '市社保中心表示，重新核定费率需用人单位持社保登记证、营业执照复印件，到所属辖区社保经代办机构办...', '1458991138');
INSERT INTO `newslist` VALUES ('23', '11', '博士股东卷5000万公款潜逃 持3个身份8个手机号', '湖南某置业公司到岳麓公安分局报案称：公司股东吕某私自侵占公司5000多万元公款后下落不明。', '1458991200');
INSERT INTO `newslist` VALUES ('24', '11', '北京5个区首通过国家创新区核查', '国务院教育督导委员会办公室建立中小学校责任督学挂牌督导制度以来，目前全国99%的中小学校实现挂...', '1458991224');
INSERT INTO `newslist` VALUES ('25', '11', '北京去年拆除722座非法墓穴 整治殡葬行业秩序', '执法人员去年纠正殡葬违法行为88起，没收封建迷信用品285公斤，有效整治了殡葬行业秩序。', '1458991290');
INSERT INTO `newslist` VALUES ('26', '12', '北京严查房产中介“十宗罪”：禁止哄抬房价', '本市开展房地产经纪机构专项执法检查，重点检查擅自发布房源信息、违规群租、哄抬房价、阴阳合同、经...', '1458991316');
INSERT INTO `newslist` VALUES ('27', '12', '这家“傲娇”超市：年挣15亿，未来还要收门票！', '电商的发展让很多人对实体店的未来充满疑虑，实体书店、服装店、超市……已经没落了吗。', '1458991373');
INSERT INTO `newslist` VALUES ('28', '12', '联想手机战略为何落后？看看ZUK金秀贤手机就知道了', '联想手机战略为何落后？看看ZUK金秀贤手机就知道了 来自星星的都教授来中国帮着联想卖手机来了。', '1458991409');
INSERT INTO `newslist` VALUES ('29', '12', '互联网电视密集发布，老牌巨头自信来自何处？', '从近日密集的新品发布会能够感觉到，电视行业大有“狼烟起”的势头。', '1458991459');
INSERT INTO `newslist` VALUES ('30', '12', '人工智能写的微型小说参评“日本星新一文学奖”', '星新一是日本现代科幻小说作家，被誉为“日本微型小说鼻祖，其中《名侦探柯南》中的“工藤新一”之名...', '1458991503');
INSERT INTO `newslist` VALUES ('31', '12', 'YOTO Video:动作捕捉公司Sixense STEM“VR攻城”', '上周在2016GDC还展示了一款跨平台多人在线游戏SiegeVR。', '1458991537');
INSERT INTO `newslist` VALUES ('32', '12', '巴菲特如何做到安心长期持股', '我们必须在买入前做好充足的投资分析，来确定这是一只值得我们长期持有的好股票。', '1458991559');
INSERT INTO `newslist` VALUES ('33', '13', '二孩政策正式落地 算算北京养个娃要花多少钱', '二孩政策正式落地 算算北京养个娃要花多少钱.....', '1458991698');
INSERT INTO `newslist` VALUES ('34', '13', '八大主力牵头涉及九千商户 京大红门南下永清建服装城', '服装城不只面向大红门八大主力市场承接疏解商户，而是面向整个北京地区的服装批发市场，以大红门和动...', '1458991718');
INSERT INTO `newslist` VALUES ('35', '14', '逃犯逛KTV 醉了 打架吃亏报警 抓了', '男子韩某醉酒后，认为KTV顾客挑衅便跟对方打架，吃亏后主动报警求助。', '1458991753');
INSERT INTO `newslist` VALUES ('36', '14', '酒店里私自养猴，究竟想干啥？', '两只活猴为什么会在饭店内，民警立即赶到现场进行了调查。', '1458991775');
INSERT INTO `newslist` VALUES ('37', '11', '天津大学成立刑事法律研究中心', '当天的研究会分为“留守儿童、流动儿童面临的社会问题”与“未成年人司法问题”两个主题，与会专家', '1458991796');
INSERT INTO `newslist` VALUES ('38', '13', '联想手机战略为何落后？看看ZUK金秀贤手机就知道了', '联想手机战略为何落后？看看ZUK金秀贤手机就知道了........', '1458992044');
INSERT INTO `newslist` VALUES ('39', '16', '人工智能写的微型小说参评“日本星新一文学奖”', '星新一是日本现代科幻小说作家，被誉为“日本微型小说鼻祖，其中《名侦探柯南》中的“工藤新一”之名...', '1458992862');
INSERT INTO `newslist` VALUES ('40', '13', 'Helio X25加持，联发科能否赢得高端市场？', '联发科一直希望与美国高通在高端智能手机芯片市场一较长短，为此推出了新的“曦力”品牌，并于去年....', '1458993164');
INSERT INTO `newslist` VALUES ('43', '11', 'W3C中国媒体发言人贾雪远..', '新浪财经讯“第十二届中国国际金融论坛”于2015年10月22-23日在上海召开。W3C中国媒体发言人贾雪远出席并演讲。', '1459005338');
INSERT INTO `newslist` VALUES ('44', '11', '天价鱼后又现天价马\"骑马1元\"按秒算 仅仅几分钟就被收了上百元(2)', '苏北网  2016年03月15日 09:00\n两个事件中的当事人都在第一时间报了警,有相应的出警记录和第一时间警察的...投稿删稿联系邮箱', '1459770514');
INSERT INTO `newslist` VALUES ('45', '14', '面膜使用的6个时间点你一定要知道', '经常使用面膜,但是你真的会用面膜吗?6个关于使用面膜的时间点,赶快来看下吧~... 6个关于使用面膜的时间点,赶快来看下吧...', '1459770874');
INSERT INTO `newslist` VALUES ('46', '14', '正确喝水长命百岁 一天9个黄金饮水时间', '挂号十分钟医院基本能达标 候诊时间有待突破', '1459771077');
INSERT INTO `newslist` VALUES ('47', '13', '州小伙熬夜用手机看小说 患上白内障几近失明-周', '北京时间3月31日《华盛顿邮报》消息:阿联的队内头号竞争对手特雷沃-布克,因为...“我估计安德雷今天会上场,他届时应该会打上几分钟', '4294967295');

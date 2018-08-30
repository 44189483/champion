/*
Navicat MySQL Data Transfer

Source Server         : 本机
Source Server Version : 50615
Source Host           : localhost:3306
Source Database       : db_champion

Target Server Type    : MYSQL
Target Server Version : 50615
File Encoding         : 65001

Date: 2018-06-28 17:28:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for activity
-- ----------------------------
DROP TABLE IF EXISTS `activity`;
CREATE TABLE `activity` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT '0' COMMENT '1.志愿服务活动2.中心参与/邀请3.线下志愿培训4.志愿服务会议',
  `serviceNumber` varchar(15) DEFAULT NULL COMMENT '服务编号',
  `title` varchar(100) DEFAULT NULL COMMENT '招募标题',
  `pic` varchar(50) DEFAULT NULL COMMENT '图片',
  `sponsor` varchar(100) DEFAULT NULL COMMENT '主办方',
  `organizer` varchar(100) DEFAULT NULL COMMENT '承办方',
  `activityAddress` varchar(100) DEFAULT NULL COMMENT '活动地点',
  `activityScheme` varchar(1000) DEFAULT NULL COMMENT '活动方案',
  `activityNature` varchar(500) DEFAULT NULL COMMENT '活动性质',
  `sportNeed` varchar(1000) DEFAULT NULL COMMENT '加入运动项目需求',
  `other` varchar(1000) DEFAULT NULL COMMENT '其它条件',
  `integral` int(4) DEFAULT '0' COMMENT '积分奖励',
  `recruitment` varchar(50) DEFAULT NULL COMMENT '招募人数',
  `createTime` datetime DEFAULT NULL,
  `startTime` datetime DEFAULT NULL,
  `endTime` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '是否审核',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='活动(公益招募)';

-- ----------------------------
-- Records of activity
-- ----------------------------
INSERT INTO `activity` VALUES ('15', '1', 'LAX20180605', '优秀运动员志愿服务校园行——走进东城区××小学', 'upload/activity/pic/20180605634.jpg', '国家体育总局人力资源开发中心', '完美和金', '东城区××小学', '', '志愿服务', '', '', '1800', '3', '2018-06-12 09:35:13', '2018-06-05 17:00:00', '2018-06-20 09:00:00', '1');
INSERT INTO `activity` VALUES ('14', '0', 'HMD20180531', '冠军体育课--2017年.崇义', 'upload/activity/pic/2018053144.jpg', '国家体育总局人力资源开发中心', '崇义县人民政府', '崇义县', '', '志愿服务', null, null, '480', '3', '2018-06-05 08:58:35', '2017-09-21 08:00:00', '2017-09-24 17:00:00', '1');

-- ----------------------------
-- Table structure for answer
-- ----------------------------
DROP TABLE IF EXISTS `answer`;
CREATE TABLE `answer` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `qid` int(10) DEFAULT NULL COMMENT '问卷关联',
  `mid` int(10) DEFAULT NULL COMMENT '会员ID',
  `reply` text COMMENT '答案',
  `createTime` datetime DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of answer
-- ----------------------------
INSERT INTO `answer` VALUES ('6', '15', '1', 'a:7:{s:3:\"c_1\";s:1:\"4\";s:3:\"c_2\";s:1:\"1\";s:3:\"c_3\";s:1:\"2\";s:3:\"c_4\";s:1:\"4\";s:3:\"c_5\";s:1:\"3\";s:4:\"user\";s:1:\"1\";s:2:\"id\";s:2:\"15\";}', '2018-05-04 14:35:51');
INSERT INTO `answer` VALUES ('7', '15', '1', 'a:7:{s:3:\"c_1\";s:1:\"3\";s:3:\"c_2\";s:1:\"1\";s:3:\"c_3\";s:1:\"2\";s:3:\"c_4\";s:1:\"3\";s:3:\"c_5\";s:1:\"3\";s:4:\"user\";s:1:\"0\";s:2:\"id\";s:2:\"15\";}', '2018-05-04 14:58:10');
INSERT INTO `answer` VALUES ('8', '15', '1', 'a:7:{s:3:\"c_1\";s:1:\"2\";s:3:\"c_2\";s:1:\"2\";s:3:\"c_3\";s:1:\"4\";s:3:\"c_4\";s:1:\"2\";s:3:\"c_5\";s:1:\"3\";s:4:\"user\";s:1:\"0\";s:2:\"id\";s:2:\"15\";}', '2018-05-07 13:57:42');
INSERT INTO `answer` VALUES ('9', '15', '96', 'a:7:{s:3:\"c_1\";s:1:\"3\";s:3:\"c_2\";s:1:\"1\";s:3:\"c_3\";s:1:\"2\";s:3:\"c_4\";s:1:\"3\";s:3:\"c_5\";s:1:\"3\";s:4:\"user\";s:1:\"0\";s:2:\"id\";s:2:\"15\";}', '2018-06-11 11:59:07');

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT NULL COMMENT '分类:0.公告 1.媒体资讯2.媒体外链3.积分详情4.问卷5.规则6.关于冠军惠',
  `mid` int(10) DEFAULT '0' COMMENT '发布人',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `attach` varchar(100) DEFAULT NULL COMMENT '附件',
  `content` text COMMENT '内容',
  `isShow` tinyint(1) DEFAULT '0' COMMENT '是否显示',
  `ord` int(4) DEFAULT NULL COMMENT '排序',
  `createTime` datetime DEFAULT NULL COMMENT '发表时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 COMMENT='文章资讯表';

-- ----------------------------
-- Records of article
-- ----------------------------
INSERT INTO `article` VALUES ('1', '3', '0', '获得积分', null, '<p>\r\n	1.参加优秀运动员全民健身<strong>志愿服务活动</strong>（在补贴标准范围内），每天获取120积分。异地抵离时间共计算为一天，不足一天的按一天计算。\r\n</p>\r\n<p>\r\n	2.参加人力中心参与组织举办的或经中心邀请参加的<strong>各类公益活动</strong>，每天获取40积分，异地抵离时间共计算为一天，不足一天的按一天计算。\r\n</p>\r\n<p>\r\n	3.参加人力中心举办的<strong>志愿服务培训</strong> \r\n</p>\r\n<p>\r\n	（1）线下培训每天获取120积分。异地抵离时间共计算为一天，不足一天的按一天计算；\r\n</p>\r\n<p>\r\n	（2）线上培训（本app内）每一个学时（45-60分钟）获取15积分。\r\n</p>\r\n<p>\r\n	4.参与志愿服务线上宣传，转发宣传信息获得12积分/人/次。\r\n</p>\r\n<p>\r\n	5.参与志愿服务工作<strong>会议</strong>，每天获取120积分。异地抵离时间共计算为一天，不足一天的按一天计算。\r\n</p>\r\n<p>\r\n	6.参与线上征求意见、问卷调查等获得15积分/人/次。\r\n</p>\r\n<p>\r\n	7.其他支持志愿服务开展，配合志愿服务工作的行为，将参照积分基数合理赋予分值。\r\n</p>\r\n<p>\r\n	<br />\r\n</p>', '0', null, null);
INSERT INTO `article` VALUES ('43', '1', '93', '人力中心组织优秀运动员到江西崇义开展全民健身志愿服务活动', 'upload/article/a7833bb68370c2b3394e991f21a27e07.jpeg', '&nbsp; &nbsp; 11月11日和12日，人力中心组织11位奥运冠军、世界冠军等优秀运动员走进总局对口支援地区——江西省崇义县开展以“学习贯彻十九大精神，迈向全民健康新时代”为主题的全民健身志愿服务。<br />\r\n&nbsp; &nbsp; 活动期间，1983年第12届亚洲男篮锦标赛冠军、1984年第23届洛杉矶奥运会中国代表团旗手王立彬、2008年北京奥运会赛艇女子四人双桨金牌得主奚爱华和张杨杨、WCBA2011—2012赛季总冠军前首钢女篮队长孙晓雨、2012年伦敦奥运会击剑女子重剑团体冠军骆晓娟、2004年雅典奥运会女子摔跤72公斤级冠军王旭、2011年第24届亚洲女篮锦标赛冠军许诺、跳水世界冠军周吕鑫、2017年伦敦田径世锦赛女子铅球冠军巩立姣、2016年里约奥运会举重女子75公斤以上级冠军孟苏平、2007年世界青年赛艇锦标赛冠军徐蕊等优秀运动员深入社区、群众体育健身广场进行全民健身互动，助力万人徒步大会，带动大家一起感受体育锻炼带来的乐趣和益处；走进学校，开展励志教育，传播中华体育精神和奥林匹克精神。同时，结合全民健身志愿服务实践，组织优秀运动员、十九大代表深入基层宣讲十九大精神。<br />\r\n冠军就在“老表”身边<br />\r\n&nbsp; &nbsp; “他就是跳水冠军周吕鑫啊？以前在电视上看过，今天终于见到真人了！”11月11日下午，江西赣州崇义县奥林匹克运动广场笼式足球场上，观看小学员与冠军共上足球课的家长们兴奋地议论着。<br />\r\n&nbsp; &nbsp; 周吕鑫的颜值、王立彬的高度、孟苏平的微笑，生动诠释了优秀运动员全民健身志愿服务（崇义站）活动的三高：高影响力、高起点、高亲和度。<br />\r\n<br />\r\n<br />\r\n王立彬、孙晓雨、许诺与当地篮球爱好者进行3对3篮球赛<br />\r\n<br />\r\n<br />\r\n孙晓雨作为教练指导当地篮球协会会员比赛<br />\r\n<br />\r\n<br />\r\n奚爱华、骆晓娟与参与足球互动的小朋友合影<br />\r\n<br />\r\n<br />\r\n周吕鑫与参与足球互动的小朋友合影<br />\r\n<br />\r\n<br />\r\n孟苏平与当地体育爱好者分享她对体育精神的认识<br />\r\n<br />\r\n<br />\r\n张杨杨与当地网球爱好者共同挥拍<br />\r\n<br />\r\n<br />\r\n巩立姣、徐蕊与崇义中学的孩子们欢乐合影<br />\r\n&nbsp; &nbsp; 王立彬、许诺、孙晓雨与崇义县篮球协会会员、当地篮球爱好者打了一场3对3篮球赛，而后又分别挑选队员，当起了教练，指导比赛；周吕鑫、奚爱华、骆晓娟与足球小学员们一起绿茵奔跑，开展趣味足球赛；王旭、孟苏平、张杨杨与8至10岁的网球班学员共同挥拍，同上一堂网球课，欢乐互动。巩立姣和徐蕊来到崇义中学给孩子们上了一堂励志教育课，分享运动员艰苦训练、顽强拼搏的故事，鼓励大家要勇于坚持梦想，不断战胜自我。<br />\r\n&nbsp; &nbsp; “我想找冠军姐姐签个名。”<br />\r\n&nbsp; &nbsp; “原来冠军就是要有一股不服输的劲头！”<br />\r\n&nbsp; &nbsp; “我现在是我们县联赛冠军，将来我也要当世界冠军！”<br />\r\n&nbsp; &nbsp; ……<br />\r\n&nbsp; &nbsp; 掌声笑声欢呼声中，冠军所到之处播撒下热爱运动的种子，激发顽强拼搏、勇争第一的冠军的心，弘扬中华体育精神。正如体育总局人力中心志愿服务管理部主任李立东在媒体通气会所言：奥运冠军、世界冠军等优秀运动员有着广泛的社会影响力和号召力，对全民健身志愿服务活动的开展具有引领和示范作用。做好优秀运动员全民健身志愿服务的组织动员，将对全民健身志愿服务活动广泛深入开展产生推动作用。同时，通过组织奥运冠军、世界冠军等优秀运动员开展全民健身志愿服务活动，将有利于教育和引导优秀运动员进一步强化祖国培养、人民哺育的意识，把服务他人、服务社会与实现个人价值有机结合起来，以实际行动回馈社会。<br />\r\n<br />\r\n<br />\r\n周吕鑫、王旭在为线上互动奖品签名<br />\r\n<br />\r\n<br />\r\n优秀运动员们为线上互动获奖者颁奖<br />\r\n<br />\r\n<br />\r\n优秀运动员们为线上互动获奖者颁奖<br />\r\n&nbsp; &nbsp; 冠军来到崇义之前，人力中心委托崇义县有关部门和华奥星空开展“全民健身动起来，迈进健康新时代”和“我和冠军的篮球赛”两项线上征集活动，健身爱好者将自己参加体育健身活动或篮球锻炼的照片，并配文字“全民健身动起来，迈进健康新时代”或“我和冠军的篮球赛”在个人社交平台(微信朋友圈和微博)发出，截图发送到要向组委会指定的网站，便有机会获得由冠军们亲手颁发的冠军签名奖品、与篮球冠军同场竞技。直接参与两个线上互动的人数达300人,关注和转发人员达3.5万人。通过线上互动，调动当地群众参与全民健身的积极性，引导当地群众积极参加体育锻炼，进一步提高活动的互动性，将有限的活动时间扩展出更大的影响效果。<br />\r\n与冠军同行健步开启新征程<br />\r\n&nbsp; &nbsp; 11月12日上午，11位冠军参加2017“群星耀阳明”中国（崇义）阳明山万人养生徒步大会暨优秀运动员全民健身志愿服务（崇义）活动开幕式，助力大型群众体育活动，身体力行引导大家积极开展全民健身。<br />\r\n<br />\r\n<br />\r\n王立彬代表优秀运动员宣读《全民健身志愿服务倡议书》<br />\r\n&nbsp; &nbsp; “今天，我们参加活动的11名冠军，积极响应国家体育总局的号召，作为优秀运动员全民健身志愿者，我们倡议：所有的奥运冠军、世界冠军、优秀运动员们，让我们一起行动起来，走入基层，走到群众身边，继续展现爱国主义精神，继续弘扬中华体育精神和奥林匹克精神，更广泛地传播体育文化。积极参加优秀运动员全民健身志愿服务，用自己的实际行动带动更多的人投身到全民健身活动中来，做全民健身的积极倡导者和引领者。通过为全民健身提供力所能及的服务，在全社会营造积极浓厚的健身氛围，增强大众科学健身意识，为‘健康中国’建设贡献自己的一份力量。”王立彬代表参与此次活动的优秀运动员宣读《全民健身志愿服务倡议书》，而后11位冠军与领导嘉宾，共同领走2017“群星耀阳明”中国（崇义）阳明山万人养生徒步活动。优秀运动员们步履轻盈走在青山绿水间，时而说笑自拍，时而应邀与粉丝们合影留念，忙得不亦乐乎。<br />\r\n<br />\r\n<br />\r\n优秀运动员欢乐领走“万人徒步大会”<br />\r\n&nbsp; &nbsp; 群星耀阳明——与冠军同行，以冠军为榜样，崇义人健步开启新征程。全民健身志愿服务走进崇义，冠军们为美丽崇义、快乐崇义人点赞。<br />\r\n服务基层 冠军分享学习十九大精神心得<br />\r\n&nbsp; &nbsp; 11月12日下午，巩立姣、孟苏平两位十九大代表和优秀运动员们来到江西省赣州市崇义县扬眉镇分享学习十九大精神心得。<br />\r\n<br />\r\n<br />\r\n巩立姣、孟苏平两位十九大代表分享学习十九大精神心得<br />\r\n&nbsp; &nbsp; “作为一名运动员，我的梦想就是在职业生涯里登上奥运会的最高领奖台，希望这个梦想在2020年东京奥运会上可以实现，能够在日本东京的赛场上，奏响中华人民共和国国歌，那将特别有意义！”十九大代表巩立姣以《学以致用，做一名优秀的运动员》为题目宣讲十九大精神，博得在场听众的阵阵掌声。<br />\r\n&nbsp; &nbsp; 巩立姣围绕“发挥模范带头作用，积极参加志愿服务活动；牢记使命刻苦训练，加快推进体育强国建设；振奋民族精神，筹办好北京冬奥会”三方面内容分享心得感受。她说：“十九大报告关于体育的相关论述，是我们党在迈向新时代、开启新征程、续写新篇章的历史关键点上对体育系统发出的动员令、吹响的冲锋号，习近平总书记为全国所有体育工作者指明了方向。体育工作者必须抓住这个千载难逢的发展机遇，通过不懈努力，把希望变成现实。‘打铁还需自身硬’，在祖国发展的时代新征程中，我们体育界的党员们要和群众一起把基础工作打牢，这样才能将体育事业更好地推进。把全民健身活动更广泛地开展好，在加快推进体育强国建设、向全面小康这一伟大目标迈进的过程中，尽自己的一份力。”<br />\r\n&nbsp; &nbsp; “作为十九大代表既感到无上光荣、又深感责任重大，要始终牢记使命，刻苦训练，不畏艰难，再接再厉，不断在赛场争创佳绩，在赛场外做好志愿服务工作，身体力行，弘扬中华体育精神。”十九大代表孟苏平围绕“深受鼓舞、倍感振奋的同时，感到自己身上所担负的责任和使命”进行分享。<br />\r\n<br />\r\n<br />\r\n扬眉镇基层干部谈学习十九大精神的感想<br />\r\n&nbsp; &nbsp; 巩立姣、孟苏平两位十九大代表分享自己亲身参加党的十九大的体会和感受后，扬眉镇基层党员干部也纷纷结合自身工作，谈学习贯彻十九大精神的感想，王立彬、奚爱华、周吕鑫、许诺、孙晓雨、王旭、张杨杨、徐蕊等优秀运动员与扬眉镇基层党员和群众，在分享会上惜时如金地倾心交流，真情互动。<br />\r\n<br />\r\n<br />\r\n优秀运动员在扬眉镇教授科学健身知识后与当地群众合影<br />\r\n&nbsp; &nbsp; 此次“学习贯彻十九大精神，迈向全民健康新时代”优秀运动员全民健身志愿服务系列活动，是十九大胜利召开之后组织的第一次优秀运动员志愿服务活动，是全面学习贯彻十九大精神，落实《国家体育总局对口支援赣州崇义县振兴发展2017年重点工作方案及责任分工》的具体举措，旨在倡导健康文明生活方式，践行“广泛开展全民健身活动，加快推进体育强国建设”，展现了崇义县阳明山良好的生态人文环境，扩大了影响，增强了效益，同时还切实地给当地群众带来福利。在迈向全民健身新时代，推动体育强国建设的共同愿景下，蓄力前行，收获满满。<br />', '0', null, '2018-05-31 15:28:44');
INSERT INTO `article` VALUES ('44', '1', '93', '优秀运动员志愿服务助力“海航迎新爱跑”活动', 'upload/article/e133bb08f34aec55e40932fc82faacc5.jpeg', '&nbsp;为落实体育总局和海南省战略合作，推动全民健身活动广泛深入开展，应海航集团邀请，人力中心作为支持单位，与海航集团共同举办“海航集团2018年迎新环球爱跑海口站（主会场）活动”，并组织亚洲男篮锦标赛冠军、1984年洛杉矶奥运会中国代表团旗手王立彬和2008年北京奥运会柔道女子78公斤级冠军杨秀丽两位优秀运动员参加此次活动。<br />\r\n&nbsp; &nbsp; 活动于2017年12月31日在海口日月广场举办，吸引了2000多名海航集团内部员工与跑步爱好者以跑步这一健康向上的方式迎接2018新年，用5公里公益跑步的独特方式庆祝海口建市90周年。此外，该项活动也融合进了公益元素。与常见的其他竞速类跑步活动不同，“迎新爱跑活动”旨在传递“全民健身”的生活理念、倡导人人参与公益，跑步积累的里程数将按照1：1的比例折算成爱心里程兑换为公益机票，帮助贫困学子春节回家，支持社会公益事业。<br />\r\n&nbsp; &nbsp; 通过组织优秀运动员参与，提高了活动的影响力和传播力，带动了更多人参与到全民健身活动中，推动了公益事业发展。<br />\r\n&nbsp; &nbsp; 2017年，人力中心作为“总局优秀运动员全民健身志愿服务协调小组”具体办事机构，共组织协调106人（队、组）次优秀运动员参加大型体育活动和公益活动，为推进全民健身志愿服务长效化机制建设做出了积极的努力。<br />', '0', null, '2018-05-31 15:29:22');
INSERT INTO `article` VALUES ('15', '4', '0', '体育锻炼问卷调查表', null, 'a:5:{i:1;a:2:{s:1:\"q\";s:15:\"您的年龄是\";s:1:\"c\";a:4:{i:1;s:11:\"16岁以下\";i:2;s:8:\"16-25岁\";i:3;s:8:\"26-35岁\";i:4;s:8:\"36-45岁\";}}i:2;a:2:{s:1:\"q\";s:15:\"您的性别是\";s:1:\"c\";a:2:{i:1;s:3:\"男\";i:2;s:3:\"女\";}}i:3;a:2:{s:1:\"q\";s:36:\"您认为您的身体素质如何？\";s:1:\"c\";a:4:{i:1;s:6:\"很好\";i:2;s:6:\"一般\";i:3;s:6:\"较差\";i:4;s:9:\"不清楚\";}}i:4;a:2:{s:1:\"q\";s:60:\"您平均每周花在体育锻炼的时长为多少小时？\";s:1:\"c\";a:4:{i:1;s:13:\"2小时以下\";i:2;s:9:\"2-4小时\";i:3;s:9:\"4-7小时\";i:4;s:10:\"7-10小时\";}}i:5;a:2:{s:1:\"q\";s:57:\"您平均每周参加体育锻炼的次数为多少次？\";s:1:\"c\";a:4:{i:1;s:10:\"2次以下\";i:2;s:6:\"2-5次\";i:3;s:7:\"6-10次\";i:4;s:11:\"10次以上\";}}}', '1', null, '2018-05-04 14:51:04');
INSERT INTO `article` VALUES ('18', '1', '2', '国家体育总局人力资源中心启动“体育经理人”培训与能力测评项目', null, '“2018首届中国体育人才峰会暨体育经理人论坛”20日在杭州萧山国际博览中心举行，论坛上宣布，由国家体育总局人力资源开发中心指导、维宁体育文化产业（北京）有限公司负责实施的“体育经理人”人才培养与测评项目正式启动。 &nbsp; &nbsp; &nbsp;据悉，“体育经理人”人才培养与测评项目是国家体育总局人力资源开发中心全程指导实施的“新时期体育人才培养改革创新项目”。 &nbsp; &nbsp; &nbsp;据该项目相关负责人介绍，“体育经理人”将打造符合中国国情的体育人才培养与测评体系，为体育产业培养优秀的体育经营管理人才。通过课程培训并测评合格者将被授予国家体育总局人力资源开发中心“体育经理人等级培训与能力测评”证书。 &nbsp; &nbsp; &nbsp;国家体育总局人力资源开发中心职业技能鉴定管理部副主任魏来概括了“体育经理人项目”定位：“借鉴国际体育商业人才培养先进经验与标准，构建符合中国国情的体育经营管理人才培训与测评体系。它包含三个基本含义。一是从体育行业来讲，它是一个国际化职业体系；二是从职务界定来讲，它泛指体育经营管理者；三是从职业能力来讲，它指向职业化商业人才。”', '0', null, '2018-05-04 15:50:17');
INSERT INTO `article` VALUES ('25', '5', '0', '发稿规则', null, '规则', '0', null, null);
INSERT INTO `article` VALUES ('24', '4', '0', '体育运动的调查问卷', null, 'a:2:{i:1;a:2:{s:1:\"q\";s:17:\"你的性别是??\";s:1:\"c\";a:2:{i:1;s:3:\"男\";i:2;s:3:\"女\";}}i:2;a:2:{s:1:\"q\";s:19:\"你经常运动吗?\";s:1:\"c\";a:3:{i:1;s:6:\"经常\";i:2;s:6:\"偶尔\";i:3;s:10:\"不运动 \";}}}', '1', null, '2018-05-08 10:43:05');
INSERT INTO `article` VALUES ('30', '6', '0', '关于冠军惠', null, '一些信息', '0', null, null);
INSERT INTO `article` VALUES ('42', '1', '93', '让体育成为生活方式-优秀运动员全民健身志愿服务活动走进来宾', 'upload/article/507c335609c9a0d38f99844bd55bfdfd.jpeg', '<p>\r\n	<span style=\"font-family:simsun;font-size:16px;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;background-color:#FFFFFF;\">由国家体育总局人力资源开发中心组织的优秀运动员全民健身志愿服务活动日前走进广西壮族自治区来宾市。恰逢来宾举办2018年中国体育庙会暨广西“壮族三月三 民族体育炫”活动。本次活动吸引了奥运冠军杨秀丽、程菲，羽毛球世界冠军王仪涵，象棋国手蒋川，围棋国手唐盈，以及“亚洲飞人”短跑名将劳义和“跳水姐妹花”李婷李娆的参与。</span>\r\n</p>\r\n<p>\r\n	<span style=\"font-family:simsun;font-size:16px;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;background-color:#FFFFFF;\"><span style=\"font-family:simsun;font-size:16px;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;background-color:#FFFFFF;\">“首先能看到大家整套动作的完成度非常高，我觉得你们现在这套啦啦操给我整体感受非常不错，尤其在动作的质量上，但感觉在表现力上还是有所欠缺。”在来宾第六中学，程菲观看了校啦啦操队的比赛动作表演，在对技术表示肯定的同时也说道，“啦啦操和体操项目一样，都是很需要表现力的，只是动作到位还远远不够，面目表情和神态也同等重要，要把自己的绝对自信展现出来。”</span></span>\r\n</p>\r\n<p>\r\n	<span style=\"font-family:simsun;font-size:16px;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;background-color:#FFFFFF;\"><span style=\"font-family:simsun;font-size:16px;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;background-color:#FFFFFF;\"><span class=\"Apple-converted-space\">&nbsp;</span>随后，运动员们来到滨江社区，很多羽毛球爱好者们早早拿着拍子等待和王仪涵切磋球技。在和大家进行了短暂热身运动之后，王仪涵和韦广中等三位选手一起进行了一场混合双打，韦广中说：“我们一共打了11个球，在打球的过程中王仪涵会对我们的动作进行简单的纠正，说怎样挥拍才能更好的接球等。我家其实不是住在滨江社区的，是在电厂那边，提前知道她要过来，也想感受一下冠军的风采，没想到这么幸运被选上进行比赛，非常开心。”在与成年选手对打结束后，王仪涵还和几位小朋友进行了互动，个子不高的张裕涵就是其中一位，在对战过程中还完成了几个扣球，今年十岁的他在赛后笑着说：“我刚刚学羽毛球八个月，特别开心能和冠军姐姐对打，觉得她特别厉害，以后我也要更加努力练习。”</span></span>\r\n</p>\r\n<p>\r\n	<span style=\"font-family:simsun;font-size:16px;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;background-color:#FFFFFF;\"><span style=\"font-family:simsun;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;font-size:16px;background-color:#FFFFFF;\"><span class=\"Apple-converted-space\">&nbsp;</span>羽毛球作为一项普及度非常高的项目，王仪涵也与很多来宾市民和学生进行了球场上的交流，王仪涵表示，她觉得运动现在已经成为一种生活方式和生活态度，“希望大家能感受到体育带来的快乐和魅力。而且我觉得现在社会各界受挫的承受能力其实都不强，因此也希望他们能通过体育去缓解和释放压力，改善自己的状态。”<br />\r\n&nbsp;</span><span style=\"font-family:simsun;font-size:14px;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;background-color:#FFFFFF;\"></span>\r\n	<div style=\"font-family:simsun;font-size:14px;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;text-align:center;background-color:#FFFFFF;\">\r\n		<span style=\"font-size:16px;\"><strong>尝试新项目 感受体育魅力</strong></span>\r\n	</div>\r\n<span style=\"font-family:simsun;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;font-size:16px;background-color:#FFFFFF;\">&nbsp;<br />\r\n&nbsp; &nbsp; 在与来宾的体育爱好者们进行了羽毛球等熟悉项目的交流后，大家还尝试了一些之前没有接触过的项目，比如三人板鞋，看似简单的三人板鞋却需要非常默契的配合，程菲和杨秀丽在现场和刚刚结束比赛的两队选手临时组队，短暂练习之后便进行了一场奥运冠军友谊赛。“三人板鞋要求三个人默契配合，刚刚看他们比赛的时候觉得很轻松，直线行进速度很快，转弯的时候甚至就像一个人一样轻松。但是当我真正踩在板上开始走的时候，就发现原来非常不简单。因为走路的方式和平常不一样，步幅和步频也需要三个人间的相互配合，所以挺佩服健步如飞的三人板鞋运动员，真的是每个项目都有自己独特的特点，真正能练习到比赛水平都非常不容易。”</span></span>\r\n</p>\r\n<p>\r\n	<span style=\"font-family:simsun;font-size:16px;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;background-color:#FFFFFF;\"><span style=\"font-family:simsun;font-size:16px;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;background-color:#FFFFFF;\"><span class=\"Apple-converted-space\">&nbsp;</span>优秀运动员们不仅感受了三人板鞋项目带来的魅力，程菲还向来宾的健身爱好者学习了柔力球。“手腕要翻得快。”“你的手指要上下放，翻过来之后要让手指回到一开始的位置，才能动作灵活。”“对对对，就是这个样子，身体要随着球和球拍一起摆起来，幅度大一点不怕。”在几个阿姨的指导下，程菲和市民一起学习了柔力球的基本动作。“其实平时也经常看到公园里面有玩柔力球的人，非常好奇她们是怎么把球很好地控制在球拍上的，今天学习了一下更佩服他们了。”第一次接触柔力球的程菲坦言，能做到像表演一样“人球合一”的状态确实不容易。随后程菲谈到，这次到广西来宾参加优秀运动员全民健身志愿服务活动感触很多，不仅和大家一起交流了体操上的经验，还体验了很多之前没有接触过的运动，非常开心。</span><br />\r\n</span>\r\n</p>', '0', null, '2018-05-31 15:26:11');
INSERT INTO `article` VALUES ('41', '0', '0', '体育总局青少司关于举办2018年第二期青少年体育俱乐部管理人员培训班的通知', null, '<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;border:0px;list-style:none;font-family:simsun;font-size:14px;white-space:normal;background-color:#F7FBFF;\">\r\n	<span style=\"font-family:Arial;\">各省、自治区、直辖市、新疆生产建设兵团体育局:<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 为全面贯彻落实2017年全国体育局长会议精神，加强我国青少年体育俱乐部（以下简称“俱乐部”）建设和人才队伍发展，进一步提高俱乐部管理人员的知识水平和业务素质，不断推动俱乐部工作深入持久地开展，充分发挥俱乐部在落实青少年体育技能普及和后备人才培养的重要作用，按照体育总局青少年体育司2018年的工作计划，决定举办2018年第二期青少年体育俱乐部管理人员培训班。现就有关事宜通知如下：<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 一、培训时间和地点<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; （一）培训时间：2018年6月11日-15日（11日报到，15日离会）<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; （二）培训地点：宁夏回族自治区银川市宝什利德酒店<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 二、培训内容<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 本次培训以“青少年体育俱乐部市场推广与运营及科学训练”为主题，将邀请国内著名的专家学者及优秀俱乐部代表，以授课和互动交流的方式进行，并且开展俱乐部相关方面的座谈和研讨。<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 授课内容：<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; （一）青少年体育俱乐部经营管理与市场推广<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; （二）人才培养视域下青少年体育俱乐部的功能定位和价值创造<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; （三）青少年体育俱乐部运动员科学化训练<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; （四）青少年体育俱乐部市场营销创新研究<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; （五）以青少年俱乐部为核心打通体育产业链条<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 三、培训对象及要求<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 各省（区、市）俱乐部管理人员，名额分配见附件2。参加培训人员要认真梳理本地区俱乐部管理工作中存在的重点、难点问题，参加培训并展开讨论。<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 四、培训经费<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 参加培训人员旅费自理，培训期间食宿费、培训费由国家体育总局负担。<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 五、培训组织形式<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 主办单位：国家体育总局青少年体育司<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 承办单位：国家体育总局体育科学研究所<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 协办单位：银川市宝什利德酒店<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 六、报名报到<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; （一）报名：请将参会人员报名表（见附件2）加盖省（区、市）体育局公章于2018年5月31日前发送至国家体育总局体育科学研究所李晓旭的电子信箱：439123434@qq.com。<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; （二）报到：请参会人员于6月11日到银川宝什利德酒店（银川金凤区亲水大街与长城路交汇处）报到。<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 七、联系方式<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; （一）国家体育总局青少年体育司<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 联系人：高&nbsp; 扬<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 电话：（010）87182342&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; （二）国家体育总局体育科学研究所<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 联系人：李晓旭&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 电话：（010）87182619<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 手机： 18500959716</span>\r\n</p>\r\n<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;border:0px;list-style:none;font-family:simsun;font-size:14px;white-space:normal;background-color:#F7FBFF;\">\r\n	<span style=\"font-family:Arial;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 附件：1.<a href=\"http://www.sport.gov.cn/n316/n336/c859961/part/502950.doc\" style=\"color:#333333;text-decoration-line:none;\">2018年第二期青少年体育俱乐部管理人员培训班日程安排表</a></span>\r\n</p>\r\n<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;border:0px;list-style:none;font-family:simsun;font-size:14px;white-space:normal;background-color:#F7FBFF;\">\r\n	<span style=\"font-family:Arial;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 　　　 2.<a href=\"http://www.sport.gov.cn/n316/n336/c859961/part/502951.doc\" style=\"color:#333333;text-decoration-line:none;\">2018年第二期青少年体育俱乐部管理人员培训班各省（区、市）名额分配表</a></span>\r\n</p>\r\n<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;border:0px;list-style:none;font-family:simsun;font-size:14px;white-space:normal;background-color:#F7FBFF;\">\r\n	<span style=\"font-family:Arial;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 　　　3.<a href=\"http://www.sport.gov.cn/n316/n336/c859961/part/502952.doc\" style=\"color:#333333;text-decoration-line:none;\">2018年第二期青少年体育俱乐部管理人员培训班报名表</a></span>\r\n</p>\r\n<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;border:0px;list-style:none;font-family:simsun;font-size:14px;white-space:normal;background-color:#F7FBFF;\">\r\n	<span style=\"font-family:Arial;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 　　　4.<a href=\"http://www.sport.gov.cn/n316/n336/c859961/part/502953.doc\" style=\"color:#333333;text-decoration-line:none;\">2018年第二期青少年体育俱乐部管理人员培训班交通路线图</a></span>\r\n</p>', '1', null, '2018-05-28 11:34:00');
INSERT INTO `article` VALUES ('40', '0', '0', '体育总局竞体司关于举办2018年“精英教练员双百培养计划”专业队教练员赴美国培训班的函', null, '<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;border:0px;list-style:none;font-family:simsun;font-size:14px;white-space:normal;background-color:#F7FBFF;\">\r\n	<span style=\"font-family:宋体;\">各有关单位：</span>\r\n</p>\r\n<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;border:0px;list-style:none;font-family:simsun;font-size:14px;white-space:normal;background-color:#F7FBFF;\">\r\n	<span style=\"font-family:宋体;\">　　为加强对体育总局“精英教练员双百培养计划”培养对象（以下简称“双百计划”培养对象）针对性培养，根据总局2018年培训计划，经国家外专局批准，拟举办2018年“双百计划”专业队教练员赴美国培训班，现就有关事宜通知如下：</span><br />\r\n<span style=\"font-family:宋体;\"><b><span style=\"line-height:21px;\">　　一、培训时间、地点</span></b></span><br />\r\n<span style=\"font-family:宋体;\"><span style=\"line-height:21px;\">　　培训时间：2018年9月。</span></span><br />\r\n<span style=\"font-family:宋体;\"><span style=\"line-height:21px;\">　　国内预培训（3天），在总局干部培训中心（北京体育大学内）；美国培训21天。</span></span><br />\r\n　　<span style=\"font-family:宋体;\"><b><span style=\"line-height:21px;\">二、培训对象</span></b></span><br />\r\n<span style=\"font-family:宋体;\"><span style=\"line-height:21px;\">　　体育总局第三批“双百计划”培养对象中专业队教练员，年龄不超过55岁。</span></span><br />\r\n<span style=\"font-family:宋体;\"><b><span style=\"line-height:21px;\">　　三、培训内容</span></b></span><br />\r\n<span style=\"font-family:宋体;\"><span style=\"line-height:21px;\">　　系统更新理论知识，动态掌握美国最新的训练新理念、新方法和新模式，促进我国竞技体育可持续发展。培训方式以课堂讲习、实践操作、体育专业机构及高水平俱乐部现场观摩、教学相结合，培训主要专题如下：<br />\r\n</span></span><span style=\"font-family:宋体;\"><span style=\"line-height:21px;\">　　（一）现代竞技运动训练新理念</span></span><br />\r\n<span style=\"font-family:宋体;\">&nbsp;&nbsp;&nbsp; （二）美国体能训练的新趋势</span><br />\r\n<span style=\"font-family:宋体;\">&nbsp;&nbsp;&nbsp; （三）精英教练员执教能力提升</span><br />\r\n<span style=\"font-family:宋体;\">&nbsp;&nbsp;&nbsp; （四）心理因素分析及恢复指导</span><br />\r\n<span style=\"font-family:宋体;\">&nbsp;&nbsp;&nbsp; （五）精英运动员的综合能力测评及数据分析</span><br />\r\n<span style=\"font-family:宋体;\">&nbsp;&nbsp;&nbsp; （六）运动生理学、运动生物力学前沿进展</span><br />\r\n<span style=\"font-family:宋体;\">&nbsp;&nbsp;&nbsp; （七）精英运动员生活管理与运动队管理</span><br />\r\n<span style=\"font-family:宋体;\">&nbsp;&nbsp;&nbsp; （八）精英教练员素质与创新能力培养</span><br />\r\n　　<span style=\"font-family:宋体;\"><b>四、培训经费<br />\r\n　　</b></span><span style=\"font-family:宋体;\"><span style=\"line-height:21px;\">培训相关经费由体育总局承担。国内往返交通费由学员所在单位负担。</span></span><br />\r\n　　<span style=\"font-family:宋体;\"><b><span style=\"line-height:21px;\">五、有关要求</span></b></span><br />\r\n<span style=\"font-family:宋体;\">　　（一）请各单位从加强对“双百计划”培养对象针对性培养的实际需要，重视参训人员的选拔和推荐工作，并将推荐人员排序，体育总局人事司将根据培训要求和报名情况择优录取。<br />\r\n</span>　　<span style=\"font-family:宋体;\">（二）推荐单位须签订出国培训承诺书。</span><br />\r\n　　<span style=\"font-family:宋体;\"><span style=\"line-height:21px;\">（三）推荐人选须填写美国签证申请表。</span></span><br />\r\n　　<span style=\"font-family:宋体;\"><span style=\"line-height:21px;\">（四）请于6月5日前将报名表、出国培训承诺书及赴美签证申请表（见附件）传真至体育总局干部培训中心。</span></span><br />\r\n　　<span style=\"font-family:宋体;\">体育总局竞体司训练处</span><br />\r\n　　<span style=\"font-family:宋体;\">联系人：牛杰冠</span><br />\r\n　　<span style=\"font-family:宋体;\">电&nbsp; 话：010—87182470</span><br />\r\n　　<span style=\"font-family:宋体;\">体育总局干部培训中心</span><br />\r\n　　<span style=\"font-family:宋体;\">联系人：徐庆雷、李莉</span><br />\r\n　　<span style=\"font-family:宋体;\">电　话：010-82881351、62968205</span><br />\r\n　　<span style=\"font-family:宋体;\">传　真：010-82881351</span>\r\n</p>\r\n<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;border:0px;list-style:none;font-family:simsun;font-size:14px;white-space:normal;background-color:#F7FBFF;\">\r\n	<br />\r\n　　<span style=\"font-family:宋体;\"><span style=\"line-height:21px;\">附　件：1．<a href=\"http://www.sport.gov.cn/n316/n336/c859971/part/502961.docx\" style=\"color:#333333;text-decoration-line:none;\">2018年“精英教练员双百培养计划”专业队教练员赴美国培训班报名表</a></span></span><br />\r\n　　　<span style=\"font-family:宋体;\"><span style=\"line-height:21px;\">　　　2.&nbsp;<a href=\"http://www.sport.gov.cn/n316/n336/c859971/part/502962.docx\" style=\"color:#333333;text-decoration-line:none;\">出国培训承诺书</a></span></span><br />\r\n　　　<span style=\"font-family:宋体;\"><span style=\"line-height:21px;\">　　　3.&nbsp;<a href=\"http://www.sport.gov.cn/n316/n336/c859971/part/502966.doc\" style=\"color:#333333;text-decoration-line:none;\">美国模拟签证表</a></span></span>\r\n</p>\r\n<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;border:0px;list-style:none;font-family:simsun;font-size:14px;white-space:normal;background-color:#F7FBFF;line-height:21px;text-indent:80pt;\">\r\n	<span style=\"font-family:宋体;\">　　</span>\r\n</p>\r\n<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;border:0px;list-style:none;font-family:simsun;font-size:14px;white-space:normal;background-color:#F7FBFF;line-height:21px;text-indent:80pt;\">\r\n	<span style=\"font-family:宋体;\">　　</span>\r\n</p>\r\n<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;border:0px;list-style:none;font-family:simsun;font-size:14px;white-space:normal;background-color:#F7FBFF;line-height:21px;text-indent:80pt;\">\r\n	<br />\r\n</p>\r\n<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;border:0px;list-style:none;font-family:simsun;font-size:14px;white-space:normal;background-color:#F7FBFF;line-height:21px;text-indent:80pt;\">\r\n	<span style=\"font-family:宋体;\">　　　　　　　　　　　　　　　　　　　　　体育总局竞体司</span>\r\n</p>\r\n<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;border:0px;list-style:none;font-family:simsun;font-size:14px;white-space:normal;background-color:#F7FBFF;line-height:21px;text-indent:80pt;\">\r\n	<span style=\"font-family:宋体;\">　　　　　　　　　　　　　　　　　　　　　2018年5月10日</span><span style=\"font-family:仿宋_GB2312;\">&nbsp;&nbsp;</span>\r\n</p>', '1', null, '2018-05-28 11:33:00');
INSERT INTO `article` VALUES ('45', '1', '94', '体育总局人力中心到中国文艺志愿者协会调研交流', null, '<div style=\"font-family:simsun;font-size:14px;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;background-color:#FFFFFF;\">\n	<span style=\"font-family:仿宋_gb2312;\"><span style=\"font-size:16px;\">&nbsp; 11月1日上午，人力中心副主任王军率中心志愿服务管理部一行到中国文艺志愿者协会进行调研，并与中国文艺志愿者协会相关负责人举行座谈会。</span></span>\n</div>\n<div style=\"font-family:simsun;font-size:14px;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;background-color:#FFFFFF;\">\n	<span style=\"font-family:仿宋_gb2312;\"><span style=\"font-size:16px;\">&nbsp; &nbsp; 中国文联文艺志愿服务中心主任、中国文艺志愿者协会副主席兼秘书长廖恳先后介绍了中国文艺志愿者协会理念、领导机构、经费来源、项目建设、制度建设的相关情况。人力中心副主任王军介绍了国家体育总局优秀运动员全民健身志愿服务组织协调机制建设成果和相关活动开展情况。随后双方就志愿者激励和培训、志愿服务信息系统建设、打造志愿服务项目等工作进行了深入交流。</span></span>\n</div>\n<div style=\"font-family:simsun;font-size:14px;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;background-color:#FFFFFF;\">\n	<span style=\"font-family:仿宋_gb2312;\"><span style=\"font-size:16px;\">&nbsp; &nbsp; 此次调研，是总局批准人力中心成立志愿服务管理部周年之际，为认真贯彻落实党的十九大报告精神和《志愿服务条例》，总结半年多初步开展工作基础上，学习借鉴中国文艺志愿者协会工作上的成功经验，加快推进优秀运动员志愿服务工作水平，促进优秀运动员志愿服务能力提升，推进体育志愿服务事业实现快速发展，集中开展的一次专题调研。</span></span>\n</div>\n<div style=\"font-family:simsun;font-size:14px;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;background-color:#FFFFFF;\">\n	<span style=\"font-family:仿宋_gb2312;\"><span style=\"font-size:16px;\">&nbsp; &nbsp; 中国文联文艺志愿服务中心副主任孙德华、文艺志愿服务中心联络服务处和组织协调处负责人及人力中心志愿服务管理部相关人员参加了座谈。</span></span>\n</div>', '0', null, '2018-05-31 15:30:11');
INSERT INTO `article` VALUES ('46', '1', '94', '优秀运动员全民健身志愿服务春耕正当时', 'upload/article/e34fa4ced9b1969b4470a1eb03aad482.jpeg', '<div style=\"font-family:simsun;font-size:14px;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;background-color:#FFFFFF;\">\r\n	<span style=\"font-size:18px;\"><span style=\"font-family:仿宋_gb2312;\">聚力谋划优秀运动员全民健身志愿服务规范有序、高质高效发展，2月7日，优秀运动员全民健身志愿服务工作座谈会在北京召开。<br />\r\n　　体育总局人力中心志愿服务管理部主任李立东做2017年志愿服务工作情况介绍，并主要针对“志愿服务优质项目研究与建设亟待提高，活动开展对接方与志愿服务经费渠道不够稳定与规范，品牌的打造与宣传亟待加强，志愿服务的理论研究、动员机制、培训机制、管理机制、项目建设与推动机制、表彰激励机制等基础工作有待完善”等存在问题与不足，征求各方意见，布局2018，推动优秀运动员全民健身志愿服务工作优质高效发展。<br />\r\n　　中国文联文艺志愿服务中心联络服务处处长宋智勤，首都体育学院足球教研室副教授、希望九洲体育公益社团负责人马克，中国传媒大学媒介与公共事物研究院公益传播研究所副所长李剑锋等相关单位代表；蹦床国家队领队李舸、奥运摔跤冠军王旭、奥运柔道冠军杨秀丽等优秀运动员代表，以及企业、媒体代表等就优秀运动员全民健身志愿服务项目建设和活动方式、内容等问题进行深度研讨，建言献策，广开思路，共谋发展。<br />\r\n　　“志愿服务，点燃希望，播种理想，让优秀运动员全民健身志愿服务工作因我们的努力而更美好！”体育总局人力中心副主任王军充分肯定了本次座谈从理论、机制、实操、评价机制四方面切入优秀运动员全民健身志愿服务工作，既有理论精神的高度，又有规范化的实操性指导意义，以及“走出去，请进来”联合发展的跨界融合性，为2018年工作的开展开了一个好头。<br />\r\n<br />\r\n</span></span>\r\n</div>\r\n<div style=\"font-family:simsun;font-size:14px;font-variant-numeric:normal;font-variant-east-asian:normal;line-height:24px;white-space:normal;widows:1;background-color:#FFFFFF;\">\r\n	<span style=\"font-size:18px;\"><span style=\"font-family:仿宋_gb2312;\"></span></span>\r\n</div>', '0', null, '2018-05-31 15:31:08');
INSERT INTO `article` VALUES ('47', '1', '94', '人力中心优秀运动员全民健身志愿服务2018年工作要点', null, '优秀运动员全民健身志愿服务是以具有广泛的社会影响力、号召力以及引领示范作用的优秀运动员为参与主体，以推动全民健身广泛开展为目的,以弘扬中华体育精神和奥林匹克精神，传播体育文化为目标，以“自我实践，服务他人，自我教育，推动社会”为宗旨，服务他人和社会的公益性活动。<br />\n&nbsp; &nbsp; 国家体育总局人力资源开发中心（以下简称：人力中心）作为体育总局优秀运动员全民健身志愿服务协调小组的办事机构，负责优秀运动员全民健身志愿服务活动的组织实施。自工作启动以来，已有198名包括奥运冠军、世界冠军、全国冠军在内的优秀运动员注册为“优秀运动员志愿者”，先后组织529人次优秀运动员参与体育总局和各地举办的各类体育活动，服务全民健身需求。2016年10月经体育总局党组批准，人力中心成立志愿服务管理部，专门负责优秀运动员全民健身志愿服务的组织实施工作。现将优秀运动员全民健身志愿服务2018年工作要点公布如下：<br />\n&nbsp; &nbsp; 一、弘扬中华体育精神和奥林匹克精神，传播体育文化，营造更加浓厚的优秀运动员体育志愿服务社会氛围<br />\n&nbsp; &nbsp; 2018年，优秀运动员全民健身志愿服务工作将以贯彻落实习近平新时代中国特色社会主义思想和关于体育工作的重要论述为指引，以服务全民健身需求为目标，以弘扬中华体育精神和奥林匹克精神，传播体育文化为引领，积极倡导志愿服务精神与理念，引领全民健身热潮，促进在全社会形成全民健身氛围。充分发挥优秀运动员榜样的示范引领作用，充分宣传优秀运动员顽强拼搏和全民健身志愿服务活动中的先进事迹。充分发挥媒体的舆论引导作用，营造良好的优秀运动员参与全民健身志愿服务的社会氛围。<br />\n&nbsp; &nbsp; 二、深入开展优秀运动员全民健身志愿服务活动，打造优秀运动员志愿服务品牌<br />\n&nbsp; &nbsp; 优秀运动员在全民健身活动中具有不可替代的示范、引领与带动作用，开展全民健身志愿服务项目建设，推动优秀运动员结合当地全民健身需求，走进社区、农村、企业、学校、连队积极参与全民健身活动，开展内容丰富、形式多样的全民健身志愿服务活动，推动优秀运动员全民健身志愿服务广泛深入开展意义深远。今年，将探索建立优秀运动员志愿服务联系点，进一步辐射带动全民健身志愿服务的开展，致力于优秀运动员志愿服务常态化地服务大众，走出一条开展优秀运动员志愿服务的品牌之路。今年拟召开优秀运动员志愿服务研讨会，进一步总结推广经验。人力中心有意愿、有责任，愿与体育系统各单位、各部门合作开展助力全民健身优秀运动员志愿服务活动，如有意向，可与人力中心志愿服务管理部联系（联系电话：010-87182093）。<br />\n&nbsp; &nbsp; 三、加强制度与项目建设，推动优秀运动员全民健身志愿服务长效化开展<br />\n&nbsp; &nbsp; 深刻认识制度保障的重要性，健全机制，运用好政策，是深化优秀运动员全民健身志愿服务必不可少的条件。通过进一步贯彻落实《志愿服务条例》、《体育总局建立全民健身志愿服务长效化机制工作方案》、《优秀运动员全民健身志愿服务实施办法（试行）》等规定，不断完善优秀运动员志愿者招募、注册、培训、激励、保障等制度建设。制订现役优秀运动员参加志愿服务活动时间累积不应少于7天与社会实践的具体措施；探索优秀运动员志愿服务激励机制；制订为优秀运动员志愿者购买意外保险等制度，确保志愿服务的各个环节有章可循。同时，针对广大群众特别是青少年的全民健身需求，围绕弘扬中华体育精神与奥林匹克精神，普及全民健身知识，宣传健康理念，激发健身活力，宣传健康文明科学的健身方式，促进体育消费，巩固、拓展、培育符合当地特点的特色全民健身志愿服务项目，实现志愿服务长效化。<br />\n&nbsp; &nbsp; 四、加大宣传力度，构筑理论研究和培训机制，培育社会化参与机制<br />\n&nbsp; &nbsp; 通过加强与各类媒体的密切合作，探索做好优秀运动员志愿者的典型宣传，集中展示优秀运动员志愿者风采，引导广大群众了解优秀运动员志愿服务、支持全民健身、参与全民健身。<br />\n&nbsp; &nbsp; 加强对志愿服务理论的学习与研究，提高对志愿服务规律性认识，深入开展调查研究和经验总结，推进优秀运动员全民健身志愿服务理论体系的研究和完善，运用理论研究成果，为推进全民健身志愿服务工作的深入发展提供可靠的支撑和依据。同时，开展优秀运动员全民健身志愿服务培训工作，进一步提升组织志愿服和志愿者服务水平。加强各地培训工作经验交流，探索建立“线下”“线上”培训模式，增强培训的实效性，建设彰显优秀运动员特征、满足全民健身需求、符合体育人才培养规律的优秀运动员全民健身志愿服务培训工作体系。今年，人力中心将继续举办志愿服务能力建设培训班，进一步推动志愿服务的开展和工作能力的提高。<br />\n&nbsp; &nbsp; 重视与社会环境相融合，充分发挥社会、市场优势，培育社会化参与机制。社会化的优秀运动员志愿服务就是允许和鼓励社会多种主体参与全民健身志愿活动，形成多样化和灵活性的参与局面。要推动以组织招募为主向公开招募为主的转变，实现招募方式的公开化。要多措并举，逐步实现志愿服务资金的社会化。挖掘社会资源，建立志愿者专项基金，从单纯以政府拨款为主转变为建立政府拨款、社会捐助、组织自筹等多元化获取资金的渠道。<br />\n&nbsp; &nbsp; 五、进一步强化运动员祖国培养、人民哺育的意识，充分发挥体育在构建社会主义和谐社会中的积极作用<br />\n&nbsp; &nbsp; 优秀运动员具有较高的社会知名度，组织奥运冠军、世界冠军开展志愿服务等社会公益活动，有利于在全社会形成团结互助、平等友爱、共同进步的社会氛围和人际关系，增加和谐因素、促进公平正义、维护社会稳定，是发挥体育在构建和谐社会中积极作用的重要方面。<br />\n&nbsp; &nbsp; 没有祖国的培养和人民的哺育，就没有运动员个人的成功。加强对优秀运动员参与全民健身志愿服务的动员，是对运动员进行理想信念教育的重要内容。为此，一是要进一步强化运动员祖国培养和人民哺育的意识，倡导“行善立德”、“学习雷锋、奉献他人、提升自己”等志愿服务理念，实现优秀运动员志愿服务的大众化。二是要通过媒体发布志愿服务项目，宣传优秀运动员志愿者，扩大优秀运动员志愿者“明星效应”和引领示范作用，进一步彰显金牌的社会价值。同时，在运动队中弘扬爱国、奉献、团结、拼搏精神，将全民健身志愿服务作为运动员进行社会实践，成为增强运动队凝聚力和战斗力的重要手段。<br />\n&nbsp; &nbsp; 优秀运动员志愿服务要始终将公益性放在首位，充分体现无偿、利他的基本要求，严格区分志愿服务活动与商业性活动，避免引发社会负面反映。优秀运动员全民健身志愿服务要坚持因地制宜、面向基层、群众受益、注重实效的原则，充分利用体育资源，广泛动员和整合社会资源，增强全民健身志愿服务活动的影响力，传播中华体育精神，传递社会正能量。<br />\n&nbsp;<br />\n&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 国家体育总局人力资源开发中心<br />\n&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;2018年1月26日<br />', '0', null, '2018-05-31 15:31:48');
INSERT INTO `article` VALUES ('48', '1', '92', '优秀运动员全民健身志愿服务活动走进宜宾', 'upload/article/f00977cc76235f0b9d949358d95fd6eb.jpeg', '&nbsp; 4月15日至16日，由国家体育总局人力资源开发中心组织的优秀运动员全民健身志愿服务活动走进了四川省宜宾市。此次活动吸引了2004年雅典奥运会女排冠军宋妮娜、1996年亚特兰大奥运会女足亚军温利蓉、原女篮国手许诺、原北京国安球员魏占奎、原赛艇运动员徐蕊以及CBA四川金强篮球俱乐部品胜男篮陈晓东和杨越的积极参与。<br />\r\n<br />\r\n<br />\r\n优秀运动员走进宜宾三中开展全民健身志愿服务<br />\r\n&nbsp;<br />\r\n走进中学 激发体育热情<br />\r\n<br />\r\n<br />\r\n陈晓东和杨越向宜宾三中女子篮球队传授投篮技巧<br />\r\n&nbsp; &nbsp; “刚刚我们进行了投篮练习，下面再来一个简单的对抗，大家一起打打球。”在宜宾三中，篮球运动员许诺、陈晓东和杨越在和学生在进行投篮练习后，召集校篮球队的队员进行了一场三人篮球对抗赛。赛后，高一学生母红洪十分兴奋：“我练习篮球差不多有四年的时间了，非常喜欢打球时的豪迈感觉。今天的交流时间很短，一开始我也挺紧张的，后来因为哥哥姐姐都很亲和，会纠正我们一些基本动作，也慢慢放松下来了。还见到他们做了一些之前只能在电视上才能看到的动作，坚定了我以后要继续刻苦练习的信心。”<br />\r\n<br />\r\n<br />\r\n魏占奎和学生交流足球技术<br />\r\n&nbsp; &nbsp; 同时，温丽蓉、魏占奎、宋妮娜也为足球和排球的队员进行了指导。魏占奎说，除了要告诉他们基本功的重要性，最想传递给他们的是体育精神，“我希望更快、更高、更强的体育精神传递不仅体现在体育竞技场上，还能出现在他们的日常生活中。现在生活和生存的压力都很大，一个团队或是个人想要进步，想要取得好成绩，就是要有一股拼劲才能成功。”<br />\r\n&nbsp;<br />\r\n走进小学 感受运动乐趣<br />\r\n&nbsp; &nbsp;<br />\r\n&nbsp; &nbsp; 中学生已经具备了一定的运动能力，可以进行一些对抗练习和技术指导，但是在小学，与同学们进行更多的是交流和互动，运动员们耐心回答小学生们提前准备的问题，告诉他们要“多运动不挑食，以后才能长高个儿。”和同学们比拼跳绳技能，徐蕊一边比一边说:“你们原来都跳得这么好，还会双摇和单编。” 宜宾市人民路小学副校长鲁能校区执行校长说：“非常开心运动员们能到学校和学生进行互动，激发他们的运动热情。”<br />\r\n<br />\r\n<br />\r\n宋妮娜和小朋友一起跳绳<br />\r\n<br />\r\n<br />\r\n徐蕊和小学生打乒乓球<br />\r\n&nbsp; &nbsp; 在与小学生进行篮球运球练习后，当体育老师问想看运动员做什么动作的时候，小学生们异口同声地说扣篮，但运动员没有选择直接扣篮，最后陈晓东抱起了一个男生让他真真切切地感受到了扣篮的感觉。<br />\r\n<br />\r\n<br />\r\n陈晓东托举起孩子扣篮<br />\r\n&nbsp;<br />\r\n&nbsp; &nbsp; “之前也参加过类似的公益活动，在和不同年级的同学互动时也有不一样的感受，但孩子们是真的热爱篮球这项运动，中学的同学需要一些更专业的教练去指导他们。在学生阶段，体育更多的是作为强健体魄的一种方式，运动使人开心，运动更会让人健康。”陈晓东说。<br />\r\n&nbsp;<br />\r\n走近市民 传播体育精神<br />\r\n&nbsp; &nbsp;<br />\r\n&nbsp; &nbsp; 优秀运动员不仅走访了五所中小学，还领跑了2018年国家森林城市马拉松系列赛蜀南竹海站比赛，和宜宾的市民一起运动。<br />\r\n<br />\r\n<br />\r\n优秀运动员领跑马拉松赛事<br />\r\n&nbsp; &nbsp; 宜宾市体育和教育局副局长肖安祥说：“宜宾在篮球和排球项目上拥有良好的基础，现在也加大了校园足球的推广和培训力度。三大球等集体项目非常有助于孩子的全面发展，邀请优秀运动员志愿者来宜宾，不仅可以从技术对他们进行指导，更重要的是给他们传递体育精神，让他们有一个新的提高。”<br />\r\n&nbsp; &nbsp; 国家体育总局人力中心志愿服务管理部主任李立东认为，国家体育总局开展优秀运动员全民健身志愿服务活动主要的目的是通过优秀运动员引领带动作用促进全民健身，弘扬中华体育精神、奥林匹克精神，传播体育文化。优秀运动员走进学校，通过自身的个人价值去推动学校运动的发展，不仅对小运动发挥榜样作用，而且还可以促进他们德智体全面发展。<br />\r\n&nbsp; &nbsp; 李立东说，优秀运动员志愿服务还将到更多的地方开展活动，不断完善志愿服务项目建设，进一步志愿服务与地方全民健身项目的深度融合，探索如何更好地将运动员身上的体育精神通过志愿服务的形式展现和传递给更多的人，让他们感受到中华体育精神的魅力。”<br />', '0', null, '2018-05-31 15:33:00');
INSERT INTO `article` VALUES ('49', '1', '92', '优秀运动员助力健康中国行—科学健身主题宣传活动启动仪式', 'upload/article/c9e92cb6326d9b755d2c1fb262fa3958.jpeg', '&nbsp; &nbsp; 4月28日，2018年健康中国行—科学健身主题宣传活动启动仪式在京举行。本次活动由国家卫生健康委员会、国家体育总局、教育部、全国总工会、共青团中央、全国妇联主办，中国健康教育中心、国家体育总局体育科学研究所承办，优秀运动员代表何雯娜、武大靖与演员刘涛、歌唱演员蔡国庆受聘成为活动宣传大使，国家体育总局赵勇副局长、国家卫生健康委员会王贺胜副主任为宣传大使颁发了聘书。', '0', null, '2018-05-31 15:33:57');

-- ----------------------------
-- Table structure for baseoption
-- ----------------------------
DROP TABLE IF EXISTS `baseoption`;
CREATE TABLE `baseoption` (
  `optionType` varchar(20) NOT NULL COMMENT '类型',
  `optionKey` varchar(50) NOT NULL COMMENT '键',
  `optionValue` varchar(100) NOT NULL COMMENT '值'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='设置表';

-- ----------------------------
-- Records of baseoption
-- ----------------------------
INSERT INTO `baseoption` VALUES ('AdminContrl', 'admin', '$2y$10$6x0P0ubJSyi3RJNVyx9yPuPqBDaflpSneoTurxq8WJ.iEyDwJCC8e');
INSERT INTO `baseoption` VALUES ('integral', 'minfo', '0');
INSERT INTO `baseoption` VALUES ('integral', 'inviting', '0');
INSERT INTO `baseoption` VALUES ('rongcloud', 'appkey', 'mgb7ka1nm4esg');
INSERT INTO `baseoption` VALUES ('rongcloud', 'appsecret', 'JiKsJNCCobSkcL');
INSERT INTO `baseoption` VALUES ('integral', 'base', '120');

-- ----------------------------
-- Table structure for blacklists
-- ----------------------------
DROP TABLE IF EXISTS `blacklists`;
CREATE TABLE `blacklists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned DEFAULT NULL,
  `friendId` int(10) unsigned DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `timestamp` bigint(20) unsigned DEFAULT '0',
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blacklists_user_id_friend_id` (`userId`,`friendId`),
  KEY `blacklists_user_id_timestamp` (`userId`,`timestamp`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blacklists
-- ----------------------------

-- ----------------------------
-- Table structure for comment
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT '0' COMMENT '1.志愿服务2.媒体资讯3.视频教程',
  `aid` int(10) DEFAULT '0' COMMENT '志愿服务/媒体资讯等评论ID',
  `mid` int(10) DEFAULT '0' COMMENT '评论用户ID',
  `content` text COMMENT '评论内容(图文)',
  `comment` int(4) DEFAULT '0' COMMENT '被评论数',
  `praise` int(4) DEFAULT '0' COMMENT '被点赞数',
  `createTime` datetime DEFAULT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8 COMMENT='志愿服务/媒体资讯等评论';

-- ----------------------------
-- Records of comment
-- ----------------------------
INSERT INTO `comment` VALUES ('1', '1', '1', '1', '<img src=\'/public/home/images/face/16.gif\' />', '0', '0', '2018-05-01 19:45:38');
INSERT INTO `comment` VALUES ('2', '1', '2', '1', '<img src=\'/public/home/images/face/42.gif\' />', '0', '0', '2018-05-02 09:51:57');
INSERT INTO `comment` VALUES ('3', '2', '5', '2', '<img src=\'/public/home/images/face/59.gif\' />', '0', '0', '2018-05-02 13:21:27');
INSERT INTO `comment` VALUES ('4', '2', '6', '1', '彬彬', '0', '0', '2018-05-03 15:02:41');
INSERT INTO `comment` VALUES ('5', '1', '11', '1', 'yyyy', '0', '2', '2018-05-08 10:54:18');
INSERT INTO `comment` VALUES ('6', '1', '4', '1', '恩快捷键', '0', '0', '2018-05-08 11:40:37');
INSERT INTO `comment` VALUES ('7', '1', '4', '1', '<img src=\'/public/home/images/face/44.gif\' />', '0', '0', '2018-05-08 11:41:36');
INSERT INTO `comment` VALUES ('8', '1', '12', '1', '点击', '0', '0', '2018-05-08 13:37:58');
INSERT INTO `comment` VALUES ('9', '2', '19', '1', '☺', '0', '2', '2018-05-08 17:02:36');
INSERT INTO `comment` VALUES ('10', '3', '5', '2', '', '0', '0', '2018-05-08 18:19:06');
INSERT INTO `comment` VALUES ('11', '3', '5', '2', '<img src=\'/public/home/images/face/21.gif\' />', '0', '1', '2018-05-08 18:19:18');
INSERT INTO `comment` VALUES ('12', '1', '5', '1', '123', '0', '0', '2018-05-09 12:56:27');
INSERT INTO `comment` VALUES ('13', '2', '16', '1', '测试', '0', '2', '2018-05-09 14:12:58');
INSERT INTO `comment` VALUES ('15', '2', '18', '2', '<img src=\'/public/home/images/face/57.gif\' />', '0', '19', '2018-05-10 16:37:50');
INSERT INTO `comment` VALUES ('14', '1', '11', '1', '123', '0', '2', '2018-05-10 15:31:03');
INSERT INTO `comment` VALUES ('16', '1', '13', '2', '', '0', '0', '2018-05-10 16:40:00');
INSERT INTO `comment` VALUES ('17', '1', '13', '2', '<img src=\'/public/home/images/face/5.gif\' />', '0', '1', '2018-05-10 16:40:07');
INSERT INTO `comment` VALUES ('18', '1', '11', '1', '88888', '0', '1', '2018-05-10 18:56:24');
INSERT INTO `comment` VALUES ('19', '2', '19', '1', '<img src=\'/public/home/images/face/57.gif\' />', '0', '1', '2018-05-11 10:25:58');
INSERT INTO `comment` VALUES ('20', '1', '11', '1', '测试', '0', '1', '2018-05-11 13:50:15');
INSERT INTO `comment` VALUES ('21', '2', '18', '1', '回复 比利测试', '0', '20', '2018-05-11 15:16:51');
INSERT INTO `comment` VALUES ('22', '2', '27', '1', '咯哦哦', '0', '1', '2018-05-14 09:27:23');
INSERT INTO `comment` VALUES ('23', '2', '27', '1', '123123', '0', '0', '2018-05-14 09:27:47');
INSERT INTO `comment` VALUES ('24', '1', '12', '86', '发kobe', '0', '2', '2018-05-15 09:50:48');
INSERT INTO `comment` VALUES ('25', '4', '1', '1', 'aaaa', '0', '1', '2018-05-16 17:26:39');
INSERT INTO `comment` VALUES ('26', '3', '2', '1', '<img src=\'/public/home/images/face/17.gif\' />', '0', '0', '2018-05-20 09:11:41');
INSERT INTO `comment` VALUES ('27', '2', '27', '2', '？', '0', '6', '2018-05-22 09:30:39');
INSERT INTO `comment` VALUES ('28', '2', '27', '2', '<img src=\'/public/home/images/face/14.gif\' />', '0', '2', '2018-05-22 09:32:47');
INSERT INTO `comment` VALUES ('29', '2', '16', '2', '<img src=\'/public/home/images/face/4.gif\' />', '0', '1', '2018-05-23 14:51:12');
INSERT INTO `comment` VALUES ('30', '3', '9', '2', '学习了', '0', '0', '2018-05-23 16:16:01');
INSERT INTO `comment` VALUES ('31', '3', '9', '1', '加强学习，提高能力。', '0', '0', '2018-05-28 14:46:32');
INSERT INTO `comment` VALUES ('32', '1', '11', '90', '<img src=\'/public/home/images/face/20.gif\' />', '0', '1', '2018-05-29 14:52:00');
INSERT INTO `comment` VALUES ('33', '1', '8', '91', '<img src=\'/public/home/images/face/11.gif\' />', '0', '1', '2018-05-30 16:01:47');
INSERT INTO `comment` VALUES ('38', '2', '47', '103', '123', '0', '0', '2018-06-20 09:45:53');
INSERT INTO `comment` VALUES ('39', '4', '4', '2', '<img src=\'/public/home/images/face/59.gif\' />', '0', '0', '2018-06-28 15:52:34');

-- ----------------------------
-- Table structure for course
-- ----------------------------
DROP TABLE IF EXISTS `course`;
CREATE TABLE `course` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT '0' COMMENT '类型0基础1提高',
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `cover` varchar(100) DEFAULT NULL COMMENT '封面',
  `url` varchar(500) DEFAULT NULL COMMENT '视频地址',
  `content` varchar(500) DEFAULT NULL COMMENT '内容概述',
  `integral` int(4) DEFAULT '0' COMMENT '积分',
  `genre` tinyint(1) DEFAULT NULL COMMENT '积分类型0赠送1消费',
  `status` tinyint(1) DEFAULT '0' COMMENT '是否发布',
  `createTime` datetime DEFAULT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='教程';

-- ----------------------------
-- Records of course
-- ----------------------------
INSERT INTO `course` VALUES ('9', '1', '外交官眼中的外交工作兼谈应对国际体育事务的能力——李肇星', 'upload/course/cover/20180518680.png', 'http://vod.whenevermart.com/8b7dc4fa671d46aa9dbcace9758d5a4e/a725e572e8714d4a9e10a2ee33d065a1-85caca4973bab2a000d47a3a796de6df-ld.mp4', '李肇星，原中华人民共和国外交部部长，第十一届全国人大外事委员会主任委员，长期从事外交工作，被誉为“铁嘴钢牙”外交官。', '15', null, '1', '2018-06-05 17:25:09');

-- ----------------------------
-- Table structure for data_versions
-- ----------------------------
DROP TABLE IF EXISTS `data_versions`;
CREATE TABLE `data_versions` (
  `userId` int(10) unsigned NOT NULL,
  `userVersion` bigint(20) unsigned DEFAULT '0',
  `blacklistVersion` bigint(20) unsigned DEFAULT '0',
  `friendshipVersion` bigint(20) unsigned DEFAULT '0',
  `groupVersion` bigint(20) unsigned DEFAULT '0',
  `groupMemberVersion` bigint(20) unsigned DEFAULT '0',
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of data_versions
-- ----------------------------

-- ----------------------------
-- Table structure for friendships
-- ----------------------------
DROP TABLE IF EXISTS `friendships`;
CREATE TABLE `friendships` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned DEFAULT NULL,
  `friendId` int(10) unsigned DEFAULT NULL,
  `displayName` varchar(32) DEFAULT '',
  `message` varchar(64) DEFAULT NULL,
  `status` int(10) unsigned DEFAULT NULL,
  `timestamp` bigint(20) unsigned DEFAULT '0',
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `friendships_user_id_friend_id` (`userId`,`friendId`),
  KEY `friendships_user_id_timestamp` (`userId`,`timestamp`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of friendships
-- ----------------------------
INSERT INTO `friendships` VALUES ('1', '4', '1', '', '', '20', '1527485420704', '2018-05-27 09:05:36', '2018-05-28 13:30:20');
INSERT INTO `friendships` VALUES ('2', '1', '4', '', '', '20', '1527485420704', '2018-05-27 09:05:36', '2018-05-28 16:54:07');
INSERT INTO `friendships` VALUES ('3', '91', '1', '', '', '20', '1527582189308', '2018-05-29 16:17:04', '2018-05-29 16:23:09');
INSERT INTO `friendships` VALUES ('4', '1', '91', '', '我是test', '20', '1527582189308', '2018-05-29 16:17:04', '2018-05-29 16:23:09');
INSERT INTO `friendships` VALUES ('5', '91', '4', '', '', '20', '1527581954407', '2018-05-29 16:18:09', '2018-05-29 16:19:14');
INSERT INTO `friendships` VALUES ('6', '4', '91', '', '我是test', '20', '1527581954407', '2018-05-29 16:18:09', '2018-05-29 16:19:14');
INSERT INTO `friendships` VALUES ('7', '1', '2', '', '', '30', '1529633161709', '2018-06-12 13:58:39', '2018-06-22 10:06:01');
INSERT INTO `friendships` VALUES ('8', '2', '1', '', '我是都市浪人', '20', '1528784044891', '2018-06-12 13:58:39', '2018-06-12 14:14:04');

-- ----------------------------
-- Table structure for groups
-- ----------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(10) NOT NULL,
  `name` varchar(32) DEFAULT NULL,
  `portraitUri` varchar(256) DEFAULT '',
  `memberCount` int(10) unsigned DEFAULT '0',
  `maxMemberCount` int(10) unsigned DEFAULT '500',
  `creatorId` int(10) unsigned DEFAULT NULL,
  `bulletin` text,
  `type` varchar(50) DEFAULT NULL,
  `timestamp` bigint(20) unsigned DEFAULT '0',
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL,
  UNIQUE KEY `groups_id_timestamp` (`id`,`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of groups
-- ----------------------------
INSERT INTO `groups` VALUES ('1', '球类健身', '', '0', '500', '1', null, '1', '1527560365030', '2018-05-01 19:45:10', '2018-06-12 09:50:37', '2018-06-12 09:50:37');
INSERT INTO `groups` VALUES ('2', '“健康环湖走”主题活动', '', '0', '500', '1', null, '1', '1525225304', '2018-05-02 09:41:44', '2018-06-12 09:50:41', '2018-06-12 09:50:41');
INSERT INTO `groups` VALUES ('3', '体育中心健身观演观赛 乐享清明小长假', '', '0', '500', '1', null, '1', '1525241189', '2018-05-02 14:06:29', '2018-06-12 09:50:50', '2018-06-12 09:50:50');
INSERT INTO `groups` VALUES ('4', '平谷办自行车嘉年华', '', '0', '500', '1', null, '1', '1525410293', '2018-05-04 13:04:53', '2018-06-12 09:50:54', '2018-06-12 09:50:54');
INSERT INTO `groups` VALUES ('5', '环燕羽山亲子跑', '', '0', '500', '1', null, '1', '1525410520', '2018-05-04 13:08:40', '2018-06-12 09:50:59', '2018-06-12 09:50:59');
INSERT INTO `groups` VALUES ('6', '收获健康收获爱，“公开赛”公益力量进校园', '', '0', '500', '1', null, '1', '1525410764', '2018-05-04 13:12:44', '2018-06-12 09:51:03', '2018-06-12 09:51:03');
INSERT INTO `groups` VALUES ('7', '2018环渤海不间断骑行', '', '0', '500', '1', null, '1', '1525421683', '2018-05-04 16:14:43', '2018-06-12 09:51:09', '2018-06-12 09:51:09');
INSERT INTO `groups` VALUES ('8', '大众汽车青少年足球训练营', '', '1', '500', '1', null, '1', '1525424742', '2018-05-04 17:05:42', null, null);
INSERT INTO `groups` VALUES ('11', '万人马拉松比赛正在进行中', '', '3', '500', '1', null, '1', '1527644320463', '2018-05-08 09:38:15', '2018-05-30 09:38:40', null);
INSERT INTO `groups` VALUES ('14', '冠军体育课--2017年.崇义', '', '1', '500', '1', null, '1', '1527730472', '2018-05-31 09:34:32', null, null);
INSERT INTO `groups` VALUES ('15', 'abc', '', '3', '500', '1', null, '1', '1527737779265', '2018-05-31 11:36:19', '2018-05-31 11:36:19', null);
INSERT INTO `groups` VALUES ('16', 'opp', '', '3', '500', '1', null, '1', '1527737985800', '2018-05-31 11:39:45', '2018-05-31 11:39:45', null);
INSERT INTO `groups` VALUES ('17', '123', '', '3', '500', '1', null, '1', '1527739141553', '2018-05-31 11:59:01', '2018-05-31 11:59:01', null);
INSERT INTO `groups` VALUES ('18', '开展工作', '', '3', '500', '1', null, '1', '1527739289626', '2018-05-31 12:01:29', '2018-05-31 12:01:29', null);
INSERT INTO `groups` VALUES ('19', '星期一', '', '0', '500', '1', null, '1', '1528077651870', '2018-06-04 10:00:51', '2018-06-11 11:42:47', '2018-06-04 10:02:01');
INSERT INTO `groups` VALUES ('17', 'test', '', '1', '500', '1', null, '1', '1528263359', '2018-06-06 13:35:59', null, null);
INSERT INTO `groups` VALUES ('18', 'test', '', '1', '500', '1', null, '1', '1528263528', '2018-06-06 13:38:48', null, null);
INSERT INTO `groups` VALUES ('19', 'test', '', '0', '500', '1', null, '1', '1528263619', '2018-06-06 13:40:19', '2018-06-11 11:42:47', '2018-06-11 11:42:47');
INSERT INTO `groups` VALUES ('20', 'test', '', '0', '500', '1', null, '1', '1528265845', '2018-06-06 14:17:25', '2018-06-11 11:42:41', '2018-06-11 11:42:41');
INSERT INTO `groups` VALUES ('21', 'test', '', '0', '500', '1', null, '1', '1528452148', '2018-06-08 18:02:28', '2018-06-11 11:42:37', '2018-06-11 11:42:37');
INSERT INTO `groups` VALUES ('22', '111', '', '0', '500', '1', null, '1', '1529052867', '2018-06-15 16:54:27', '2018-06-22 09:52:41', '2018-06-22 09:52:41');

-- ----------------------------
-- Table structure for group_members
-- ----------------------------
DROP TABLE IF EXISTS `group_members`;
CREATE TABLE `group_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupId` int(10) unsigned NOT NULL DEFAULT '0',
  `memberId` int(10) unsigned NOT NULL,
  `displayName` varchar(32) NOT NULL DEFAULT '',
  `role` int(10) unsigned NOT NULL,
  `show` tinyint(1) DEFAULT NULL,
  `isDeleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_users_member_id_timestamp` (`memberId`,`timestamp`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of group_members
-- ----------------------------
INSERT INTO `group_members` VALUES ('1', '1', '1', '', '1', '1', '1', '1528768237307', '2018-05-01 19:45:10', '2018-06-12 09:50:37');
INSERT INTO `group_members` VALUES ('2', '2', '1', '', '1', '1', '1', '1528768241311', '2018-05-02 09:41:44', '2018-06-12 09:50:41');
INSERT INTO `group_members` VALUES ('5', '3', '1', '', '1', '1', '1', '1528768250545', '2018-05-02 14:06:29', '2018-06-12 09:50:50');
INSERT INTO `group_members` VALUES ('7', '4', '1', '', '1', '1', '1', '1528768254784', '2018-05-04 13:04:53', '2018-06-12 09:50:54');
INSERT INTO `group_members` VALUES ('8', '5', '1', '', '1', '1', '1', '1528768259056', '2018-05-04 13:08:40', '2018-06-12 09:50:59');
INSERT INTO `group_members` VALUES ('9', '6', '1', '', '1', '1', '1', '1528768263742', '2018-05-04 13:12:44', '2018-06-12 09:51:03');
INSERT INTO `group_members` VALUES ('10', '7', '1', '', '1', '1', '1', '1528768269321', '2018-05-04 16:14:43', '2018-06-12 09:51:09');
INSERT INTO `group_members` VALUES ('12', '8', '1', '', '1', '1', '0', '1525424742', '2018-05-04 17:05:42', '0000-00-00 00:00:00');
INSERT INTO `group_members` VALUES ('16', '13', '1', '', '1', '1', '1', '1527560604313', '2018-05-09 11:09:26', '2018-05-29 10:23:24');
INSERT INTO `group_members` VALUES ('17', '1', '4', '', '1', '1', '1', '1527560365030', '2018-05-28 13:32:33', '2018-05-29 10:19:25');
INSERT INTO `group_members` VALUES ('18', '0', '4', '', '1', '1', '0', '1527493221923', '2018-05-28 15:40:21', '2018-05-28 15:40:21');
INSERT INTO `group_members` VALUES ('19', '0', '1', '', '0', '1', '0', '1527493221923', '2018-05-28 15:40:21', '2018-05-28 15:40:21');
INSERT INTO `group_members` VALUES ('20', '27', '4', '', '1', '1', '0', '1527493386364', '2018-05-28 15:43:06', '2018-05-28 15:43:06');
INSERT INTO `group_members` VALUES ('21', '27', '1', '', '0', '1', '0', '1527493386364', '2018-05-28 15:43:06', '2018-05-28 15:43:06');
INSERT INTO `group_members` VALUES ('22', '28', '1', '', '1', '1', '0', '1527496052506', '2018-05-28 16:27:32', '2018-05-28 16:27:32');
INSERT INTO `group_members` VALUES ('23', '28', '4', '', '0', '1', '0', '1527496052506', '2018-05-28 16:27:32', '2018-05-28 16:27:32');
INSERT INTO `group_members` VALUES ('31', '11', '91', '', '0', '1', '0', '1527585066', '2018-05-29 17:11:06', '0000-00-00 00:00:00');
INSERT INTO `group_members` VALUES ('32', '8', '91', '', '0', '1', '0', '1527644203', '2018-05-30 09:36:43', '0000-00-00 00:00:00');
INSERT INTO `group_members` VALUES ('33', '11', '1', '', '1', '1', '0', '1527644320463', '2018-05-30 09:38:40', '2018-05-30 09:38:40');
INSERT INTO `group_members` VALUES ('34', '14', '1', '', '1', '1', '0', '1527730472', '2018-05-31 09:34:32', '0000-00-00 00:00:00');
INSERT INTO `group_members` VALUES ('35', '15', '4', '', '1', '1', '0', '1527737779265', '2018-05-31 11:36:19', '2018-05-31 11:36:19');
INSERT INTO `group_members` VALUES ('36', '15', '91', '', '1', '1', '0', '1527737779265', '2018-05-31 11:36:19', '2018-05-31 11:36:19');
INSERT INTO `group_members` VALUES ('37', '15', '1', '', '0', '1', '0', '1527737779265', '2018-05-31 11:36:19', '2018-05-31 11:36:19');
INSERT INTO `group_members` VALUES ('38', '16', '4', '', '1', '1', '0', '1527737985800', '2018-05-31 11:39:45', '2018-05-31 11:39:45');
INSERT INTO `group_members` VALUES ('39', '16', '91', '', '1', '1', '0', '1527737985800', '2018-05-31 11:39:45', '2018-05-31 11:39:45');
INSERT INTO `group_members` VALUES ('40', '16', '1', '', '0', '1', '0', '1527737985800', '2018-05-31 11:39:45', '2018-05-31 11:39:45');
INSERT INTO `group_members` VALUES ('41', '17', '4', '', '1', '1', '0', '1527739141553', '2018-05-31 11:59:01', '2018-05-31 11:59:01');
INSERT INTO `group_members` VALUES ('42', '17', '91', '', '1', '1', '0', '1527739141553', '2018-05-31 11:59:01', '2018-05-31 11:59:01');
INSERT INTO `group_members` VALUES ('43', '17', '1', '', '0', '1', '0', '1527739141553', '2018-05-31 11:59:01', '2018-05-31 11:59:01');
INSERT INTO `group_members` VALUES ('44', '18', '4', '', '1', '1', '0', '1527739289626', '2018-05-31 12:01:29', '2018-05-31 12:01:29');
INSERT INTO `group_members` VALUES ('45', '18', '91', '', '1', '1', '0', '1527739289626', '2018-05-31 12:01:29', '2018-05-31 12:01:29');
INSERT INTO `group_members` VALUES ('46', '18', '1', '', '0', '1', '0', '1527739289626', '2018-05-31 12:01:29', '2018-05-31 12:01:29');
INSERT INTO `group_members` VALUES ('47', '19', '4', '', '1', '1', '1', '1528077721010', '2018-06-04 10:00:51', '2018-06-04 10:02:01');
INSERT INTO `group_members` VALUES ('48', '19', '91', '', '1', '1', '1', '1528077721010', '2018-06-04 10:00:51', '2018-06-04 10:02:01');
INSERT INTO `group_members` VALUES ('49', '19', '1', '', '0', '1', '1', '1528077721010', '2018-06-04 10:00:51', '2018-06-04 10:02:01');
INSERT INTO `group_members` VALUES ('52', '19', '1', '', '1', '1', '1', '1528688567018', '2018-06-06 13:40:19', '2018-06-11 11:42:47');
INSERT INTO `group_members` VALUES ('53', '20', '1', '', '1', '1', '1', '1528688561151', '2018-06-06 14:17:25', '2018-06-11 11:42:41');
INSERT INTO `group_members` VALUES ('54', '21', '1', '', '1', '1', '1', '1528688557112', '2018-06-08 18:02:28', '2018-06-11 11:42:37');
INSERT INTO `group_members` VALUES ('55', '22', '1', '', '1', '1', '1', '1529632361625', '2018-06-15 16:54:27', '2018-06-22 09:52:41');

-- ----------------------------
-- Table structure for group_syncs
-- ----------------------------
DROP TABLE IF EXISTS `group_syncs`;
CREATE TABLE `group_syncs` (
  `groupId` int(10) unsigned NOT NULL DEFAULT '0',
  `syncInfo` tinyint(1) DEFAULT '0',
  `syncMember` tinyint(1) DEFAULT '0',
  `dismiss` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`groupId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of group_syncs
-- ----------------------------

-- ----------------------------
-- Table structure for login_logs
-- ----------------------------
DROP TABLE IF EXISTS `login_logs`;
CREATE TABLE `login_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned DEFAULT NULL,
  `ipAddress` int(10) unsigned DEFAULT NULL,
  `os` varchar(64) DEFAULT NULL,
  `osVersion` varchar(64) DEFAULT NULL,
  `carrier` varchar(64) DEFAULT NULL,
  `device` varchar(64) DEFAULT NULL,
  `manufacturer` varchar(64) DEFAULT NULL,
  `userAgent` varchar(256) DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of login_logs
-- ----------------------------

-- ----------------------------
-- Table structure for message
-- ----------------------------
DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT '0' COMMENT '1.在线留言2.意见反馈',
  `content` text COMMENT '留言内容',
  `createTime` datetime DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='在线留言/意见反馈表';

-- ----------------------------
-- Records of message
-- ----------------------------
INSERT INTO `message` VALUES ('1', '2', '不知道', '2018-05-20 09:46:10');
INSERT INTO `message` VALUES ('2', '2', '没有内容哇', '2018-05-23 14:16:31');
INSERT INTO `message` VALUES ('3', '2', '513376467', '2018-05-24 16:20:18');
INSERT INTO `message` VALUES ('4', '2', 'dyjbvv', '2018-06-20 09:45:16');
INSERT INTO `message` VALUES ('5', '2', '？', '2018-06-27 15:23:03');

-- ----------------------------
-- Table structure for notice_push
-- ----------------------------
DROP TABLE IF EXISTS `notice_push`;
CREATE TABLE `notice_push` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) DEFAULT NULL COMMENT '机型',
  `registration_id` varchar(25) NOT NULL COMMENT '推送ID',
  `status` tinyint(1) DEFAULT '1' COMMENT '1开0关',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='推送设备';

-- ----------------------------
-- Records of notice_push
-- ----------------------------
INSERT INTO `notice_push` VALUES ('2', 'android', '190e35f7e0177cce4ad', '1');
INSERT INTO `notice_push` VALUES ('3', 'android', '1a0018970af4733f064', '1');
INSERT INTO `notice_push` VALUES ('4', 'android', '100d855909152c9b992', '1');
INSERT INTO `notice_push` VALUES ('5', 'android', '170976fa8ad38c24f62', '1');
INSERT INTO `notice_push` VALUES ('6', 'android', '190e35f7e017657effb', '1');
INSERT INTO `notice_push` VALUES ('7', 'android', '120c83f7607450d57df', '1');

-- ----------------------------
-- Table structure for notice_read
-- ----------------------------
DROP TABLE IF EXISTS `notice_read`;
CREATE TABLE `notice_read` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mid` int(10) DEFAULT '0' COMMENT '会员ID',
  `nid` int(10) DEFAULT '0' COMMENT '对应消息ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of notice_read
-- ----------------------------
INSERT INTO `notice_read` VALUES ('2', '2', '15');
INSERT INTO `notice_read` VALUES ('3', '2', '24');
INSERT INTO `notice_read` VALUES ('32', '103', '15');
INSERT INTO `notice_read` VALUES ('31', '103', '24');
INSERT INTO `notice_read` VALUES ('8', '1', '15');
INSERT INTO `notice_read` VALUES ('30', '103', '40');
INSERT INTO `notice_read` VALUES ('29', '103', '41');
INSERT INTO `notice_read` VALUES ('11', '1', '24');
INSERT INTO `notice_read` VALUES ('28', '4', '40');
INSERT INTO `notice_read` VALUES ('27', '2', '41');
INSERT INTO `notice_read` VALUES ('26', '2', '40');
INSERT INTO `notice_read` VALUES ('25', '96', '15');
INSERT INTO `notice_read` VALUES ('24', '96', '40');
INSERT INTO `notice_read` VALUES ('23', '1', '41');
INSERT INTO `notice_read` VALUES ('22', '1', '40');

-- ----------------------------
-- Table structure for praise
-- ----------------------------
DROP TABLE IF EXISTS `praise`;
CREATE TABLE `praise` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT '0' COMMENT '1评论相关2用户发表相关',
  `mid` int(10) DEFAULT '0' COMMENT '点赞人',
  `cid` int(10) DEFAULT NULL COMMENT '对应主表ID',
  `createTime` datetime DEFAULT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=133 DEFAULT CHARSET=utf8 COMMENT='点赞表';

-- ----------------------------
-- Records of praise
-- ----------------------------
INSERT INTO `praise` VALUES ('126', '1', '2', '15', '2018-06-11 17:19:04');
INSERT INTO `praise` VALUES ('10', '1', '2', '5', '2018-05-10 16:39:35');
INSERT INTO `praise` VALUES ('9', '2', '1', '2', '2018-05-10 16:39:07');
INSERT INTO `praise` VALUES ('11', '1', '2', '17', '2018-05-10 16:40:09');
INSERT INTO `praise` VALUES ('12', '2', '2', '8', '2018-05-10 16:41:44');
INSERT INTO `praise` VALUES ('50', '1', '86', '24', '2018-05-15 09:50:51');
INSERT INTO `praise` VALUES ('39', '1', '1', '14', '2018-05-13 13:10:57');
INSERT INTO `praise` VALUES ('19', '1', '18', '14', '2018-05-11 13:25:41');
INSERT INTO `praise` VALUES ('16', '1', '18', '18', '2018-05-11 09:34:42');
INSERT INTO `praise` VALUES ('17', '1', '18', '5', '2018-05-11 09:34:43');
INSERT INTO `praise` VALUES ('38', '1', '1', '9', '2018-05-12 11:37:34');
INSERT INTO `praise` VALUES ('37', '2', '1', '7', '2018-05-11 19:04:57');
INSERT INTO `praise` VALUES ('132', '1', '2', '34', '2018-06-27 18:11:57');
INSERT INTO `praise` VALUES ('25', '1', '18', '11', '2018-05-11 14:55:31');
INSERT INTO `praise` VALUES ('28', '2', '1', '6', '2018-05-11 15:09:47');
INSERT INTO `praise` VALUES ('78', '2', '1', '15', '2018-05-17 10:10:00');
INSERT INTO `praise` VALUES ('123', '1', '1', '20', '2018-05-25 13:10:15');
INSERT INTO `praise` VALUES ('58', '2', '1', '21', '2018-05-17 10:09:47');
INSERT INTO `praise` VALUES ('59', '2', '1', '21', '2018-05-17 10:09:48');
INSERT INTO `praise` VALUES ('60', '2', '1', '21', '2018-05-17 10:09:50');
INSERT INTO `praise` VALUES ('61', '2', '1', '21', '2018-05-17 10:09:50');
INSERT INTO `praise` VALUES ('62', '2', '1', '21', '2018-05-17 10:09:51');
INSERT INTO `praise` VALUES ('63', '2', '1', '21', '2018-05-17 10:09:52');
INSERT INTO `praise` VALUES ('64', '2', '1', '21', '2018-05-17 10:09:52');
INSERT INTO `praise` VALUES ('65', '2', '1', '21', '2018-05-17 10:09:53');
INSERT INTO `praise` VALUES ('66', '2', '1', '21', '2018-05-17 10:09:53');
INSERT INTO `praise` VALUES ('67', '2', '1', '21', '2018-05-17 10:09:54');
INSERT INTO `praise` VALUES ('68', '2', '1', '21', '2018-05-17 10:09:55');
INSERT INTO `praise` VALUES ('69', '2', '1', '21', '2018-05-17 10:09:55');
INSERT INTO `praise` VALUES ('70', '2', '1', '21', '2018-05-17 10:09:55');
INSERT INTO `praise` VALUES ('71', '2', '1', '21', '2018-05-17 10:09:56');
INSERT INTO `praise` VALUES ('72', '2', '1', '21', '2018-05-17 10:09:56');
INSERT INTO `praise` VALUES ('73', '2', '1', '21', '2018-05-17 10:09:56');
INSERT INTO `praise` VALUES ('74', '2', '1', '21', '2018-05-17 10:09:56');
INSERT INTO `praise` VALUES ('75', '2', '1', '21', '2018-05-17 10:09:56');
INSERT INTO `praise` VALUES ('76', '2', '1', '21', '2018-05-17 10:09:57');
INSERT INTO `praise` VALUES ('77', '2', '1', '21', '2018-05-17 10:09:57');
INSERT INTO `praise` VALUES ('79', '2', '1', '15', '2018-05-17 10:10:00');
INSERT INTO `praise` VALUES ('80', '2', '1', '15', '2018-05-17 10:10:01');
INSERT INTO `praise` VALUES ('81', '2', '1', '15', '2018-05-17 10:10:01');
INSERT INTO `praise` VALUES ('82', '2', '1', '15', '2018-05-17 10:10:01');
INSERT INTO `praise` VALUES ('83', '2', '1', '15', '2018-05-17 10:10:02');
INSERT INTO `praise` VALUES ('84', '2', '1', '15', '2018-05-17 10:10:02');
INSERT INTO `praise` VALUES ('85', '2', '1', '15', '2018-05-17 10:10:02');
INSERT INTO `praise` VALUES ('86', '2', '1', '15', '2018-05-17 10:10:02');
INSERT INTO `praise` VALUES ('87', '2', '1', '15', '2018-05-17 10:10:02');
INSERT INTO `praise` VALUES ('88', '2', '1', '15', '2018-05-17 10:10:03');
INSERT INTO `praise` VALUES ('89', '2', '1', '15', '2018-05-17 10:10:03');
INSERT INTO `praise` VALUES ('90', '2', '1', '15', '2018-05-17 10:10:03');
INSERT INTO `praise` VALUES ('91', '2', '1', '15', '2018-05-17 10:10:03');
INSERT INTO `praise` VALUES ('92', '2', '1', '15', '2018-05-17 10:10:03');
INSERT INTO `praise` VALUES ('93', '2', '1', '15', '2018-05-17 10:10:04');
INSERT INTO `praise` VALUES ('94', '2', '1', '15', '2018-05-17 10:10:04');
INSERT INTO `praise` VALUES ('95', '2', '1', '15', '2018-05-17 10:10:04');
INSERT INTO `praise` VALUES ('96', '1', '1', '24', '2018-05-18 09:39:54');
INSERT INTO `praise` VALUES ('131', '2', '2', '54', '2018-06-27 16:32:41');
INSERT INTO `praise` VALUES ('98', '2', '1', '19', '2018-05-21 15:42:12');
INSERT INTO `praise` VALUES ('99', '2', '87', '22', '2018-05-21 17:36:33');
INSERT INTO `praise` VALUES ('101', '2', '87', '2', '2018-05-21 17:58:37');
INSERT INTO `praise` VALUES ('102', '2', '87', '1', '2018-05-21 17:59:05');
INSERT INTO `praise` VALUES ('103', '2', '87', '13', '2018-05-21 18:12:54');
INSERT INTO `praise` VALUES ('104', '2', '87', '3', '2018-05-22 09:42:00');
INSERT INTO `praise` VALUES ('105', '2', '1', '13', '2018-05-23 14:18:40');
INSERT INTO `praise` VALUES ('106', '2', '1', '27', '2018-05-23 14:18:55');
INSERT INTO `praise` VALUES ('107', '2', '1', '27', '2018-05-23 14:18:56');
INSERT INTO `praise` VALUES ('108', '2', '1', '27', '2018-05-23 14:18:57');
INSERT INTO `praise` VALUES ('109', '2', '1', '27', '2018-05-23 14:18:58');
INSERT INTO `praise` VALUES ('110', '2', '1', '27', '2018-05-23 14:18:59');
INSERT INTO `praise` VALUES ('111', '2', '1', '27', '2018-05-23 14:19:00');
INSERT INTO `praise` VALUES ('112', '1', '1', '28', '2018-05-23 14:27:11');
INSERT INTO `praise` VALUES ('113', '4', '2', '25', '2018-05-23 14:32:58');
INSERT INTO `praise` VALUES ('114', '1', '2', '29', '2018-05-23 14:51:15');
INSERT INTO `praise` VALUES ('115', '2', '2', '1', '2018-05-23 14:52:20');
INSERT INTO `praise` VALUES ('116', '2', '2', '4', '2018-05-23 14:53:54');
INSERT INTO `praise` VALUES ('117', '1', '89', '9', '2018-05-23 15:53:43');
INSERT INTO `praise` VALUES ('118', '1', '89', '28', '2018-05-23 15:54:00');
INSERT INTO `praise` VALUES ('119', '2', '89', '11', '2018-05-23 15:56:41');
INSERT INTO `praise` VALUES ('120', '2', '89', '1', '2018-05-23 15:57:28');
INSERT INTO `praise` VALUES ('129', '2', '1', '9', '2018-06-20 09:33:47');
INSERT INTO `praise` VALUES ('122', '2', '2', '9', '2018-05-23 16:14:22');
INSERT INTO `praise` VALUES ('124', '1', '90', '32', '2018-05-29 14:59:24');
INSERT INTO `praise` VALUES ('125', '1', '91', '33', '2018-05-30 16:01:56');
INSERT INTO `praise` VALUES ('130', '2', '1', '55', '2018-06-22 17:43:19');

-- ----------------------------
-- Table structure for review
-- ----------------------------
DROP TABLE IF EXISTS `review`;
CREATE TABLE `review` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(10) DEFAULT '0' COMMENT '针对评论表的评论ID',
  `mid` int(10) DEFAULT '0' COMMENT '评论人',
  `content` text COMMENT '评论内容',
  `createTime` datetime DEFAULT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='对评论者评论';

-- ----------------------------
-- Records of review
-- ----------------------------
INSERT INTO `review` VALUES ('1', '14', '1', 'fdsfdsfdsfdsfdsf', '2018-05-10 18:56:19');
INSERT INTO `review` VALUES ('2', '18', '18', '恩快捷键', '2018-05-11 09:34:55');
INSERT INTO `review` VALUES ('3', '9', '1', '<img src=\'/public/home/images/face/18.gif\' />', '2018-05-11 10:25:26');
INSERT INTO `review` VALUES ('4', '11', '1', '<img src=\'/public/home/images/face/22.gif\' />', '2018-05-11 10:26:41');
INSERT INTO `review` VALUES ('5', '5', '1', '测试', '2018-05-11 13:49:59');
INSERT INTO `review` VALUES ('6', '20', '1', '123123', '2018-05-11 17:40:50');
INSERT INTO `review` VALUES ('7', '22', '1', '123', '2018-05-14 09:27:28');
INSERT INTO `review` VALUES ('8', '22', '1', '456799', '2018-05-14 09:27:58');
INSERT INTO `review` VALUES ('9', '25', '1', 'xxxx', '2018-05-16 17:26:43');
INSERT INTO `review` VALUES ('10', '13', '1', '嘎嘎', '2018-05-21 15:48:45');
INSERT INTO `review` VALUES ('11', '25', '2', '<img src=\'/public/home/images/face/30.gif\' />', '2018-05-23 14:33:13');
INSERT INTO `review` VALUES ('12', '13', '2', '<img src=\'/public/home/images/face/54.gif\' />', '2018-05-23 14:51:40');
INSERT INTO `review` VALUES ('13', '20', '90', '<img src=\'/public/home/images/face/18.gif\' />', '2018-05-29 14:52:08');
INSERT INTO `review` VALUES ('14', '33', '91', '<img src=\'/public/home/images/face/27.gif\' />', '2018-05-30 16:02:06');
INSERT INTO `review` VALUES ('15', '21', '2', '<img src=\'/public/home/images/face/38.gif\' />', '2018-06-11 17:18:33');

-- ----------------------------
-- Table structure for signup
-- ----------------------------
DROP TABLE IF EXISTS `signup`;
CREATE TABLE `signup` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `aid` int(10) DEFAULT '0' COMMENT '关联公益招募ID',
  `mid` int(10) DEFAULT '0' COMMENT '报名人',
  `signature` varchar(100) DEFAULT NULL COMMENT '手动签名(图片)',
  `status` tinyint(1) DEFAULT '0' COMMENT '是否审核',
  `createTime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='活动报名';

-- ----------------------------
-- Records of signup
-- ----------------------------
INSERT INTO `signup` VALUES ('5', '2', '1', 'upload/activity/signature/789fc37802d6de4a1ac5d036c51225ee.png', '1', '2018-05-03 16:47:39');
INSERT INTO `signup` VALUES ('6', '5', '1', 'upload/activity/signature/785e7fc2d799bc7ec42e54044f91d18b.png', '1', '2018-05-04 13:14:40');
INSERT INTO `signup` VALUES ('7', '5', '4', 'upload/activity/signature/044632260a60db984d52cf9bf2150e1a.png', '1', '2018-05-04 16:29:22');
INSERT INTO `signup` VALUES ('8', '6', '4', 'upload/activity/signature/044632260a60db984d52cf9bf2150e1a.png', '1', '2018-05-02 16:30:32');
INSERT INTO `signup` VALUES ('9', '6', '1', 'upload/activity/signature/789fc37802d6de4a1ac5d036c51225ee.png', '1', '2018-04-30 16:32:05');
INSERT INTO `signup` VALUES ('10', '4', '1', 'upload/activity/signature/785e7fc2d799bc7ec42e54044f91d18b.png', '1', '2018-05-01 16:59:06');
INSERT INTO `signup` VALUES ('11', '4', '4', 'upload/activity/signature/044632260a60db984d52cf9bf2150e1a.png', '1', '2018-04-30 16:59:38');
INSERT INTO `signup` VALUES ('28', '15', '103', null, '1', '2018-06-20 10:56:48');
INSERT INTO `signup` VALUES ('15', '13', '18', 'upload/activity/signature/a48d547ebacb6c351d0492a70366d7c4.png', '0', '2018-05-09 11:51:41');
INSERT INTO `signup` VALUES ('16', '13', '1', 'upload/activity/signature/158489c77cebcf4704f6e8767ccbfc48.png', '0', '2018-05-10 17:00:01');
INSERT INTO `signup` VALUES ('26', '15', '1', null, '1', '2018-06-11 10:26:35');
INSERT INTO `signup` VALUES ('27', '15', '4', null, '1', '2018-06-11 10:26:35');
INSERT INTO `signup` VALUES ('24', '11', '91', 'upload/activity/signature/b5a69c5d90551be95ed817d06a711ab6.png', '1', '2018-05-29 17:10:51');
INSERT INTO `signup` VALUES ('25', '8', '91', 'upload/activity/signature/6397e9f71deb7a923e1d4d9d731d45fa.png', '1', '2018-05-30 09:36:33');

-- ----------------------------
-- Table structure for slide
-- ----------------------------
DROP TABLE IF EXISTS `slide`;
CREATE TABLE `slide` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `pic` varchar(100) DEFAULT NULL COMMENT '图片',
  `content` text COMMENT '内容',
  `ord` int(11) DEFAULT NULL COMMENT '排序',
  `createTime` datetime DEFAULT NULL COMMENT '发表时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='轮播图新闻';

-- ----------------------------
-- Records of slide
-- ----------------------------
INSERT INTO `slide` VALUES ('4', '优秀运动员全民健身志愿服务活动走进宜宾', 'upload/banner/20180531582.jpg', '<p>\r\n	4月15日至16日，由国家体育总局人力资源开发中心组织的优秀运动员全民健身志愿服务活动走进了四川省宜宾市。此次活动吸引了2004年雅典奥运会女排冠军宋妮娜、1996年亚特兰大奥运会女足亚军温利蓉、原女篮国手许诺、原北京国安球员魏占奎、原赛艇运动员徐蕊以及CBA四川金强篮球俱乐部品胜男篮陈晓东和杨越的积极参与。\r\n</p>\r\n<p>\r\n	优秀运动员走进宜宾三中开展全民健身志愿服务<br />\r\n&nbsp;<br />\r\n走进中学 激发体育热情\r\n</p>\r\n<p>\r\n	陈晓东和杨越向宜宾三中女子篮球队传授投篮技巧<br />\r\n&nbsp;&nbsp;&nbsp; “刚刚我们进行了投篮练习，下面再来一个简单的对抗，大家一起打打球。”在宜宾三中，篮球运动员许诺、陈晓东和杨越在和学生在进行投篮练习后，召集校篮球队的队员进行了一场三人篮球对抗赛。赛后，高一学生母红洪十分兴奋：“我练习篮球差不多有四年的时间了，非常喜欢打球时的豪迈感觉。今天的交流时间很短，一开始我也挺紧张的，后来因为哥哥姐姐都很亲和，会纠正我们一些基本动作，也慢慢放松下来了。还见到他们做了一些之前只能在电视上才能看到的动作，坚定了我以后要继续刻苦练习的信心。”\r\n</p>\r\n<p>\r\n	魏占奎和学生交流足球技术<br />\r\n&nbsp;&nbsp;&nbsp; 同时，温丽蓉、魏占奎、宋妮娜也为足球和排球的队员进行了指导。魏占奎说，除了要告诉他们基本功的重要性，最想传递给他们的是体育精神，“我希望更快、更高、更强的体育精神传递不仅体现在体育竞技场上，还能出现在他们的日常生活中。现在生活和生存的压力都很大，一个团队或是个人想要进步，想要取得好成绩，就是要有一股拼劲才能成功。”<br />\r\n&nbsp;<br />\r\n走进小学 感受运动乐趣<br />\r\n&nbsp;&nbsp; <br />\r\n&nbsp;&nbsp;&nbsp; 中学生已经具备了一定的运动能力，可以进行一些对抗练习和技术指导，但是在小学，与同学们进行更多的是交流和互动，运动员们耐心回答小学生们提前准备的问题，告诉他们要“多运动不挑食，以后才能长高个儿。”和同学们比拼跳绳技能，徐蕊一边比一边说:“你们原来都跳得这么好，还会双摇和单编。” 宜宾市人民路小学副校长鲁能校区执行校长说：“非常开心运动员们能到学校和学生进行互动，激发他们的运动热情。”\r\n</p>\r\n<p>\r\n	宋妮娜和小朋友一起跳绳\r\n</p>\r\n<p>\r\n	徐蕊和小学生打乒乓球<br />\r\n&nbsp;&nbsp;&nbsp; 在与小学生进行篮球运球练习后，当体育老师问想看运动员做什么动作的时候，小学生们异口同声地说扣篮，但运动员没有选择直接扣篮，最后陈晓东抱起了一个男生让他真真切切地感受到了扣篮的感觉。\r\n</p>\r\n<p>\r\n	陈晓东托举起孩子扣篮<br />\r\n&nbsp;<br />\r\n&nbsp;&nbsp;&nbsp; “之前也参加过类似的公益活动，在和不同年级的同学互动时也有不一样的感受，但孩子们是真的热爱篮球这项运动，中学的同学需要一些更专业的教练去指导他们。在学生阶段，体育更多的是作为强健体魄的一种方式，运动使人开心，运动更会让人健康。”陈晓东说。<br />\r\n&nbsp;<br />\r\n走近市民 传播体育精神<br />\r\n&nbsp;&nbsp; <br />\r\n&nbsp;&nbsp;&nbsp; 优秀运动员不仅走访了五所中小学，还领跑了2018年国家森林城市马拉松系列赛蜀南竹海站比赛，和宜宾的市民一起运动。\r\n</p>\r\n<p>\r\n	优秀运动员领跑马拉松赛事<br />\r\n&nbsp;&nbsp;&nbsp; 宜宾市体育和教育局副局长肖安祥说：“宜宾在篮球和排球项目上拥有良好的基础，现在也加大了校园足球的推广和培训力度。三大球等集体项目非常有助于孩子的全面发展，邀请优秀运动员志愿者来宜宾，不仅可以从技术对他们进行指导，更重要的是给他们传递体育精神，让他们有一个新的提高。”<br />\r\n&nbsp;&nbsp;&nbsp; 国家体育总局人力中心志愿服务管理部主任李立东认为，国家体育总局开展优秀运动员全民健身志愿服务活动主要的目的是通过优秀运动员引领带动作用促进全民健身，弘扬中华体育精神、奥林匹克精神，传播体育文化。优秀运动员走进学校，通过自身的个人价值去推动学校运动的发展，不仅对小运动发挥榜样作用，而且还可以促进他们德智体全面发展。<br />\r\n&nbsp;&nbsp;&nbsp; 李立东说，优秀运动员志愿服务还将到更多的地方开展活动，不断完善志愿服务项目建设，进一步志愿服务与地方全民健身项目的深度融合，探索如何更好地将运动员身上的体育精神通过志愿服务的形式展现和传递给更多的人，让他们感受到中华体育精神的魅力。”（转自《中国体育报》）\r\n</p>', '0', '2018-05-31 14:27:20');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `region` varchar(5) DEFAULT NULL,
  `nickname` varchar(20) DEFAULT NULL COMMENT '用户名',
  `realname` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `portraitUri` varchar(100) DEFAULT NULL COMMENT '头像',
  `phone` varchar(20) DEFAULT NULL COMMENT '手机',
  `tel` varchar(20) DEFAULT NULL,
  `passwordHash` char(40) DEFAULT NULL,
  `passwordSalt` char(4) DEFAULT NULL,
  `rongCloudToken` varchar(256) DEFAULT '',
  `groupCount` int(10) unsigned DEFAULT '0',
  `level` tinyint(1) DEFAULT '0' COMMENT '0.普通会员1.志愿者2.记者',
  `integral` int(10) DEFAULT '0' COMMENT '积分',
  `status` tinyint(1) DEFAULT '0' COMMENT '0.未审核1.已审核',
  `activation` tinyint(1) DEFAULT '0' COMMENT '是否激活0.1',
  `nation` varchar(20) DEFAULT NULL COMMENT '民族',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `identificationNumber` varchar(30) DEFAULT NULL COMMENT '身份证号',
  `sex` tinyint(1) DEFAULT '0' COMMENT '性别',
  `job` varchar(50) DEFAULT NULL COMMENT '职业',
  `inviteCode` varchar(10) DEFAULT NULL COMMENT '邀请码',
  `mediaCompany` varchar(100) DEFAULT NULL COMMENT '新闻媒体公司',
  `sportEvent` varchar(50) DEFAULT NULL COMMENT '运动项目',
  `sportTeam` varchar(50) DEFAULT NULL COMMENT '运动队',
  `sportPrize` varchar(50) DEFAULT NULL COMMENT '运动奖项',
  `birth` date DEFAULT NULL,
  `birthAddress` varchar(100) DEFAULT NULL COMMENT '出生地',
  `liveAddress` varchar(100) DEFAULT NULL COMMENT '居住地',
  `contactAddress` varchar(100) DEFAULT NULL COMMENT '通讯地址',
  `timestamp` bigint(20) DEFAULT '0',
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8 COMMENT='会员与WEBIM公用一个表';

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', '86', '都市浪人', '孙国梁', 'http://101.37.107.121/upload/member/avatar/d6abc431469e2a91035e82c81727bb20.jpeg', '15942461399', '', 'e1c7ca7f434fe2dfc5b603426d55cb8de55fcf55', '1476', 'driJ59GQUsndIDZjQH2sVuuZTqQY4XYTCV98pMrzdPnFHgvH7WMmRSpYrQFtyz3eTcSQ42t/app6ygUYwz/jGyNcY1o0d3df', '0', '1', '1198', '1', '1', '汉', '44189483@qq.com', '231084798507213718', '0', null, 'MK08C79', null, '田径', '国家田径队', '1000', null, '牡丹江', '大连', '黑龙江', '1524970380064', '2018-05-04 10:33:28', '2018-05-28 16:08:37', null);
INSERT INTO `users` VALUES ('2', '86', '比利', '北方晚报', 'http://101.37.107.121/upload/member/avatar/20180504657.jpg', '13889467368', '', 'e1c7ca7f434fe2dfc5b603426d55cb8de55fcf55', '1476', 'q510sG87ke0/hRk1BOETLveQGS9m+dAE830wJJ48kFhRww/WmlFlTEtgg83yT1DhrhZPH3MqQp/kejah4VvLICZMVjHmp9QN', '0', '2', '1130', '1', '1', '', '', '', '0', null, null, '北方晚报', null, null, null, null, '大连', '', '', '1524970614174', '2018-05-08 18:15:24', '2018-04-29 10:58:08', null);
INSERT INTO `users` VALUES ('3', '86', 'lili', '刘丽', 'http://101.37.107.121/upload/member/avatar/2018050410.jpg', '18842606495', null, 'e1c7ca7f434fe2dfc5b603426d55cb8de55fcf55', '1476', '', '0', '0', '1000', '1', '1', '', '840795395@qq.com', '23108876921313122x', '0', null, null, null, null, null, null, null, '', '', '', '0', '2018-05-04 10:37:02', null, null);
INSERT INTO `users` VALUES ('4', '86', '铁锤', '王钢蛋', 'http://101.37.107.121/upload/member/avatar/201805041.jpg', '18940445237', null, 'e1c7ca7f434fe2dfc5b603426d55cb8de55fcf55', '1476', 'e02LLbWjJ57xEpesEwZrkPeQGS9m+dAE830wJJ48kFi6RTcKnAoZCRnSgP42DVghaIRp21dh3v3s2Lz1yAMS/CZMVjHmp9QN', '0', '1', '120', '1', '1', '', '', '', '0', null, 'MK00C79', null, null, null, null, null, '', '', '', '0', '2018-05-04 16:38:54', '2018-05-21 13:41:43', null);
INSERT INTO `users` VALUES ('18', '86', '12138', null, 'http://101.37.107.121/upload/member/avatar/8d97e39cf987ac712cb1e12cb8db60d2.jpeg', null, null, 'e1c7ca7f434fe2dfc5b603426d55cb8de55fcf55', '1476', 'Kbo0vp9tpQcDAy3zRjTNOPeQGS9m+dAE830wJJ48kFhb7GY1qXxyZg3SdWwORb2wj9tfpfGzLC4mTFYx5qfUDQ==', '0', '1', '10', '1', '1', null, null, null, '0', null, 'XG15XUOA', null, null, null, null, null, null, null, null, '0', '2018-05-08 14:12:35', null, null);
INSERT INTO `users` VALUES ('21', '86', '12136', null, 'http://101.37.107.121/upload/member/avatar/af8ff3629e051b1bfbac868533d62ce0.jpeg', null, null, 'e1c7ca7f434fe2dfc5b603426d55cb8de55fcf55', '1476', '', '0', '0', '0', '1', '1', null, null, null, '0', null, null, null, null, null, null, null, null, null, null, '0', '2018-05-08 14:49:31', null, null);
INSERT INTO `users` VALUES ('22', '86', null, '我', 'http://101.37.107.121/upload/member/avatar/20180508721.jpg', '13940986773', null, 'e1c7ca7f434fe2dfc5b603426d55cb8de55fcf55', '1476', 'm85sadn8AuU5gCJ7tEGoufeQGS9m+dAE830wJJ48kFhb7GY1qXxyZiWYB22MtVFFh3vMlKvHHxImTFYx5qfUDQ==', '0', '2', '0', '1', '1', '', '', '', '0', null, null, null, null, null, null, null, '', '', '', '0', '2018-05-08 15:14:26', null, null);
INSERT INTO `users` VALUES ('23', '86', null, '12139', null, '13644266240', null, 'e1c7ca7f434fe2dfc5b603426d55cb8de55fcf55', '1476', 'tBV3K8pmz0rZnHPXhfGpJPeQGS9m+dAE830wJJ48kFhb7GY1qXxyZgTuzhUYlQJfecA4U1yU1c8mTFYx5qfUDQ==', '0', '2', '0', '1', '1', '', '421104619@qq.com', '222555222222225555', '0', null, null, null, null, null, null, null, '', '', '', '0', '2018-05-08 15:53:11', null, null);
INSERT INTO `users` VALUES ('25', null, null, 'zxt2', null, '13188419396', null, 'e1c7ca7f434fe2dfc5b603426d55cb8de55fcf55', '1476', '', '0', '0', '10', '1', '1', '汉', '', '', '0', null, null, null, null, null, null, null, '', '', '', '0', '2018-05-08 15:11:13', null, null);
INSERT INTO `users` VALUES ('26', '86', 'zxt1', null, 'http://101.37.107.121/upload/member/avatar/3b75a2c0111ce6545396a3178b545c1a.jpeg', null, null, 'e1c7ca7f434fe2dfc5b603426d55cb8de55fcf55', '1476', '', '0', '0', '0', '0', '1', null, null, null, '0', null, null, null, null, null, null, null, null, null, null, '0', '2018-05-10 10:20:25', null, null);
INSERT INTO `users` VALUES ('78', null, null, '丛南', '', '18640886811', null, '2cf7e0eef5eda1b84d1f22b5effcaa1942c9db8c', '6964', 'F97sYiaLcLYUrDmpd2mZgPeQGS9m+dAE830wJJ48kFjjerKbNyv33emxn7WcFk6bAEUFn6vid3QmTFYx5qfUDQ==', '0', '1', '10500', '1', '1', '汉', '840795395@qq.com', '222405193407261811', '0', null, 'KSGLGW0X', null, '跑步', '田径队', '1000', null, '黑龙江', '大连', '南极', '0', '0000-00-00 00:00:00', null, null);
INSERT INTO `users` VALUES ('79', null, null, '丛南1', null, '18640886812', null, 'e1c7ca7f434fe2dfc5b603426d55cb8de55fcf55', '1476', '', '0', '1', '8001', '1', '1', '汉', '', '222405193407261812', '0', null, 'XG150UOB', null, null, null, null, null, '黑龙江', '大连', '南极', '0', '0000-00-00 00:00:00', null, null);
INSERT INTO `users` VALUES ('80', null, null, '丛南2', null, '18640886813', null, 'e1c7ca7f434fe2dfc5b603426d55cb8de55fcf55', '1476', '', '0', '1', '8002', '1', '1', '汉', '', '222405193407261823', '0', null, 'AAICIOLX', null, null, null, null, null, '黑龙江', '大连', '南极', '0', '0000-00-00 00:00:00', null, null);
INSERT INTO `users` VALUES ('81', null, null, '丛南3', null, '18640886814', null, 'e1c7ca7f434fe2dfc5b603426d55cb8de55fcf55', '1476', '', '0', '1', '8003', '1', '1', '汉', '', '222405193407261834', '0', null, 'NK00C79', null, null, null, null, null, '黑龙江', '大连', '南极', '0', '0000-00-00 00:00:00', null, null);
INSERT INTO `users` VALUES ('82', null, null, '丛南4', null, '18640886815', null, 'e1c7ca7f434fe2dfc5b603426d55cb8de55fcf55', '1476', '', '0', '1', '8004', '1', '1', '汉', '', '222405193407261854', '0', null, 'PO8ZXDWS', null, null, null, null, null, '黑龙江', '大连', '南极', '0', '0000-00-00 00:00:00', null, null);
INSERT INTO `users` VALUES ('83', null, null, '丛南5', 'http://101.37.107.121/upload/member/avatar/48b63409272b40518cf19f9f75b47fed.jpeg', '18640886816', null, '02e9c5e89dbcdcb91a42f55479a8829fe68e3d25', '5977', 'rv4G3L5RhRNYdIlCZ4d7E+uZTqQY4XYTCV98pMrzdPnFHgvH7WMmReHPJ0xKHfybEGbV5/m+0JUoI521eZMQSA==', '0', '1', '8005', '1', '1', '汉', '', '222405193407261658', '0', null, 'AAIZIOLX', null, null, null, null, null, '黑龙江', '大连', '南极', '0', '0000-00-00 00:00:00', null, null);
INSERT INTO `users` VALUES ('84', null, null, '丛南6', null, '18640886817', null, 'e1c7ca7f434fe2dfc5b603426d55cb8de55fcf55', '1476', '', '0', '1', '8006', '1', '1', '汉', '', '222405193407261895', '0', null, 'MKI0C79', null, null, null, null, null, '黑龙江', '大连', '南极', '0', '0000-00-00 00:00:00', null, null);
INSERT INTO `users` VALUES ('85', null, null, '丛南7', null, '18640886818', null, 'e1c7ca7f434fe2dfc5b603426d55cb8de55fcf55', '1476', '', '0', '1', '8007', '1', '1', '汉', '', '222405193407261658', '0', null, 'QO8ZXDWS', null, null, null, null, null, '黑龙江', '大连', '南极', '0', '0000-00-00 00:00:00', null, null);
INSERT INTO `users` VALUES ('86', '86', null, '丛南8', 'http://101.37.107.121/upload/member/avatar/fdad080202cb4bc74c5391c33fc0fb7a.jpeg', '18640886819', null, 'ec426b8b99825db7e28d58601a2285c2260ff73d', '9012', '+/v4w8G+uKl287CIf3B2j1fFp5OBL33Osc2uau+nXgYpCsqtvkYcN4z2sRJaaXQjUOJwwKTjdP0=', '0', '1', '8508', '1', '1', '汉', '421104619@qq.com', '222405193407261823', '0', null, 'CIACAP23', '', '', '', '', null, '黑龙江', '大连', '南极', '0', '2018-05-15 09:40:22', null, null);
INSERT INTO `users` VALUES ('87', '86', '普通会员', null, 'http://101.37.107.121/upload/member/avatar/81425f1fdb23040b4dede84f84808ba6.jpeg', '', null, 'e9f848d98a1c16dcf77114768669af62916db682', '1508', '', '0', '0', '10', '1', '1', '', '1084271784@qq.com', '231084198807213470', '0', null, null, null, null, null, null, null, '', '', '', '0', '2018-05-21 15:54:50', null, null);
INSERT INTO `users` VALUES ('88', '86', 'spox', null, 'http://101.37.107.121/upload/member/avatar/4a07388295d11c525604547325dada41.jpeg', '', null, '6fa23426fc634a46edf8d4b5acdda927f282db94', '2279', '', '0', '0', '0', '0', '1', '汉', '', '', '0', null, null, null, null, null, null, null, '', '', '', '0', '2018-05-23 09:56:53', null, null);
INSERT INTO `users` VALUES ('89', '86', 'spo', null, 'http://101.37.107.121/upload/member/avatar/56b6a1c5e9ff64d92be024b109965e42.jpeg', '', null, '53f76652c431f0fe5b1344297540fd24a22711c2', '2766', '', '0', '0', '60', '1', '1', '汗', '', '', '0', null, null, null, null, null, null, null, '呼兰', '', '大连', '0', '2018-05-23 15:17:11', null, null);
INSERT INTO `users` VALUES ('91', '86', 'test', null, 'http://101.37.107.121/upload/member/avatar/382ffe2fed6d94b5d5383d3bf5167aa6.jpeg', '13889467369', null, '3e92593848e262c9ce4a803871ef1990eedc4749', '7922', '8OOAKfAMlbh3eqNIrCa6YveQGS9m+dAE830wJJ48kFhreGnrWj62Kcr3t/XJe/ECkFaFGWc+gmmval9its+ENCZMVjHmp9QN', '0', '1', '1015', '1', '1', null, null, null, '0', null, 'QO3ZXDWS', null, null, null, null, null, null, null, null, '0', '2018-05-29 15:06:51', '2018-05-30 16:58:43', null);
INSERT INTO `users` VALUES ('92', '86', null, '人民网', 'http://101.37.107.121/upload/member/avatar/20180531831.jpg', '13940986776', null, 'd9dfdb4f24eb97e2034a6a25e34db45c4d6a66d8', '2915', 'b9QsSoXYaO8L6pe3H66kR/eQGS9m+dAE830wJJ48kFhreGnrWj62KYpskK20zhUZi5CVqgDKq0kmTFYx5qfUDQ==', '0', '2', '0', '1', '1', '', '123456789@163.com', '211211123412345625', '0', null, null, '', '', '', '', null, '', '', '', '0', '2018-05-31 15:24:10', null, null);
INSERT INTO `users` VALUES ('93', '86', null, '新浪体育', 'http://101.37.107.121/upload/member/avatar/20180531742.jpg', '13940986774', null, '4be407faeb4d6c07b8271b8b16be595e3d4dd0e1', '4807', '+mLdpv7xzlDIdxgDFvLe//eQGS9m+dAE830wJJ48kFhreGnrWj62Kbkx80YksaIfwKYQUJBrmVMmTFYx5qfUDQ==', '0', '2', '0', '1', '1', '', '123456@163.com', '200201213010242013', '0', null, null, '新浪体育', '', '', '', null, '', '', '', '0', '2018-05-31 15:03:19', null, null);
INSERT INTO `users` VALUES ('94', '86', null, '人民日报', 'http://101.37.107.121/upload/member/avatar/20180531194.jpg', '13940986775', null, 'd67e6d180e983c7866cbe0e460d6820c11cf6e38', '1677', 'GT8MBUIXc8xNJkyaJlppnFfFp5OBL33Osc2uau+nXgZrDjMqU7LEH9NQux7jOWpX9KsMHqOXSNs=', '0', '2', '0', '1', '1', '', '123456546@163.com', '202202125202314201', '0', null, null, '人民日报', '', '', '', null, '', '', '', '0', '2018-05-31 15:03:30', null, null);
INSERT INTO `users` VALUES ('95', '86', null, '徐蕊', null, '18652217252', null, '00abaf254ed02c47253dba90a9fb1b3e751dbd76', '6177', 'K30pTS51myp04g7vLMUbQlfFp5OBL33Osc2uau+nXgbvfeE8S0S3zTlX+jsVyR4rKHy5T+84d7g=', '0', '1', '15', '1', '1', '', '', '320324199108186242', '0', null, 'AAIZIOLY', '', '赛艇', '', '', null, '江苏', '北京', '', '0', '2018-06-05 08:44:12', null, null);
INSERT INTO `users` VALUES ('96', '86', 'dada', null, 'http://101.37.107.121/upload/member/avatar/78e72d9752fd308200752f32ca3ebe2e.jpeg', null, null, '7f65d88c82f4315307474d91f370444856bb1aab', '7140', 'QI8Bj9NiQY5TeAq+801f5uuZTqQY4XYTCV98pMrzdPnFHgvH7WMmRSEfmtvFwdwwjWJ+qxdk0SEoI521eZMQSA==', '0', '1', '30', '1', '1', null, null, null, '0', null, 'AQ64TMKD', null, null, null, null, null, null, null, null, '0', '2018-06-06 16:13:24', null, null);
INSERT INTO `users` VALUES ('97', '86', 'ASDF', null, 'http://101.37.107.121/upload/member/avatar/8953e400cd0304047d65b279ef67a57e.jpeg', null, null, 'd9c5e5beb0cf45e3574b53bb29710faaaea2b0c7', '7057', 'v27F0fW3/gi22b8gRckwTfeQGS9m+dAE830wJJ48kFjjerKbNyv33XAXjsQrrhnpBKMSSfBJhpomTFYx5qfUDQ==', '0', '1', '0', '1', '1', null, null, null, '0', null, '95K0RE6G', null, null, null, null, null, null, null, null, '0', '2018-06-06 16:23:29', null, null);
INSERT INTO `users` VALUES ('101', '86', null, 'zxt', '', '15694267473', null, 'fc20a94f346a5ebe01f367ae4730b19ef5e10f30', '4887', 'zYWZ8tojmaPNlJoQ1wncHPeQGS9m+dAE830wJJ48kFjNtYJZACbuzrOPVulqzoP5xL2VY0GCgCkmTFYx5qfUDQ==', '0', '2', '0', '1', '1', '', '', '111111111111111111', '0', null, '5INELAEK', '', '', '', '', null, '', '', '', '0', '2018-06-15 17:14:30', null, null);
INSERT INTO `users` VALUES ('102', '86', 'spoo', null, 'http://101.37.107.121/upload/member/avatar/ac91af718726a691571a178a9ea2cf8f.jpeg', null, null, '51901fd6c8ccd55c38899589119a794000be54ab', '7147', 'HgNKmBsm29cyCFVDRTuUlOuZTqQY4XYTCV98pMrzdPnFHgvH7WMmRSsIKUC7fCyDfR612WDe/3hLLZDxcm+/Og==', '0', '1', '0', '1', '1', null, null, null, '0', null, '73TUCHO1', null, null, null, null, null, null, null, null, '0', '2018-06-15 17:07:31', null, null);
INSERT INTO `users` VALUES ('103', '86', 'zxt', 'zxc', 'http://101.37.107.121/upload/member/avatar/20180620728.jpg', '15694267472', null, '7b7386d023a38c66d284f2076460caad32fda30d', '1229', 'XeQJqPG2S6oR3z+S70d44veQGS9m+dAE830wJJ48kFjNtYJZACbuzlYzGWgGTVTVmv9Yvq8SeAUmTFYx5qfUDQ==', '0', '1', '1815', '1', '1', '', '', '', '0', null, 'BI0O8T4U', '', '', '', '', null, '', '', '', '0', '2018-06-20 10:18:09', null, null);

-- ----------------------------
-- Table structure for users_collect
-- ----------------------------
DROP TABLE IF EXISTS `users_collect`;
CREATE TABLE `users_collect` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT '0' COMMENT '收藏:1.用户发表2公益活动',
  `mid` int(10) DEFAULT '0' COMMENT '会员ID',
  `aid` int(10) DEFAULT '0' COMMENT '文章收藏ID',
  `createTime` datetime DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users_collect
-- ----------------------------
INSERT INTO `users_collect` VALUES ('16', '2', '1', '7', '2018-05-11 19:04:58');
INSERT INTO `users_collect` VALUES ('52', '1', '1', '9', '2018-05-17 10:09:13');
INSERT INTO `users_collect` VALUES ('21', '2', '1', '5', '2018-05-13 15:29:57');
INSERT INTO `users_collect` VALUES ('23', '1', '1', '5', '2018-05-14 10:59:46');
INSERT INTO `users_collect` VALUES ('61', '1', '1', '10', '2018-05-28 17:46:44');
INSERT INTO `users_collect` VALUES ('26', '1', '1', '12', '2018-05-14 12:35:08');
INSERT INTO `users_collect` VALUES ('43', '1', '1', '13', '2018-05-15 09:53:13');
INSERT INTO `users_collect` VALUES ('34', '1', '86', '13', '2018-05-15 09:44:10');
INSERT INTO `users_collect` VALUES ('36', '1', '86', '12', '2018-05-15 09:45:36');
INSERT INTO `users_collect` VALUES ('38', '1', '86', '10', '2018-05-15 09:45:38');
INSERT INTO `users_collect` VALUES ('53', '1', '87', '2', '2018-05-21 17:37:08');
INSERT INTO `users_collect` VALUES ('54', '1', '87', '1', '2018-05-21 17:59:09');
INSERT INTO `users_collect` VALUES ('56', '1', '87', '13', '2018-05-21 18:16:02');
INSERT INTO `users_collect` VALUES ('57', '1', '2', '1', '2018-05-23 14:52:26');
INSERT INTO `users_collect` VALUES ('58', '1', '2', '4', '2018-05-23 14:53:57');
INSERT INTO `users_collect` VALUES ('59', '1', '89', '11', '2018-05-23 15:56:43');
INSERT INTO `users_collect` VALUES ('60', '1', '89', '1', '2018-05-23 15:57:34');
INSERT INTO `users_collect` VALUES ('62', '1', '1', '55', '2018-06-22 17:43:17');
INSERT INTO `users_collect` VALUES ('63', '1', '2', '54', '2018-06-27 16:32:39');

-- ----------------------------
-- Table structure for users_friend
-- ----------------------------
DROP TABLE IF EXISTS `users_friend`;
CREATE TABLE `users_friend` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mid` int(10) DEFAULT '0' COMMENT '会员ID',
  `fid` int(10) DEFAULT '0' COMMENT '朋友ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='好友';

-- ----------------------------
-- Records of users_friend
-- ----------------------------

-- ----------------------------
-- Table structure for users_integral
-- ----------------------------
DROP TABLE IF EXISTS `users_integral`;
CREATE TABLE `users_integral` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mid` int(10) DEFAULT '0' COMMENT '会员ID',
  `type` tinyint(1) DEFAULT '0' COMMENT '积分类型1.完善资料2.参加活动3.视频教程4.问卷调查5.分享资讯6.分享活动7.分享图集8.分享美文9.分享轮播10.分享公告11.分享在线视频',
  `cid` int(10) DEFAULT '0' COMMENT '对应关联ID',
  `content` varchar(100) DEFAULT NULL COMMENT '相关信息',
  `integral` int(10) DEFAULT '0' COMMENT '积分',
  `status` tinyint(1) DEFAULT '0' COMMENT '1获得2使用',
  `createTime` datetime DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='会员积分记录';

-- ----------------------------
-- Records of users_integral
-- ----------------------------
INSERT INTO `users_integral` VALUES ('6', '1', '3', '4', '观看排球教程使用20积分', '20', '2', '2018-05-08 13:44:58');
INSERT INTO `users_integral` VALUES ('7', '1', '3', '3', '观看sikana排球教程获得5积分', '5', '1', '2018-05-08 13:47:08');
INSERT INTO `users_integral` VALUES ('2', '1', '1', '0', '完善个人资料获得积分500', '500', '1', '2018-05-02 15:19:10');
INSERT INTO `users_integral` VALUES ('8', '2', '3', '5', '观看惨吞5弹五连败 曼萨诺帅位危矣获得20积分', '20', '1', '2018-05-08 15:04:18');
INSERT INTO `users_integral` VALUES ('9', '18', '3', '2', '观看免费开放“一年等一回”？市民期待更多体育公益场获得10积分', '10', '1', '2018-05-11 15:39:29');
INSERT INTO `users_integral` VALUES ('17', '1', '3', '6', '观看FANC VR体育舞蹈获得50积分', '50', '1', '2018-05-12 13:49:28');
INSERT INTO `users_integral` VALUES ('18', '1', '3', '8', '观看WM33预测 关于付费的一点感想获得80积分', '80', '1', '2018-05-13 18:33:52');
INSERT INTO `users_integral` VALUES ('19', '1', '3', '9', '观看外交官眼中的外交工作兼谈应对国际体育事务的能力——李肇星获得10积分', '10', '1', '2018-05-18 17:08:12');
INSERT INTO `users_integral` VALUES ('20', '87', '3', '9', '观看外交官眼中的外交工作兼谈应对国际体育事务的能力——李肇星获得10积分', '10', '1', '2018-05-21 16:31:52');
INSERT INTO `users_integral` VALUES ('21', '89', '3', '6', '观看FANC VR体育舞蹈获得50积分', '50', '1', '2018-05-23 16:08:04');
INSERT INTO `users_integral` VALUES ('22', '89', '3', '2', '观看免费开放“一年等一回”？市民期待更多体育公益场获得10积分', '10', '1', '2018-05-23 16:08:19');
INSERT INTO `users_integral` VALUES ('23', '2', '3', '9', '观看外交官眼中的外交工作兼谈应对国际体育事务的能力——李肇星获得10积分', '10', '1', '2018-05-23 16:15:40');
INSERT INTO `users_integral` VALUES ('24', '95', '3', '9', '观看外交官眼中的外交工作兼谈应对国际体育事务的能力——李肇星获得15积分', '15', '1', '2018-06-05 17:23:21');
INSERT INTO `users_integral` VALUES ('25', '78', '1', '0', '完善个人资料获得积分500', '500', '1', '2018-06-08 18:00:04');
INSERT INTO `users_integral` VALUES ('26', '96', '3', '9', '观看外交官眼中的外交工作兼谈应对国际体育事务的能力——李肇星获得15积分', '15', '1', '2018-06-11 11:53:14');
INSERT INTO `users_integral` VALUES ('27', '96', '4', '15', '参与问卷调查 体育锻炼问卷调查表 获得15积分', '15', '1', '2018-06-11 11:59:07');
INSERT INTO `users_integral` VALUES ('30', '2', '5', '49', '分享\"优秀运动员助力健康中国行—科学健身主题宣传活动启动仪式\"获得积分12', '12', '1', '2018-06-12 12:02:11');
INSERT INTO `users_integral` VALUES ('31', '103', '3', '9', '观看外交官眼中的外交工作兼谈应对国际体育事务的能力——李肇星获得15积分', '15', '1', '2018-06-20 09:46:45');
INSERT INTO `users_integral` VALUES ('32', '1', '5', '47', '分享\"人力中心优秀运动员全民健身志愿服务2018年工作要点\"获得积分12', '12', '1', '2018-06-20 09:56:36');

-- ----------------------------
-- Table structure for users_post
-- ----------------------------
DROP TABLE IF EXISTS `users_post`;
CREATE TABLE `users_post` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT '0' COMMENT '1文章2图片3视频',
  `title` varchar(50) DEFAULT NULL,
  `pic` varchar(100) DEFAULT NULL COMMENT '图片',
  `aid` int(10) DEFAULT '0' COMMENT '关联公益招募ID',
  `mid` int(10) DEFAULT '0' COMMENT '0为官方,大于0为会员',
  `content` varchar(2000) DEFAULT NULL COMMENT '内容',
  `praise` int(10) DEFAULT '0' COMMENT '被赞数',
  `status` tinyint(1) DEFAULT '0' COMMENT '是否审核',
  `createTime` datetime DEFAULT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8 COMMENT='会员发布资料';

-- ----------------------------
-- Records of users_post
-- ----------------------------
INSERT INTO `users_post` VALUES ('1', '1', '选择锻炼时间你做主', null, '6', '1', '<p style=\"margin-top:0px;margin-bottom:0px;color:#333333;font-family:verdana;font-size:14px;white-space:normal;background-color:#FFFFFF;\">\n	有人觉得选择健身的时间很随意，因为能一直坚持才是最重要。也有人觉得选择对的健身时间非常重要，毕竟既然已经选择健身了，选择一个合适的时间点才能体现对健身重视。而长期以来，大家对健身时间的争执无非是主要集中在两个时间段，早上和晚上。\n</p>\n<p style=\"margin-top:0px;margin-bottom:0px;color:#333333;font-family:verdana;font-size:14px;white-space:normal;background-color:#FFFFFF;\">\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 选择一早 一日之计在于晨<br />\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 一直坚持早上锻炼的柯文选择在早晨健身的原因有两个，一是因为他坚信一日之计在于晨，只有在早上的时候锻炼身体才能一天保持最佳的身体状态，二是因为早上锻炼的人比较少，晚上公园里面人很多。柯文说：“我一直坚持在早上锻炼，因为要上班的原因，所以出去锻炼的时间会比较早，夏天的时候大概五时半就出去了，那个时候人很少，可以活动的空间也非常大。我尝试过晚上去锻炼，七八点的时候整个公园都是人，连走路都要像过障碍一样。九点以后天太黑了，而且离公园的闭园时间也很近，就觉得时间安排会非常紧张。”<br />\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 坚持早上锻炼的柯文也为自己的晨练安排了很多项目，他说：“首先我会先进行慢走，将身体完全活动开，然后根据自己的状态选择跑步或是跳绳，有时候放松的时候也会和跳广场的大妈一起跳一会儿。总体来说，就觉得早上人真的比晚上少，然后干什么都很放松，不需要害怕走路的时候撞到别人，也不需要特意找一块空地跳绳。”人少似乎成为柯文选择晨练的最重要原因。\n</p>\n<p style=\"margin-top:0px;margin-bottom:0px;color:#333333;font-family:verdana;font-size:14px;white-space:normal;background-color:#FFFFFF;\">\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 选择一晚 锻炼身体助睡眠<br />\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 程烨就是一个“夜猫子”，她说：“我几乎没有在早上锻炼过，因为早上起不来，如果没有睡饱，我会觉得一天都没有精神的。”不能早起的程烨因此一直选择在晚上锻炼。<br />\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 还有一个原因是程烨选择在晚上锻炼，就是失眠。“我知道很多人觉得晚上运动反而容易失眠，周围的人也在我开始尝试的时候劝过我，不要再加重失眠。但是在我坚持了这么长时间，觉得失眠明显有了好转，所以觉得选择一项适合自己的运动，是非常有助于睡眠。”程烨觉得失眠是长期影响她的一个重要原因，没有好的睡眠质量，会严重影响第二天的工作和生活，从而进入恶性循环。<br />\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; “黄昏练”的人认为晨练不值得提倡，因为在凌晨4时到早上9时之间，二氧化碳反流，空气质量并不好。此外，对于患有高血压和心血管疾病的人而言，早晨人体的血液黏稠度比较高，不利身体健康，所以要根据自身身体情况选择锻炼时间。如果一定要早上锻炼，最好在锻炼前喝适量的水，以此来稀释血液，降低黏稠度。<br />\n　　<br />\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 坚持自己 锻炼时间很固定<br />\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 有人会选择早锻炼，有人会选择晚锻炼。在林雯看来选择一个固定的时间锻炼才最重要，她说：“我不是很相信什么早锻炼和晚锻炼对人身体有什么不同的好处或伤害，我觉得每个人的身体情况都不同，只有自己才是最了解的。我一般选择中午健身，因为在单位旁边新开了一家健身房，所以工作日的时候几乎天天都可以去健身。”坚持每天中午健身的林雯觉得自己的身体每到中午就会兴奋起来，这也是她最喜欢的状态。同时林雯觉得，很多人选择锻炼时间不过是为自己的不想运动找借口，真正想运动的人会积极选择适合自己的，是行动派而不是一直停留在理论上。<br />\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', '3', '1', '2018-05-04 16:49:21');
INSERT INTO `users_post` VALUES ('2', '2', '欢快的自行车', null, '6', '1', 'upload/member/pic/a30e35a6d9ca4dfb883c91b94f38616e.jpeg', '2', '1', '2018-05-04 16:50:39');
INSERT INTO `users_post` VALUES ('3', '2', '补水', null, '6', '1', 'upload/member/pic/20cdda252d4e4823d7e49cff69fefa98.jpeg', '1', '1', '2018-05-04 16:55:46');
INSERT INTO `users_post` VALUES ('4', '2', '越野', null, '4', '1', 'upload/member/pic/7821e2be1f691e9f2a5eacbe64c3aeba.jpeg', '1', '1', '2018-05-04 17:08:00');
INSERT INTO `users_post` VALUES ('5', '1', '22秒', null, '4', '1', '<p style=\"padding:5px 0px;margin-top:0px;margin-bottom:0px;white-space:normal;\">\n	体育场内的喧嚣声浪席卷我的全身。尽管久经训练，我依旧感觉到自己的肾上腺素在不断上升，满怀期待也让我心跳加速。我缓缓地做了一个深呼吸，让自己冷静下来。这次可不能跑砸了。我们站成一排，一台摄像机从我们面前扫过，在播音员向观众介绍我们的时候，镜头会在我们每一个人的脸上停留片刻。摄像机来到我的面前，人们的叫喊声越发响亮了。我冲着镜头挥舞双手，用笑容掩盖肾上腺素激增带来的紧张。这一刻是属于我的，这是我重显威风的机会。接下来的几秒钟，成败将见分晓。\n</p>\n<p style=\"padding:5px 0px;margin-top:0px;margin-bottom:0px;white-space:normal;\">\n	我们前面站着一位身穿紫色衬衣的年轻女性。她的神色有点纠结，既带有专业人士的冷静，又包含着一种在奥运会焦点赛事中遇到偶像时的快乐和雀跃。她招手示意我们上前，大家纷纷站在自己的起跑器旁边。对我来说，这是压力最大的时刻，此刻的我就像一个等着爬进机舱的伞兵。我现在还可以退出，我可以转身离开，走出赛场。而一旦我的双脚蹬上了起跑器，我就别无选择了。\n</p>\n<p style=\"padding:5px 0px;margin-top:0px;margin-bottom:0px;white-space:normal;\">\n	往往在还没正式开跑之前，比赛就已经在起跑线上决出了胜负。我必须集中注意力。我想象着接下来的比赛，把它的22秒压缩成4段，每段5秒：起跑，前100米，跑入直线跑道，最后冲刺。我的双腿到那时会发出痛苦的抗议，每一个对手都将与我不相上下。而到了最关键的冲刺阶段，我的脑海中必须紧紧抓住一种感觉————当我知道金牌将属于我的时候就会有这种感觉。\n</p>\n<p style=\"padding:5px 0px;margin-top:0px;margin-bottom:0px;white-space:normal;\">\n	“各就各位！”\n</p>', '0', '1', '2018-05-04 17:12:15');
INSERT INTO `users_post` VALUES ('6', '2', 'yyyy', null, '5', '1', 'upload/member/pic/83d7bd754b1752d4af59ccc1bc023d8b.jpeg', '1', '1', '2018-05-08 11:05:49');
INSERT INTO `users_post` VALUES ('7', '1', 'yyyyy', null, '5', '1', 'gngtntntntntngngn', '1', '1', '2018-05-08 11:06:40');
INSERT INTO `users_post` VALUES ('8', '1', '测试', null, '5', '1', '测试测试', '1', '1', '2018-05-08 13:39:49');
INSERT INTO `users_post` VALUES ('9', '1', '小学体育中如何有效应用体育游戏', null, '12', '1', '<p style=\"padding:10px;margin-top:0px;margin-bottom:0px;line-height:30px;white-space:normal;\">\n	摘要：微课是一种现代化的教学模式，在高中数学课堂教学中运用这种教学模式，教师不仅要转变传统的教学观念，对数学学科的学科特点及其教学规律有充分的认识和理解，还要以学生的实际学习情况为主，灵活运用微课的教学模式进行数学课堂实践教学，为学生营造一个自由宽松的学习环境，培养学生自主思考的能力，让学生在学习数学知识的过程中，发挥自身的主观能动性，从而不断提高数学学习的效率，进而提升高中数学教学的整体质量水平。\n</p>\n<p style=\"padding:10px;margin-top:0px;margin-bottom:0px;line-height:30px;white-space:normal;\">\n	关键词：高中数学；微课；教学模式\n</p>\n<p style=\"padding:10px;margin-top:0px;margin-bottom:0px;line-height:30px;white-space:normal;\">\n	教育改革使传统的教学模式无法与当前的教育发展要求相契合，因此，微课这种新型的教学模式应运而生。在高中数学教学中采用微课的教学模式，不但为高中数学教学开辟了一个新天地，还能通过引导学生发现问题的方式，培养学生的数学学习兴趣和独立思考能力，让学生主动参与到数学课堂教学活动中，进而提高学生的数学学习效率，增强数学教学的效果。\n</p>', '2', '1', '2018-05-08 14:44:09');
INSERT INTO `users_post` VALUES ('10', '2', '简单点', null, '12', '1', 'upload/member/pic/282f35a3827e23cb2f7cd2e3acead283.jpeg', '0', '1', '2018-05-10 14:32:54');
INSERT INTO `users_post` VALUES ('11', '3', 'test', null, '12', '1', '<iframe height=\'100%\' width=\'100%\' src=\'http://player.youku.com/embed/XMzUzMjQxMjk4OA==\' frameborder=0 \'allowfullscreen\'></iframe>', '1', '1', '2018-05-13 15:27:20');
INSERT INTO `users_post` VALUES ('12', '2', '集体舞', null, '12', '1', 'upload/member/pic/3e48e14bd4a3742e4b7758ff33ba90c3.jpeg', '0', '1', '2018-05-13 15:28:13');
INSERT INTO `users_post` VALUES ('13', '3', '天天体育', null, '12', '1', '<iframe height=\'100%\' width=\'100%\' src=\'http://player.youku.com/embed/XMzYwMTk4NjE0NA==\' frameborder=0 \'allowfullscreen\'></iframe>', '0', '1', '2018-05-14 11:36:46');
INSERT INTO `users_post` VALUES ('14', '2', 'call了', null, '11', '86', 'upload/member/pic/cedd09bb5dd9849fda55583302a102bf.jpeg', '0', '0', '2018-05-15 09:55:12');
INSERT INTO `users_post` VALUES ('30', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\1723365512.jpg', '0', '1', '2018-06-22 09:22:58');
INSERT INTO `users_post` VALUES ('22', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\68840759.jpg', '0', '1', '2018-06-22 09:20:27');
INSERT INTO `users_post` VALUES ('23', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\413554875.jpg', '0', '1', '2018-06-22 09:20:27');
INSERT INTO `users_post` VALUES ('24', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\103810383.jpg', '0', '1', '2018-06-22 09:20:27');
INSERT INTO `users_post` VALUES ('29', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\1017942494.jpg', '0', '1', '2018-06-22 09:22:58');
INSERT INTO `users_post` VALUES ('28', '2', '活动图片', null, '14', '0', 'upload/member/pic/693433720.jpg', '0', '1', '2018-06-22 09:22:58');
INSERT INTO `users_post` VALUES ('31', '2', '活动图片', null, '14', '0', 'upload/member/pic/1925883379.jpg', '0', '1', '2018-06-22 09:22:59');
INSERT INTO `users_post` VALUES ('35', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\68840759.jpg', '0', '1', '2018-06-22 10:18:01');
INSERT INTO `users_post` VALUES ('36', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\103810383.jpg', '0', '1', '2018-06-22 10:18:01');
INSERT INTO `users_post` VALUES ('37', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\413554875.jpg', '0', '1', '2018-06-22 10:18:01');
INSERT INTO `users_post` VALUES ('38', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\1723365512.jpg', '0', '1', '2018-06-22 10:18:01');
INSERT INTO `users_post` VALUES ('39', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\IMG_20170922_153506.jpg', '0', '1', '2018-06-22 10:19:40');
INSERT INTO `users_post` VALUES ('40', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\IMG_20170922_153506.jpg', '0', '1', '2018-06-22 10:19:40');
INSERT INTO `users_post` VALUES ('41', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\IMG_20170922_153508.jpg', '0', '1', '2018-06-22 10:19:40');
INSERT INTO `users_post` VALUES ('42', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\IMG_20170922_153508.jpg', '0', '1', '2018-06-22 10:19:40');
INSERT INTO `users_post` VALUES ('43', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\IMG_20170922_153510.jpg', '0', '1', '2018-06-22 10:19:40');
INSERT INTO `users_post` VALUES ('44', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\IMG_20170922_153510.jpg', '0', '1', '2018-06-22 10:19:41');
INSERT INTO `users_post` VALUES ('53', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\IMG_20170922_150917.jpg', '0', '1', '2018-06-22 10:29:40');
INSERT INTO `users_post` VALUES ('54', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\IMG_20170922_150922.jpg', '1', '1', '2018-06-22 10:29:40');
INSERT INTO `users_post` VALUES ('55', '2', '活动图片', null, '14', '0', 'upload/member/pic/\\IMG_20170922_150922.jpg', '1', '1', '2018-06-22 10:29:41');

-- ----------------------------
-- Table structure for verification_codes
-- ----------------------------
DROP TABLE IF EXISTS `verification_codes`;
CREATE TABLE `verification_codes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region` varchar(5) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `sessionId` varchar(32) DEFAULT NULL,
  `token` char(36) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`region`,`phone`),
  UNIQUE KEY `verification_codes_region_phone` (`region`,`phone`),
  UNIQUE KEY `token` (`token`),
  UNIQUE KEY `verification_codes_token_unique` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of verification_codes
-- ----------------------------

/*
Navicat MySQL Data Transfer

Source Server         : MySQL
Source Server Version : 50625
Source Host           : localhost:3306
Source Database       : maga

Target Server Type    : MYSQL
Target Server Version : 50625
File Encoding         : 65001

Date: 2017-07-20 23:19:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for auth_account
-- ----------------------------
DROP TABLE IF EXISTS `auth_account`;
CREATE TABLE `auth_account` (
  `ac_id` int(11) NOT NULL AUTO_INCREMENT,
  `ac_username` varchar(50) DEFAULT NULL,
  `ac_email` varchar(120) DEFAULT NULL,
  `ac_password` varchar(100) DEFAULT NULL,
  `ac_created` datetime DEFAULT NULL,
  `ac_modified` datetime DEFAULT NULL,
  `ac_status` int(1) DEFAULT '0',
  `ac_first_name` varchar(50) DEFAULT NULL,
  `ac_last_name` varchar(50) DEFAULT NULL,
  `ac_token` varchar(32) DEFAULT NULL,
  `ac_last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`ac_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_account
-- ----------------------------
INSERT INTO `auth_account` VALUES ('1', 'icarus12345', 'khuongxuantruong@gmail.com', 'dfcd89dd382770db6acfa0781b9ef680', '2017-06-17 22:54:44', null, '1', 'Truong', 'Khuong', 'alr07I6MzEkG1p4toFBfPOT9LwuWjny8', '2017-07-08 23:37:10');
INSERT INTO `auth_account` VALUES ('2', 'risk', 'khuongxuantruong2@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '2017-06-18 17:40:17', null, '1', 'Khuong', 'Truong', 'twIUblzV9GONTQx305ZMpgmKvFd1c6RD', '2017-07-14 12:26:13');
INSERT INTO `auth_account` VALUES ('3', 'thoanguyen', 'thoanguyen@yahoo.com.vn', '827ccb0eea8a706c4c34a16891f84e7b', '2017-07-08 23:25:53', null, '1', 'Thoa', 'Nguyen', '8YideVOUk04BFaN7qMQo5x6zcHJ1bm2l', '2017-07-08 23:26:02');

-- ----------------------------
-- Table structure for auth_client
-- ----------------------------
DROP TABLE IF EXISTS `auth_client`;
CREATE TABLE `auth_client` (
  `app_id` varchar(32) NOT NULL,
  `app_secret` varchar(100) DEFAULT NULL,
  `app_name` varchar(100) DEFAULT NULL,
  `app_created` datetime DEFAULT NULL,
  `app_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_client
-- ----------------------------
INSERT INTO `auth_client` VALUES ('12345', '6789', 'Icarus12345', '2017-06-17 14:58:25', null);

-- ----------------------------
-- Table structure for auth_token
-- ----------------------------
DROP TABLE IF EXISTS `auth_token`;
CREATE TABLE `auth_token` (
  `token_id` varchar(32) NOT NULL,
  `token_app_id` varchar(32) DEFAULT NULL,
  `token_created` datetime DEFAULT NULL,
  `token_modified` datetime DEFAULT NULL,
  `token_expried` datetime DEFAULT NULL,
  PRIMARY KEY (`token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_token
-- ----------------------------
INSERT INTO `auth_token` VALUES ('twIUblzV9GONTQx305ZMpgmKvFd1c6RD', '2', '2017-07-14 12:26:13', null, '2017-07-14 14:26:13');

-- ----------------------------
-- Table structure for auth_users
-- ----------------------------
DROP TABLE IF EXISTS `auth_users`;
CREATE TABLE `auth_users` (
  `ause_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `ause_key` varchar(32) NOT NULL,
  `ause_group` smallint(6) DEFAULT NULL,
  `ause_name` varchar(100) NOT NULL,
  `ause_username` varchar(50) NOT NULL,
  `ause_email` varchar(50) NOT NULL,
  `ause_secretkey` varchar(52) NOT NULL,
  `ause_salt` varchar(32) NOT NULL,
  `ause_password` varchar(32) NOT NULL,
  `ause_position` smallint(6) NOT NULL,
  `ause_status` int(1) DEFAULT NULL,
  `ause_created` datetime NOT NULL,
  `ause_modified` datetime DEFAULT NULL,
  `ause_deleted` datetime DEFAULT NULL,
  `ause_picture` varchar(120) DEFAULT NULL,
  `ause_authority` varchar(20) DEFAULT NULL,
  `ause_lastlogin` datetime DEFAULT NULL,
  `ause_sorting` int(11) DEFAULT NULL,
  PRIMARY KEY (`ause_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10026 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_users
-- ----------------------------
INSERT INTO `auth_users` VALUES ('10001', 'V9ZXr6Uw', '10002', 'Trường Khương', 'khuongxuantruong@gmail.com', 'khuongxuantruong@gmail.com', '7PtY3oAdRdK6P7YncnroQ2KLnLoPjrnW', 'FA04iw9qhWlT', '27d168e16f3cafbdd8c5f0c1be19608e', '1', '1', '2013-04-02 16:43:42', '2017-06-11 21:40:38', null, 'https://lh5.googleusercontent.com/-WAMgTlfd5og/AAAAAAAAAAI/AAAAAAAAAP0/b0LCEJbexS4/photo.jpg', 'Administrator', '2013-04-08 10:01:04', null);
INSERT INTO `auth_users` VALUES ('10018', 'QOEb1vsc', null, 'Admin', 'admin', 'Admin@gmail.com', 'qBKh4pA07aiU4WNbLCHLjhzpaFnjWXYP', '7PrSkIXh', '0903ce30d609ba18172fc0605c2848c2', '2', '1', '2014-12-11 23:01:35', '2014-12-12 05:07:50', null, null, 'Admin', null, null);
INSERT INTO `auth_users` VALUES ('10020', 'h8ovaLKc', null, 'View', 'View', 'View@gmail.com', 'PMvaAlFujxu8GZvKzLPIjQOaCu4Eba5y', 'g5AVZC8K', '477b560f0f16773d3348ae9711052ba9', '2', '0', '2014-12-12 08:31:32', '2015-07-04 22:09:45', null, null, 'View', null, null);
INSERT INTO `auth_users` VALUES ('10025', '7WcZ5eCH', null, 'AAAA', 'aaaa', 'aaa@gmail.com', 'vZdcbJt5NsfTrIoMej0XwE7g4QmGPlCu', 'VC4lXaHJ', 'a75dcb584add437e980f0c2d42cbc450', '2', '0', '2017-06-11 09:24:10', '2017-06-11 10:00:13', '2017-06-11 10:00:13', 'aaaa', '', null, '1');

-- ----------------------------
-- Table structure for ci_sessions
-- ----------------------------
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ci_sessions
-- ----------------------------
INSERT INTO `ci_sessions` VALUES ('7p3a0mfhl9ksuu03iheh5ulh35lcdaag', '127.0.0.1', '1500566172', 0x5F5F63695F6C6173745F726567656E65726174657C693A313530303536363132333B6E6C6F67696E7C693A303B4B4346494E4445527C613A343A7B733A383A2264697361626C6564223B623A303B733A393A2275706C6F616455524C223B733A353A222F64617461223B733A393A2275706C6F6164446972223B733A34323A22443A5C78616D70705C6874646F63735C7269736B5C7365727665725C73797374656D5C2E2E2F64617461223B733A363A22616363657373223B613A323A7B733A353A2266696C6573223B613A363A7B733A363A2275706C6F6164223B623A313B733A363A2264656C657465223B623A313B733A343A22636F7079223B623A313B733A343A226D6F7665223B623A313B733A363A2272656E616D65223B623A313B733A343A2265646974223B623A313B7D733A343A2264697273223B613A333A7B733A363A22637265617465223B623A313B733A363A2264656C657465223B623A313B733A363A2272656E616D65223B623A313B7D7D7D64617362626F6172645F757365727C4F3A383A22737464436C617373223A31353A7B733A373A22617573655F6964223B733A353A223130303138223B733A383A22617573655F6B6579223B733A383A22514F456231767363223B733A31303A22617573655F67726F7570223B4E3B733A393A22617573655F6E616D65223B733A353A2241646D696E223B733A31333A22617573655F757365726E616D65223B733A353A2261646D696E223B733A31303A22617573655F656D61696C223B733A31353A2241646D696E40676D61696C2E636F6D223B733A31333A22617573655F706F736974696F6E223B733A313A2232223B733A31313A22617573655F737461747573223B733A313A2231223B733A31323A22617573655F63726561746564223B733A31393A22323031342D31322D31312032333A30313A3335223B733A31333A22617573655F6D6F646966696564223B733A31393A22323031342D31322D31322030353A30373A3530223B733A31323A22617573655F64656C65746564223B4E3B733A31323A22617573655F70696374757265223B4E3B733A31343A22617573655F617574686F72697479223B733A353A2241646D696E223B733A31343A22617573655F6C6173746C6F67696E223B4E3B733A31323A22617573655F736F7274696E67223B4E3B7D);
INSERT INTO `ci_sessions` VALUES ('b4ibpelg3aa4nssubp4msfpjga8uajnd', '127.0.0.1', '1500542380', 0x5F5F63695F6C6173745F726567656E65726174657C693A313530303534323331333B6E6C6F67696E7C693A303B4B4346494E4445527C613A343A7B733A383A2264697361626C6564223B623A303B733A393A2275706C6F616455524C223B733A353A222F64617461223B733A393A2275706C6F6164446972223B733A34303A22453A5C77616D7036345C7777775C7269736B5C7365727665725C73797374656D5C2E2E2F64617461223B733A363A22616363657373223B613A323A7B733A353A2266696C6573223B613A363A7B733A363A2275706C6F6164223B623A313B733A363A2264656C657465223B623A313B733A343A22636F7079223B623A313B733A343A226D6F7665223B623A313B733A363A2272656E616D65223B623A313B733A343A2265646974223B623A313B7D733A343A2264697273223B613A333A7B733A363A22637265617465223B623A313B733A363A2264656C657465223B623A313B733A363A2272656E616D65223B623A313B7D7D7D64617362626F6172645F757365727C4F3A383A22737464436C617373223A31353A7B733A373A22617573655F6964223B733A353A223130303138223B733A383A22617573655F6B6579223B733A383A22514F456231767363223B733A31303A22617573655F67726F7570223B4E3B733A393A22617573655F6E616D65223B733A353A2241646D696E223B733A31333A22617573655F757365726E616D65223B733A353A2261646D696E223B733A31303A22617573655F656D61696C223B733A31353A2241646D696E40676D61696C2E636F6D223B733A31333A22617573655F706F736974696F6E223B733A313A2232223B733A31313A22617573655F737461747573223B733A313A2231223B733A31323A22617573655F63726561746564223B733A31393A22323031342D31322D31312032333A30313A3335223B733A31333A22617573655F6D6F646966696564223B733A31393A22323031342D31322D31322030353A30373A3530223B733A31323A22617573655F64656C65746564223B4E3B733A31323A22617573655F70696374757265223B4E3B733A31343A22617573655F617574686F72697479223B733A353A2241646D696E223B733A31343A22617573655F6C6173746C6F67696E223B4E3B733A31323A22617573655F736F7274696E67223B4E3B7D);

-- ----------------------------
-- Table structure for tbl_category
-- ----------------------------
DROP TABLE IF EXISTS `tbl_category`;
CREATE TABLE `tbl_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `data` longtext,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `pid` int(11) DEFAULT '0',
  `value` varchar(255) DEFAULT NULL,
  `sorting` int(11) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=110 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_category
-- ----------------------------
INSERT INTO `tbl_category` VALUES ('94', 'THẨM MỸ  NGỰC', 'tham-my-nguc', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:38:33', '2017-07-20 20:52:22', 'mega', '1', '0', '>94', '88', '41');
INSERT INTO `tbl_category` VALUES ('95', 'THẨM MỸ  MẶT', 'tham-my-mat', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:38:45', '2017-07-20 20:52:22', 'mega', '1', '0', '>95', '89', '41');
INSERT INTO `tbl_category` VALUES ('96', 'THẨM MỸ MŨI', 'tham-my-mui', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:38:56', '2017-07-20 20:52:22', 'mega', '1', '0', '>96', '90', '41');
INSERT INTO `tbl_category` VALUES ('97', 'THẨM MỸ  MẮT', 'tham-my-mat', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:39:17', '2017-07-20 20:52:22', 'mega', '1', '0', '>97', '91', '41');
INSERT INTO `tbl_category` VALUES ('98', 'THẨM MỸ MÔI', 'tham-my-moi', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:39:28', '2017-07-20 20:52:22', 'mega', '1', '0', '>98', '92', '41');
INSERT INTO `tbl_category` VALUES ('99', 'PHẪU THUẬT GIẢM BÉO', 'phau-thuat-giam-beo', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:39:38', '2017-07-20 20:52:22', 'mega', '1', '0', '>99', '93', '41');
INSERT INTO `tbl_category` VALUES ('100', 'LIỆU TRÌNH ĐIỀU TRỊ', 'lieu-trinh-dieu-tri', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:40:10', '2017-07-20 20:52:22', 'mega', '1', '0', '>100', '94', '42');
INSERT INTO `tbl_category` VALUES ('101', 'ĐIỀU TRỊ  NÁM', 'dieu-tri-nam', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:40:27', '2017-07-20 20:52:22', 'mega', '1', '100', '>100>101', '95', '42');
INSERT INTO `tbl_category` VALUES ('102', 'ĐIỀU TRỊ  MỤN', 'dieu-tri-mun', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:41:02', '2017-07-20 20:52:22', 'mega', '1', '100', '>100>102', '96', '42');
INSERT INTO `tbl_category` VALUES ('103', 'ĐIỀU TRỊ ĐẶC BIỆT', 'dieu-tri-dac-biet', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:41:25', '2017-07-20 20:52:22', 'mega', '1', '100', '>100>103', '97', '42');
INSERT INTO `tbl_category` VALUES ('104', 'LIỆU TRÌNH CHĂM SÓC', 'lieu-trinh-cham-soc', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:41:47', '2017-07-20 20:52:22', 'mega', '1', '0', '>104', '98', '42');
INSERT INTO `tbl_category` VALUES ('105', 'COMBO CHĂM SÓC DA MẶT', 'combo-cham-soc-da-mat', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:42:03', '2017-07-20 20:52:22', 'mega', '1', '104', '>104>105', '99', '42');
INSERT INTO `tbl_category` VALUES ('106', 'COMBO  GIẢM BÉO', 'combo-giam-beo', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:42:28', '2017-07-20 20:52:22', 'mega', '1', '0', '>106', '100', '43');
INSERT INTO `tbl_category` VALUES ('107', 'PHUN THÊU', 'phun-theu', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:42:41', '2017-07-20 20:52:22', 'mega', '1', '0', '>107', '101', '44');
INSERT INTO `tbl_category` VALUES ('108', 'XÓA LASER', 'xoa-laser', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:42:51', '2017-07-20 20:52:22', 'mega', '1', '0', '>108', '102', '44');
INSERT INTO `tbl_category` VALUES ('109', 'TRIỆT LÔNG  TRỌN GÓI', 'triet-long-tron-goi', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:43:15', '2017-07-20 20:52:22', 'mega', '1', '0', '>109', '103', '45');

-- ----------------------------
-- Table structure for tbl_data
-- ----------------------------
DROP TABLE IF EXISTS `tbl_data`;
CREATE TABLE `tbl_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `data` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `category` int(11) DEFAULT '-1',
  `pid` int(11) DEFAULT '-1',
  `longdata` longtext,
  `key` varchar(32) DEFAULT NULL,
  `sorting` int(11) DEFAULT '1',
  `author` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_data
-- ----------------------------
INSERT INTO `tbl_data` VALUES ('41', 'Mailer Setting', 'mailer-setting', 'a:10:{s:4:\"host\";s:40:\"sg2plcpnl0035.prod.sin2.secureserver.net\";s:4:\"port\";s:3:\"465\";s:10:\"smtpsecure\";s:3:\"ssl\";s:8:\"smtpauth\";s:1:\"1\";s:7:\"mailler\";s:4:\"smtp\";s:8:\"username\";s:28:\"noreply@creativedesignvn.com\";s:8:\"password\";s:11:\"noreply@123\";s:6:\"sender\";s:22:\"Creative Studio Design\";s:6:\"mailer\";s:4:\"smtp\";s:6:\"active\";s:1:\"1\";}', '2017-07-20 14:23:23', '2017-07-20 14:54:57', 'mailler', '1', '-1', '-1', null, null, '40', '10018');

-- ----------------------------
-- Table structure for tbl_module
-- ----------------------------
DROP TABLE IF EXISTS `tbl_module`;
CREATE TABLE `tbl_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `data` longtext,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `category` int(11) DEFAULT '-1',
  `sorting` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_module
-- ----------------------------
INSERT INTO `tbl_module` VALUES ('32', 'Project', 'project', 'a:13:{s:4:\"site\";s:8:\"Risk App\";s:4:\"size\";s:0:\"\";s:7:\"storage\";s:11:\"tbl_project\";s:4:\"edit\";s:4:\"true\";s:4:\"type\";s:0:\"\";s:8:\"catetype\";s:0:\"\";s:10:\"cateviewer\";s:4:\"list\";s:6:\"groups\";a:1:{i:0;a:2:{s:4:\"name\";s:11:\"Information\";s:6:\"length\";s:1:\"1\";}}s:7:\"columns\";a:1:{i:0;a:7:{s:4:\"type\";s:4:\"text\";s:3:\"col\";s:2:\"12\";s:4:\"name\";s:4:\"desc\";s:5:\"title\";s:4:\"Desc\";s:6:\"client\";s:0:\"\";s:6:\"server\";s:0:\"\";s:3:\"biz\";s:1:\"0\";}}s:3:\"add\";s:5:\"false\";s:6:\"delete\";s:5:\"false\";s:6:\"unique\";s:5:\"false\";s:9:\"showthumb\";s:5:\"false\";}', '2017-07-20 15:11:19', '2017-07-20 16:19:23', '', '0', '-1', '24');
INSERT INTO `tbl_module` VALUES ('33', 'Type', 'type', 'a:14:{s:4:\"site\";s:6:\"Common\";s:4:\"size\";s:3:\"320\";s:7:\"storage\";s:8:\"tbl_type\";s:3:\"add\";s:4:\"true\";s:4:\"edit\";s:4:\"true\";s:6:\"delete\";s:4:\"true\";s:6:\"unique\";s:4:\"true\";s:4:\"type\";s:4:\"mega\";s:10:\"typeviewer\";s:6:\"string\";s:8:\"catetype\";s:0:\"\";s:10:\"cateviewer\";s:0:\"\";s:6:\"groups\";a:1:{i:0;a:2:{s:4:\"name\";s:11:\"Information\";s:6:\"length\";s:1:\"2\";}}s:7:\"columns\";a:2:{i:0;a:7:{s:4:\"type\";s:4:\"text\";s:3:\"col\";s:2:\"12\";s:4:\"name\";s:4:\"desc\";s:5:\"title\";s:4:\"Desc\";s:6:\"client\";s:22:\"validate[maxSize[250]]\";s:6:\"server\";s:0:\"\";s:3:\"biz\";s:1:\"0\";}i:1;a:7:{s:4:\"type\";s:5:\"image\";s:3:\"col\";s:2:\"12\";s:4:\"name\";s:5:\"image\";s:5:\"title\";s:5:\"Image\";s:6:\"client\";s:22:\"validate[maxSize[120]]\";s:6:\"server\";s:0:\"\";s:3:\"biz\";s:1:\"0\";}}s:9:\"showthumb\";s:5:\"false\";}', '2017-07-20 20:03:25', '2017-07-20 20:11:47', '', '0', '-1', '25');
INSERT INTO `tbl_module` VALUES ('34', 'Category', 'category', 'a:14:{s:4:\"site\";s:6:\"Common\";s:4:\"size\";s:3:\"480\";s:7:\"storage\";s:12:\"tbl_category\";s:3:\"add\";s:4:\"true\";s:4:\"edit\";s:4:\"true\";s:6:\"delete\";s:4:\"true\";s:6:\"unique\";s:4:\"true\";s:9:\"showthumb\";s:4:\"true\";s:4:\"type\";s:4:\"mega\";s:10:\"typeviewer\";s:4:\"list\";s:8:\"catetype\";s:4:\"mega\";s:10:\"cateviewer\";s:4:\"tree\";s:6:\"groups\";a:1:{i:0;a:2:{s:4:\"name\";s:11:\"Information\";s:6:\"length\";s:1:\"2\";}}s:7:\"columns\";a:2:{i:0;a:7:{s:4:\"type\";s:4:\"text\";s:3:\"col\";s:2:\"12\";s:4:\"name\";s:4:\"desc\";s:5:\"title\";s:4:\"Desc\";s:6:\"client\";s:22:\"validate[maxSize[250]]\";s:6:\"server\";s:0:\"\";s:3:\"biz\";s:1:\"0\";}i:1;a:7:{s:4:\"type\";s:5:\"image\";s:3:\"col\";s:2:\"12\";s:4:\"name\";s:5:\"image\";s:5:\"title\";s:5:\"Image\";s:6:\"client\";s:22:\"validate[maxSize[120]]\";s:6:\"server\";s:0:\"\";s:3:\"biz\";s:1:\"0\";}}}', '2017-07-20 20:10:03', '2017-07-20 20:12:36', '', '0', '-1', '26');

-- ----------------------------
-- Table structure for tbl_setting
-- ----------------------------
DROP TABLE IF EXISTS `tbl_setting`;
CREATE TABLE `tbl_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `data` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `category` int(11) DEFAULT '-1',
  `pid` int(11) DEFAULT '-1',
  `key` varchar(32) DEFAULT NULL,
  `sorting` int(11) DEFAULT '1',
  `author` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_setting
-- ----------------------------
INSERT INTO `tbl_setting` VALUES ('11', 'Description', 'description', 'a:2:{s:3:\"sid\";s:2:\"24\";s:4:\"desc\";s:22:\"Description of website\";}', '2017-06-01 15:31:07', null, 'creative', '0', '36', '-1', null, '5', null);
INSERT INTO `tbl_setting` VALUES ('9', 'Tel', 'tel', 'a:3:{s:3:\"sid\";s:2:\"23\";s:4:\"desc\";s:52:\"A portable telephone that can make and receive calls\";s:5:\"value\";s:10:\"0985747240\";}', '2017-06-01 10:51:55', '2017-06-01 15:56:43', 'creative', '0', '37', '-1', null, '3', null);
INSERT INTO `tbl_setting` VALUES ('8', 'Mobile Phone', 'mobile-phone', 'a:3:{s:3:\"sid\";s:2:\"23\";s:4:\"desc\";s:52:\"A portable telephone that can make and receive calls\";s:5:\"value\";s:30:\"+61 8 8274 7000 - 1800 809 991\";}', '2017-06-01 10:50:23', '2017-06-02 12:07:13', 'creative', '0', '37', '-1', null, '2', null);
INSERT INTO `tbl_setting` VALUES ('7', 'Địa chỉ', 'address', 'a:3:{s:3:\"sid\";s:2:\"23\";s:5:\"value\";s:45:\"215 Greenhill Rd, Eastwood SA 5063, Australia\";s:4:\"desc\";s:18:\"Address of company\";}', '2017-06-01 09:59:06', '2017-06-02 12:17:33', 'creative', '0', '37', '-1', null, '1', null);
INSERT INTO `tbl_setting` VALUES ('10', 'Keywork', 'keywork', 'a:2:{s:3:\"sid\";s:2:\"23\";s:4:\"desc\";s:16:\"Keyword meta tag\";}', '2017-06-01 11:00:14', null, 'creative', '0', '36', '-1', null, '4', null);
INSERT INTO `tbl_setting` VALUES ('12', 'Logo', 'logo', 'a:3:{s:3:\"sid\";s:2:\"25\";s:4:\"desc\";s:15:\"Logo of website\";s:5:\"value\";s:21:\"/data/image/img/1.jpg\";}', '2017-06-01 16:11:35', '2017-06-01 16:11:48', 'creative', '0', '36', '-1', null, '6', null);
INSERT INTO `tbl_setting` VALUES ('13', 'Email', 'email', 'a:3:{s:3:\"sid\";s:2:\"23\";s:4:\"desc\";s:33:\"Email to contact and send request\";s:5:\"value\";s:29:\"nhungbui@creativedesignvn.com\";}', '2017-06-02 11:12:45', '2017-06-02 11:12:50', 'creative', '0', '37', '-1', null, '7', null);
INSERT INTO `tbl_setting` VALUES ('14', 'Map', 'map', 'a:3:{s:3:\"sid\";s:2:\"23\";s:4:\"desc\";s:22:\"Location of Google Map\";s:5:\"value\";s:21:\"10.892048, 106.679982\";}', '2017-06-02 11:23:28', '2017-06-02 12:14:20', 'creative', '0', '37', '-1', null, '8', null);
INSERT INTO `tbl_setting` VALUES ('15', 'LIÊN HỆ VỚI CHÚNG TÔI', 'lien-he-voi-chung-toi', 'a:3:{s:3:\"sid\";s:2:\"24\";s:4:\"desc\";s:28:\"LIÊN HỆ VỚI CHÚNG TÔI\";s:5:\"value\";s:87:\"Vui lòng để lại thông tin, chúng tôi sẽ liên hệ với bạn sớm nhất.\";}', '2017-06-02 12:10:34', '2017-06-02 12:12:40', 'creative', '0', '37', '-1', null, '9', null);
INSERT INTO `tbl_setting` VALUES ('16', 'Title', 'title', 'a:3:{s:3:\"sid\";s:2:\"23\";s:4:\"desc\";s:16:\"Title of website\";s:5:\"value\";s:23:\"Creative Design Studio \";}', '2017-06-02 12:15:13', '2017-06-02 12:15:31', 'creative', '0', '36', '-1', null, '10', null);
INSERT INTO `tbl_setting` VALUES ('17', 'Khách hàng hài lòng', 'testimonial', 'a:3:{s:3:\"sid\";s:2:\"24\";s:4:\"desc\";s:26:\"Testimonial from customer.\";s:5:\"value\";s:245:\"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.\";}', '2017-06-02 13:19:07', '2017-06-02 13:19:36', 'creative', '0', '38', '-1', null, '11', null);
INSERT INTO `tbl_setting` VALUES ('18', 'Công việc của chúng tôi', 'cong-viec-cua-chung-toi', 'a:3:{s:3:\"sid\";s:2:\"24\";s:4:\"desc\";s:30:\"Công việc của chúng tôi\";s:5:\"value\";s:170:\"Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.\";}', '2017-06-02 14:04:21', '2017-06-02 14:04:31', 'creative', '0', '38', '-1', null, '12', null);
INSERT INTO `tbl_setting` VALUES ('24', 'SMTPSecure', 'smtpsecure', 'a:2:{s:3:\"sid\";s:2:\"23\";s:4:\"desc\";s:10:\"SMTPSecure\";}', '2017-07-20 14:09:35', null, 'email', '0', '0', '-1', null, '18', null);
INSERT INTO `tbl_setting` VALUES ('23', 'SMTPAuth', 'smtpauth', 'a:2:{s:3:\"sid\";s:2:\"29\";s:4:\"desc\";s:8:\"SMTPAuth\";}', '2017-07-20 14:09:24', null, 'email', '0', '0', '-1', null, '17', null);
INSERT INTO `tbl_setting` VALUES ('21', 'Host', 'host', 'a:2:{s:3:\"sid\";s:2:\"23\";s:4:\"desc\";s:15:\"The SMTP server\";}', '2017-07-20 13:55:33', null, 'email', '0', '0', '-1', null, '15', null);
INSERT INTO `tbl_setting` VALUES ('22', 'Port', 'port', 'a:2:{s:3:\"sid\";s:2:\"23\";s:4:\"desc\";s:33:\"The SMTP port for the mail server\";}', '2017-07-20 13:55:58', null, 'email', '0', '0', '-1', null, '16', null);
INSERT INTO `tbl_setting` VALUES ('25', 'Mailer', 'mailer', 'a:2:{s:3:\"sid\";s:2:\"23\";s:4:\"desc\";s:6:\"Mailer\";}', '2017-07-20 14:09:46', null, 'email', '0', '0', '-1', null, '19', null);
INSERT INTO `tbl_setting` VALUES ('26', 'Username', 'username', 'a:2:{s:3:\"sid\";s:2:\"23\";s:4:\"desc\";s:8:\"Username\";}', '2017-07-20 14:10:00', null, 'email', '0', '0', '-1', null, '20', null);
INSERT INTO `tbl_setting` VALUES ('27', 'Password', 'password', 'a:2:{s:3:\"sid\";s:2:\"23\";s:4:\"desc\";s:8:\"Password\";}', '2017-07-20 14:10:11', null, 'email', '0', '0', '-1', null, '21', null);
INSERT INTO `tbl_setting` VALUES ('28', 'Sender name', 'sender-name', 'a:2:{s:3:\"sid\";s:2:\"23\";s:4:\"desc\";s:11:\"Sender name\";}', '2017-07-20 14:11:16', null, 'email', '0', '0', '-1', null, '22', null);

-- ----------------------------
-- Table structure for tbl_type
-- ----------------------------
DROP TABLE IF EXISTS `tbl_type`;
CREATE TABLE `tbl_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `data` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `category` int(11) DEFAULT '-1',
  `sorting` int(11) DEFAULT '1',
  `author` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tbl_type
-- ----------------------------
INSERT INTO `tbl_type` VALUES ('41', 'KHOA PHẪU THUẬT THẨM MỸ', 'khoa-phau-thuat-tham-my', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:05:57', null, 'mega', '1', '-1', '1', '10018');
INSERT INTO `tbl_type` VALUES ('42', 'TRUNG TÂM ĐIỀU TRỊ VÀ CHĂM SÓC DA MẶT', 'trung-tam-dieu-tri-va-cham-soc-da-mat', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:06:07', null, 'mega', '1', '-1', '2', '10018');
INSERT INTO `tbl_type` VALUES ('43', 'TRUNG TÂM ĐIỀU TRỊ GIẢM BÉO KHÔNG XÂM LẤN', 'trung-tam-dieu-tri-giam-beo-khong-xam-lan', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:06:30', null, 'mega', '1', '-1', '3', '10018');
INSERT INTO `tbl_type` VALUES ('44', 'TRUNG TÂM PHUN THÊU', 'trung-tam-phun-theu', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:06:40', null, 'mega', '1', '-1', '4', '10018');
INSERT INTO `tbl_type` VALUES ('45', 'TRUNG TÂM TRIỆT LÔNG IPL TRIỆT LÔNG', 'trung-tam-triet-long-ipl-triet-long', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:06:50', null, 'mega', '1', '-1', '5', '10018');
INSERT INTO `tbl_type` VALUES ('46', 'Bắn mụn ruồi, mụn thịt', 'ban-mun-ruoi-mun-thit', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:07:03', null, 'mega', '1', '-1', '6', '10018');
INSERT INTO `tbl_type` VALUES ('47', 'Chăm sóc da', 'cham-soc-da', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:07:19', null, 'mega', '1', '-1', '7', '10018');
INSERT INTO `tbl_type` VALUES ('48', 'MỸ PHẨM', 'my-pham', 'a:2:{s:4:\"desc\";s:0:\"\";s:5:\"image\";s:0:\"\";}', '2017-07-20 20:07:31', '2017-07-20 21:43:06', 'mega', '1', '-1', '8', '10018');

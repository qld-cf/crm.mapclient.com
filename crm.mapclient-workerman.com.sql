/*
Navicat MySQL Data Transfer

Source Server         : 本地数据库
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : crm.mapclient-workerman.com

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-11-10 09:42:59
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mc_line
-- ----------------------------
DROP TABLE IF EXISTS `mc_line`;
CREATE TABLE `mc_line` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `point` varchar(128) NOT NULL COMMENT '记录下的坐标点',
  `record_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '记录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_line
-- ----------------------------

-- ----------------------------
-- Table structure for mc_user
-- ----------------------------
DROP TABLE IF EXISTS `mc_user`;
CREATE TABLE `mc_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '可以对外展示的序号',
  `src` varchar(16) NOT NULL COMMENT '来源 [admin：管理端平台；gps：gps端]',
  `name` varchar(128) NOT NULL COMMENT '车牌号码/车辆名/用户名',
  `password` varchar(128) NOT NULL COMMENT '管理平台登陆密码',
  `logout_time` datetime NOT NULL COMMENT '最近离线时间',
  PRIMARY KEY (`id`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_user
-- ----------------------------

/*
Navicat MySQL Data Transfer

Source Server         : PC Local
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : floors

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-10-07 16:19:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for applications
-- ----------------------------
DROP TABLE IF EXISTS `applications`;
CREATE TABLE `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `cuid` int(11) NOT NULL,
  `muid` int(11) NOT NULL,
  `dflag` tinyint(1) NOT NULL,
  `appname` varchar(250) NOT NULL,
  `appdesc` text NOT NULL,
  `ip` varchar(50) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `apptoken` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for authorization
-- ----------------------------
DROP TABLE IF EXISTS `authorization`;
CREATE TABLE `authorization` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `permissionid` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `cuid` int(11) NOT NULL,
  `muid` int(11) NOT NULL,
  `dflag` tinyint(1) NOT NULL,
  `expired` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for avatars
-- ----------------------------
DROP TABLE IF EXISTS `avatars`;
CREATE TABLE `avatars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `cuid` int(11) NOT NULL,
  `muid` int(11) NOT NULL,
  `dflag` tinyint(1) NOT NULL,
  `selected` tinyint(1) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `crc` varchar(16) NOT NULL,
  `extensions` varchar(100) NOT NULL,
  `filedata` longblob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for broker
-- ----------------------------
DROP TABLE IF EXISTS `broker`;
CREATE TABLE `broker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appid` int(11) NOT NULL,
  `brokerid` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `cuid` int(11) NOT NULL,
  `muid` int(11) NOT NULL,
  `dflag` tinyint(1) NOT NULL,
  `servicename` varchar(255) NOT NULL,
  `servicedesc` text NOT NULL,
  `code` varchar(25) NOT NULL,
  `config` text NOT NULL,
  `version` varchar(24) NOT NULL,
  `callbackurl` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for credentials
-- ----------------------------
DROP TABLE IF EXISTS `credentials`;
CREATE TABLE `credentials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `credentials` varchar(255) NOT NULL,
  `secure` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `dflag` tinyint(1) NOT NULL,
  `sflag` tinyint(1) NOT NULL,
  `remark` text NOT NULL,
  `expired` datetime NOT NULL,
  `profilepic` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for logs
-- ----------------------------
DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `credentialid` int(11) NOT NULL,
  `datein` datetime NOT NULL,
  `requestmethod` varchar(255) NOT NULL,
  `action` varchar(200) NOT NULL,
  `ipaddress` varchar(100) NOT NULL,
  `useragent` text NOT NULL,
  `httpstatus` varchar(50) NOT NULL,
  `tokens` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for operator
-- ----------------------------
DROP TABLE IF EXISTS `operator`;
CREATE TABLE `operator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `cuid` int(11) NOT NULL,
  `muid` int(11) NOT NULL,
  `dflag` tinyint(1) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `firstemail` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `roles` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appid` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `cuid` int(11) NOT NULL,
  `muid` int(11) NOT NULL,
  `dflag` tinyint(1) NOT NULL,
  `pname` varchar(255) NOT NULL,
  `pcode` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `cuid` int(11) NOT NULL,
  `muid` int(11) NOT NULL,
  `dflag` tinyint(1) NOT NULL,
  `sflag` tinyint(1) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `prefix` varchar(50) DEFAULT NULL,
  `suffix` varchar(50) DEFAULT NULL,
  `expired` datetime NOT NULL,
  `phonenumber` varchar(255) NOT NULL,
  `firstemail` varchar(255) NOT NULL,
  `secondemail` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `descriptions` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

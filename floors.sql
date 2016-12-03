/*
Navicat MySQL Data Transfer

Source Server         : Local Database
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : floors

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-12-03 15:34:37
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
  `token` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for logs
-- ----------------------------
DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `credentialid` int(11) NOT NULL,
  `datein` datetime NOT NULL,
  `dateout` datetime NOT NULL,
  `requestmethod` varchar(255) NOT NULL,
  `action` varchar(200) NOT NULL,
  `ipaddress` varchar(100) NOT NULL,
  `useragent` text NOT NULL,
  `remark` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `birthday` datetime DEFAULT NULL,
  `descriptions` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*
Navicat MySQL Data Transfer

Source Server         : Local
Source Server Version : 50718
Source Host           : localhost:3306
Source Database       : cadillac2

Target Server Type    : MYSQL
Target Server Version : 50718
File Encoding         : 65001

Date: 2017-09-18 16:34:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for clasificacion
-- ----------------------------
DROP TABLE IF EXISTS `clasificacion`;
CREATE TABLE `clasificacion` (
  `id_cla` int(11) NOT NULL AUTO_INCREMENT,
  `nom_cla` varchar(255) NOT NULL,
  PRIMARY KEY (`id_cla`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of clasificacion
-- ----------------------------
INSERT INTO `clasificacion` VALUES ('1', 'Cocina');
INSERT INTO `clasificacion` VALUES ('2', 'Barra');
SET FOREIGN_KEY_CHECKS=1;

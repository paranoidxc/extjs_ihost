/*
SQLyog Trial v8.4 
MySQL - 5.5.8 : Database - extjs
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`extjs` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `extjs`;

/*Table structure for table `department` */

DROP TABLE IF EXISTS `department`;

CREATE TABLE `department` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `department` */

/*Table structure for table `model_ext_field` */

DROP TABLE IF EXISTS `model_ext_field`;

CREATE TABLE `model_ext_field` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `model_id` tinyint(3) NOT NULL DEFAULT '0',
  `e_type` tinyint(2) NOT NULL DEFAULT '0',
  `real_field_name` varchar(255) NOT NULL DEFAULT '',
  `field_name` varchar(255) NOT NULL DEFAULT '',
  `display_name` varchar(255) NOT NULL DEFAULT '',
  `tip` varchar(255) NOT NULL DEFAULT '',
  `default_value` varchar(255) NOT NULL DEFAULT '',
  `config` tinytext,
  `sort` mediumint(4) NOT NULL DEFAULT '0',
  `is_blank` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `model_ext_field` */

insert  into `model_ext_field`(`id`,`model_id`,`e_type`,`real_field_name`,`field_name`,`display_name`,`tip`,`default_value`,`config`,`sort`,`is_blank`,`status`) values (1,1,0,'field_1','email','电子邮件','1','1','1',0,0,0),(2,1,6,'field_2','habit','习惯','333','d','love|A|1\r\nfuck|B|\r\nOption|c\r\nOption|D|1',0,0,0),(3,1,8,'field_3','category','类别','xxx','xxx','1',0,0,0),(4,1,4,'field_4','gender','性别','xxx','1','男|male\r\n女|female',0,0,0),(5,1,1,'field_5','secret_code','密码','111','111','大大大',0,0,0),(6,1,5,'field_6','is_url','是否连接','11','111','xxx',0,0,0),(7,1,2,'field_7','country','国家','111','11','US|美国\r\nCN|中国\r\nJP|日本\r\nKR|韩国',0,0,0),(8,1,7,'field_8','create_date','创建日期','','',NULL,0,0,0),(9,1,9,'field_9','update_time','创建时间','','',NULL,0,0,0),(10,0,0,'','','','','',NULL,0,0,0),(11,1,10,'','memo','备注','','',NULL,0,0,0),(12,1,11,'','rich_memo','富文本','','',NULL,0,0,0);

/*Table structure for table `model_ext_field_data` */

DROP TABLE IF EXISTS `model_ext_field_data`;

CREATE TABLE `model_ext_field_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `field_1` varchar(255) DEFAULT '',
  `field_2` varchar(255) DEFAULT '',
  `field_3` varchar(255) DEFAULT '',
  `field_4` varchar(255) DEFAULT '',
  `field_5` varchar(255) DEFAULT '',
  `field_6` varchar(255) DEFAULT '',
  `field_7` varchar(255) DEFAULT '',
  `field_8` varchar(255) DEFAULT '',
  `field_9` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `model_ext_field_data` */

insert  into `model_ext_field_data`(`id`,`field_1`,`field_2`,`field_3`,`field_4`,`field_5`,`field_6`,`field_7`,`field_8`,`field_9`) values (1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,'方法方法','Array',NULL,'male','',NULL,NULL,'',NULL),(4,'方法方法','A,D',NULL,'male','',NULL,NULL,'',NULL),(5,'33333','A,D',NULL,NULL,'1111','1','中国','2012-11-10',NULL),(6,'','A,D',NULL,NULL,'',NULL,NULL,'',NULL),(7,'','A,D',NULL,NULL,'',NULL,NULL,'',NULL),(8,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `itype` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `department_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`password`,`email`,`itype`,`status`,`department_id`) values (1,'vvvvdddd','333333',NULL,0,1,NULL),(2,'vv','1222',NULL,0,1,NULL),(3,'3333','3333',NULL,0,1,NULL),(4,'vvv','3333',NULL,0,1,NULL),(5,'vvv','33333',NULL,0,1,NULL),(6,'4444444444','333',NULL,0,1,NULL),(7,'vvvvvvvvvvvv','3333',NULL,0,1,NULL),(8,'中文测试飞','2222222',NULL,0,1,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

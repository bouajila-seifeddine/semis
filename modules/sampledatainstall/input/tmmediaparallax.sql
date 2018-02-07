DROP TABLE IF EXISTS `ps_tmmediaparallax`;
CREATE TABLE `ps_tmmediaparallax` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `selector` varchar(64) DEFAULT NULL,
  `filename` varchar(64) DEFAULT NULL,
  `width` int(10) DEFAULT NULL,
  `height` int(10) DEFAULT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/* Scheme for table ps_tmmediaparallax */
INSERT INTO `ps_tmmediaparallax` VALUES
('1','#tmhtmlcontent_home','parallax-1.jpg','2050','1152','image'),
('2','#htmlcontent_home','parallax-2.jpg','2050','1178','image');

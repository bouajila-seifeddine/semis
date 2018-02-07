DROP TABLE IF EXISTS `ps_tmmegamenu_items`;
CREATE TABLE `ps_tmmegamenu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tab` int(11) NOT NULL,
  `row` int(11) NOT NULL DEFAULT '1',
  `col` int(11) NOT NULL DEFAULT '1',
  `width` int(11) NOT NULL,
  `class` varchar(100) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `is_mega` int(11) NOT NULL DEFAULT '0',
  `settings` varchar(10000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

/* Scheme for table ps_tmmegamenu_items */
INSERT INTO `ps_tmmegamenu_items` VALUES
('57','2','1','1','0',NULL,'0','0','CAT4,CAT11,CAT18'),
('84','1','1','1','2',NULL,'0','1','CAT4'),
('85','1','1','2','2',NULL,'0','1','CAT11'),
('86','1','1','3','2',NULL,'0','1','CAT18'),
('87','1','1','4','2',NULL,'0','1','CAT25'),
('88','1','2','1','6',NULL,'0','1','BNR1'),
('89','1','2','2','6',NULL,'0','1','BNR2');

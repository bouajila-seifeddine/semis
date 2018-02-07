DROP TABLE IF EXISTS `ps_tmmegamenu`;
CREATE TABLE `ps_tmmegamenu` (
  `id_item` int(11) NOT NULL AUTO_INCREMENT,
  `id_shop` int(11) NOT NULL DEFAULT '1',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `specific_class` varchar(100) DEFAULT NULL,
  `is_mega` int(11) NOT NULL DEFAULT '0',
  `is_simple` int(11) NOT NULL DEFAULT '0',
  `is_custom_url` int(11) NOT NULL DEFAULT '0',
  `url` varchar(100) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `unique_code` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_item`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/* Scheme for table ps_tmmegamenu */
INSERT INTO `ps_tmmegamenu` VALUES
('1','1','1',NULL,'1','0','0','CAT3','1','it_69032383'),
('2','1','2',NULL,'0','1','0','CAT39','1','it_41479170'),
('3','1','3',NULL,'0','0','0','CAT40','1','it_70730459'),
('4','1','4',NULL,'0','0','0','CAT41','1','it_22442863'),
('5','1','5',NULL,'0','0','0','CAT42','1','it_12485267'),
('6','1','6',NULL,'0','0','0','CAT43','1','it_85280198'),
('7','1','7',NULL,'0','0','0','CAT44','1','it_10674302'),
('8','1','8',NULL,'0','0','0','CAT45','1','it_96307746'),
('9','1','9','blog-item','0','0','0','CAT46','1','it_79783746');

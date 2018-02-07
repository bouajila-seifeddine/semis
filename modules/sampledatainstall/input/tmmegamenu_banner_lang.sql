DROP TABLE IF EXISTS `ps_tmmegamenu_banner_lang`;
CREATE TABLE `ps_tmmegamenu_banner_lang` (
  `id_item` int(10) unsigned NOT NULL,
  `id_lang` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `url` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `public_title` varchar(100) DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id_item`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Scheme for table ps_tmmegamenu_banner_lang */
INSERT INTO `ps_tmmegamenu_banner_lang` VALUES
('1','1','menu-banner1','index.php?id_category=40&controller=category','4178cd713d0f0fc1566dcf9920dc515eeef87925_img-banner1.jpg',NULL,''),
('1','2','menu-banner1','index.php?id_category=40&controller=category','896af4437f894ce92fc70a8a2ca40f29716b7c99_img-banner1.jpg',NULL,''),
('1','3','menu-banner1','index.php?id_category=40&controller=category','5b512b4adca8dfa0892176b20bb9a600498c9999_img-banner1.jpg',NULL,''),
('1','4','menu-banner1','index.php?id_category=40&controller=category','58519140b5a124f698f2724c04be3701499c466f_img-banner1.jpg',NULL,''),
('1','5','menu-banner1','index.php?id_category=40&controller=category','280057ce2acf964f62929f49bc0e99ad6fe1448c_img-banner1.jpg',NULL,''),
('2','1','menu-banner2','index.php?id_category=41&controller=category','dc28cb155f1690e43a423fd2128c994145ffafdb_img-banner2.jpg',NULL,''),
('2','2','menu-banner2','index.php?id_category=41&controller=category','392105ae56e42c8fc396d3fd993ce81c6e80cb7b_img-banner2.jpg',NULL,''),
('2','3','menu-banner2','index.php?id_category=41&controller=category','aa0595f439a03364d882612681bb54651fdf0c14_img-banner2.jpg',NULL,''),
('2','4','menu-banner2','index.php?id_category=41&controller=category','decd2d0f5dd1409d890596d4d7250bb0844a0d94_img-banner2.jpg',NULL,''),
('2','5','menu-banner2','index.php?id_category=41&controller=category','b32d5a1450bb36b7de489e4b8c4f5c632f8a6425_img-banner2.jpg',NULL,'');

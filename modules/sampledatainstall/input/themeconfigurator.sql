DROP TABLE IF EXISTS `ps_themeconfigurator`;
CREATE TABLE `ps_themeconfigurator` (
  `id_item` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `item_order` int(10) unsigned NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `title_use` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hook` varchar(100) DEFAULT NULL,
  `url` text,
  `target` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `image` varchar(100) DEFAULT NULL,
  `image_w` varchar(10) DEFAULT NULL,
  `image_h` varchar(10) DEFAULT NULL,
  `html` text,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_item`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

/* Scheme for table ps_themeconfigurator */
INSERT INTO `ps_themeconfigurator` VALUES
('1','1','1','1',NULL,'0','top','index.php?id_category=6&controller=category','0','4229279d62bf32e492c5345548b97582007380b5_banner-1.jpg','0','0','<h2>TRAIL <span>RUNNING</span></h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('2','1','2','1',NULL,'0','top','index.php?id_category=6&controller=category','0','4229279d62bf32e492c5345548b97582007380b5_banner-1.jpg','0','0','<h2>TRAIL <span>RUNNING</span></h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('3','1','3','1',NULL,'0','top','index.php?id_category=6&controller=category','0','4229279d62bf32e492c5345548b97582007380b5_banner-1.jpg','0','0','<h2>TRAIL <span>RUNNING</span></h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('4','1','4','1',NULL,'0','top','index.php?id_category=6&controller=category','0','4229279d62bf32e492c5345548b97582007380b5_banner-1.jpg','0','0','<h2>TRAIL <span>RUNNING</span></h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('5','1','5','1',NULL,'0','top','index.php?id_category=6&controller=category','0','4229279d62bf32e492c5345548b97582007380b5_banner-1.jpg','0','0','<h2>TRAIL <span>RUNNING</span></h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('6','1','1','2',NULL,'0','top','index.php?id_category=7&controller=category','0','1be55d46cdbc20cf4d32d129c78b6314f0f7e0c1_banner-2.jpg','0','0','<h2>SURFING</h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('7','1','2','2',NULL,'0','top','index.php?id_category=7&controller=category','0','1be55d46cdbc20cf4d32d129c78b6314f0f7e0c1_banner-2.jpg','0','0','<h2>SURFING</h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('8','1','3','2',NULL,'0','top','index.php?id_category=7&controller=category','0','1be55d46cdbc20cf4d32d129c78b6314f0f7e0c1_banner-2.jpg','0','0','<h2>SURFING</h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('9','1','4','2',NULL,'0','top','index.php?id_category=7&controller=category','0','1be55d46cdbc20cf4d32d129c78b6314f0f7e0c1_banner-2.jpg','0','0','<h2>SURFING</h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('10','1','5','2',NULL,'0','top','index.php?id_category=7&controller=category','0','1be55d46cdbc20cf4d32d129c78b6314f0f7e0c1_banner-2.jpg','0','0','<h2>SURFING</h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('11','1','1','3',NULL,'0','top','index.php?id_category=8&controller=category','0','4494a4fc07cb16cd36a98688616d370f63bc69ac_banner-3.jpg','0','0','<h2>SNOWBOARDING</h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('12','1','2','3',NULL,'0','top','index.php?id_category=8&controller=category','0','4494a4fc07cb16cd36a98688616d370f63bc69ac_banner-3.jpg','0','0','<h2>SNOWBOARDING</h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('13','1','3','3',NULL,'0','top','index.php?id_category=8&controller=category','0','4494a4fc07cb16cd36a98688616d370f63bc69ac_banner-3.jpg','0','0','<h2>SNOWBOARDING</h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('14','1','4','3',NULL,'0','top','index.php?id_category=8&controller=category','0','4494a4fc07cb16cd36a98688616d370f63bc69ac_banner-3.jpg','0','0','<h2>SNOWBOARDING</h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('15','1','5','3',NULL,'0','top','index.php?id_category=8&controller=category','0','4494a4fc07cb16cd36a98688616d370f63bc69ac_banner-3.jpg','0','0','<h2>SNOWBOARDING</h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('16','1','1','4',NULL,'0','top','index.php?id_category=9&controller=category','0','12dca28a27fa669b442b56770bdfa2c259c05e51_banner-4.jpg','0','0','<h2>ALPINE <span>CLIMBING</span></h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('17','1','2','4',NULL,'0','top','index.php?id_category=9&controller=category','0','12dca28a27fa669b442b56770bdfa2c259c05e51_banner-4.jpg','0','0','<h2>ALPINE <span>CLIMBING</span></h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('18','1','3','4',NULL,'0','top','index.php?id_category=9&controller=category','0','12dca28a27fa669b442b56770bdfa2c259c05e51_banner-4.jpg','0','0','<h2>ALPINE <span>CLIMBING</span></h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('19','1','4','4',NULL,'0','top','index.php?id_category=9&controller=category','0','12dca28a27fa669b442b56770bdfa2c259c05e51_banner-4.jpg','0','0','<h2>ALPINE <span>CLIMBING</span></h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('20','1','5','4',NULL,'0','top','index.php?id_category=9&controller=category','0','12dca28a27fa669b442b56770bdfa2c259c05e51_banner-4.jpg','0','0','<h2>ALPINE <span>CLIMBING</span></h2>\r\n<p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labor.</p>\r\n<button><span>Shop now!</span></button>','1'),
('21','1','1','1',NULL,'0','home','index.php?id_category=11&controller=category','0',NULL,'0','0','<i class=\"fa fa-truck\"></i>\r\n<h2>Free shipping on <span>orders over $99</span></h2><h2>\r\n</h2><p>Lorem ipsum dolor sit amet conse ctetur adipi sicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>','1'),
('22','1','2','1',NULL,'0','home','index.php?id_category=11&controller=category','0',NULL,'0','0','<i class=\"fa fa-truck\"></i>\r\n<h2>Free shipping on <span>orders over $99</span></h2><h2>\r\n</h2><p>Lorem ipsum dolor sit amet conse ctetur adipi sicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>','1'),
('23','1','3','1',NULL,'0','home','index.php?id_category=11&controller=category','0',NULL,'0','0','<i class=\"fa fa-truck\"></i>\r\n<h2>Free shipping on <span>orders over $99</span></h2><h2>\r\n</h2><p>Lorem ipsum dolor sit amet conse ctetur adipi sicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>','1'),
('24','1','4','1',NULL,'0','home','index.php?id_category=11&controller=category','0',NULL,'0','0','<i class=\"fa fa-truck\"></i>\r\n<h2>Free shipping on <span>orders over $99</span></h2><h2>\r\n</h2><p>Lorem ipsum dolor sit amet conse ctetur adipi sicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>','1'),
('25','1','5','1',NULL,'0','home','index.php?id_category=11&controller=category','0',NULL,'0','0','<i class=\"fa fa-truck\"></i>\r\n<h2>Free shipping on <span>orders over $99</span></h2><h2>\r\n</h2><p>Lorem ipsum dolor sit amet conse ctetur adipi sicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>','1'),
('26','1','1','2',NULL,'0','home','index.php?id_category=12&controller=category','0',NULL,'0','0','<i class=\"fa fa-thumbs-o-up\"></i>\r\n<h2>Satisfaction <span>100% Guaranteed</span></h2><h2>\r\n</h2><p>Lorem ipsum dolor sit amet conse ctetur adipi sicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>','1'),
('27','1','2','2',NULL,'0','home','index.php?id_category=12&controller=category','0',NULL,'0','0','<i class=\"fa fa-thumbs-o-up\"></i>\r\n<h2>Satisfaction <span>100% Guaranteed</span></h2><h2>\r\n</h2><p>Lorem ipsum dolor sit amet conse ctetur adipi sicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>','1'),
('28','1','3','2',NULL,'0','home','index.php?id_category=12&controller=category','0',NULL,'0','0','<i class=\"fa fa-thumbs-o-up\"></i>\r\n<h2>Satisfaction <span>100% Guaranteed</span></h2><h2>\r\n</h2><p>Lorem ipsum dolor sit amet conse ctetur adipi sicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>','1'),
('29','1','4','2',NULL,'0','home','index.php?id_category=12&controller=category','0',NULL,'0','0','<i class=\"fa fa-thumbs-o-up\"></i>\r\n<h2>Satisfaction <span>100% Guaranteed</span></h2><h2>\r\n</h2><p>Lorem ipsum dolor sit amet conse ctetur adipi sicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>','1'),
('30','1','5','2',NULL,'0','home','index.php?id_category=12&controller=category','0',NULL,'0','0','<i class=\"fa fa-thumbs-o-up\"></i>\r\n<h2>Satisfaction <span>100% Guaranteed</span></h2><h2>\r\n</h2><p>Lorem ipsum dolor sit amet conse ctetur adipi sicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>','1'),
('31','1','1','3',NULL,'0','home','index.php?id_category=13&controller=category','0',NULL,'0','0','<i class=\"fa fa-refresh\"></i>\r\n<h2>14 Day <span>Easy return</span></h2><h2>\r\n</h2><p>Lorem ipsum dolor sit amet conse ctetur adipi sicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>','1'),
('32','1','2','3',NULL,'0','home','index.php?id_category=13&controller=category','0',NULL,'0','0','<i class=\"fa fa-refresh\"></i>\r\n<h2>14 Day <span>Easy return</span></h2><h2>\r\n</h2><p>Lorem ipsum dolor sit amet conse ctetur adipi sicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>','1'),
('33','1','3','3',NULL,'0','home','index.php?id_category=13&controller=category','0',NULL,'0','0','<i class=\"fa fa-refresh\"></i>\r\n<h2>14 Day <span>Easy return</span></h2><h2>\r\n</h2><p>Lorem ipsum dolor sit amet conse ctetur adipi sicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>','1'),
('34','1','4','3',NULL,'0','home','index.php?id_category=13&controller=category','0',NULL,'0','0','<i class=\"fa fa-refresh\"></i>\r\n<h2>14 Day <span>Easy return</span></h2><h2>\r\n</h2><p>Lorem ipsum dolor sit amet conse ctetur adipi sicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>','1'),
('35','1','5','3',NULL,'0','home','index.php?id_category=13&controller=category','0',NULL,'0','0','<i class=\"fa fa-refresh\"></i>\r\n<h2>14 Day <span>Easy return</span></h2><h2>\r\n</h2><p>Lorem ipsum dolor sit amet conse ctetur adipi sicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>','1');

CREATE TABLE IF NOT EXISTS `PREFIX_block_product_detail`(
	`id_product` int(10) NOT NULL,
	`block_ip` text,
	`block_country` text,
	`active` tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY  (`id_product`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;
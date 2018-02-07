/* Slides */
CREATE TABLE IF NOT EXISTS `PREFIX_slider_slides` (
    `id_slider_slides` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `id_shop` int(10) unsigned NOT NULL,
    `position` int(10) unsigned NOT NULL DEFAULT 0,
    `active` tinyint(1) unsigned NOT NULL DEFAULT 0,
    `blank` tinyint(1) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY (`id_slider_slides`, `id_shop`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=UTF8;

/* Slides lang configuration */
CREATE TABLE IF NOT EXISTS `PREFIX_slider_slides_lang` (
    `id_slider_slides` int(10) unsigned NOT NULL,
    `id_lang` int(10) unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    `link` varchar(255) NOT NULL,
    `image` varchar(255) NOT NULL,
    `content` text NOT NULL,
    `button` varchar(255) NOT NULL,
    PRIMARY KEY (`id_slider_slides`,`id_lang`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=UTF8;
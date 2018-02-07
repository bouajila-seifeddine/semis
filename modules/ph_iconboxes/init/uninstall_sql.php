<?php
/**
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/
$sql = array();

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'prestahome_iconbox`';

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'prestahome_iconbox_lang`';

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'prestahome_iconbox_shop`';

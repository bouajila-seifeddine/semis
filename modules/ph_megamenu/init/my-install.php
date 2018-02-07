<?php
/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
/**
* @author    PrestaHome Team <support@prestahome.com>
* @copyright  Copyright (c) 2014-2015 PrestaHome Team - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/
$access = array();
$groups = Group::getGroups(Context::getContext()->language->id);

foreach ($groups as $group)
{
    $access[$group['id_group']] = true;
}

$home_link = new PrestaHomeMegaMenu();
foreach (Language::getLanguages(true) as $lang)
	$home_link->url[$lang['id_lang']] = Context::getContext()->shop->getBaseURL();

foreach (Language::getLanguages(true) as $lang)
	$home_link->title[$lang['id_lang']] = 'Home';

$home_link->icon = 'fa-home';
$home_link->display_title = 0;
$home_link->access = serialize($access);
$home_link->add();

#################################################

$support_link = new PrestaHomeMegaMenu();
foreach (Language::getLanguages(true) as $lang)
	$support_link->url[$lang['id_lang']] = 'https://addons.prestashop.com/contact-community.php?id_product=17128';

foreach (Language::getLanguages(true) as $lang)
	$support_link->title[$lang['id_lang']] = 'Need Support?';

$support_link->access = serialize($access);

$support_link->add();

#################################################

$prestahome_link_base = new PrestaHomeMegaMenu();
foreach (Language::getLanguages(true) as $lang)
	$prestahome_link_base->url[$lang['id_lang']] = 'http://www.prestahome.com';

foreach (Language::getLanguages(true) as $lang)
	$prestahome_link_base->title[$lang['id_lang']] = 'PrestaHome';

foreach (Language::getLanguages(true) as $lang)
	$prestahome_link_base->label_text[$lang['id_lang']] = 'Follow us!';

$prestahome_link_base->access = serialize($access);

$prestahome_link_base->add();

#################################################

$prestahome_link_facebook = new PrestaHomeMegaMenu();
foreach (Language::getLanguages(true) as $lang)
	$prestahome_link_facebook->url[$lang['id_lang']] = 'http://www.facebook.com/PrestaHome';

foreach (Language::getLanguages(true) as $lang)
	$prestahome_link_facebook->title[$lang['id_lang']] = 'Facebook';

$prestahome_link_facebook->icon = 'fa-facebook';
$prestahome_link_facebook->id_parent = $prestahome_link_base->id;

$prestahome_link_facebook->access = serialize($access);

$prestahome_link_facebook->add();

#################################################

$prestahome_link_twitter = new PrestaHomeMegaMenu();
foreach (Language::getLanguages(true) as $lang)
	$prestahome_link_twitter->url[$lang['id_lang']] = 'http://www.twitter.com/PrestaHome';

foreach (Language::getLanguages(true) as $lang)
	$prestahome_link_twitter->title[$lang['id_lang']] = 'Twitter';

$prestahome_link_twitter->icon = 'fa-twitter';
$prestahome_link_twitter->id_parent = $prestahome_link_base->id;

$prestahome_link_twitter->access = serialize($access);

$prestahome_link_twitter->add();

#################################################

$prestahome_link_tf = new PrestaHomeMegaMenu();
foreach (Language::getLanguages(true) as $lang)
	$prestahome_link_tf->url[$lang['id_lang']] = 'https://addons.prestashop.com/en/2_community?contributor=67059';

foreach (Language::getLanguages(true) as $lang)
	$prestahome_link_tf->title[$lang['id_lang']] = 'PrestaHome Portfolio';

$prestahome_link_tf->icon = 'fa-heart';
$prestahome_link_tf->id_parent = $prestahome_link_base->id;

$prestahome_link_tf->access = serialize($access);

$prestahome_link_tf->add();

#################################################

$contact_link = new PrestaHomeMegaMenu();
foreach (Language::getLanguages(true) as $lang)
	$contact_link->url[$lang['id_lang']] = 'index.php?controller=contact';

foreach (Language::getLanguages(true) as $lang)
	$contact_link->title[$lang['id_lang']] = 'Contact';

$contact_link->icon = 'fa-envelope';

$contact_link->access = serialize($access);

$contact_link->add();
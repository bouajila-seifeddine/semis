<?php
/**
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
 * @author PrestaHome Team <support@prestahome.com>
 * @copyright Copyright (c) 2014-2015 PrestaHome Team - www.PrestaHome.com
 * @license You only can use module, nothing more!
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

$sections = array();

$sections[] = array(
    'title' => $this->l('General settings'),
    'icon' => 'icon icon-cogs',
    'fields' => array(

        array(
            'id' => 'purchase_code',
            'type' => 'text',
            'title' => $this->l('Purchase code'),
            'desc' => $this->l('In order to have ability to use auto-update mechanism and few other features of the theme you need to provide valid purchase code. You can find your Purchase Code by going to item page, click the "Support" tab, and scroll down to the bottom of the page.'),
            'default' => '',
        ),

        array(
            'id' => 'check_for_updates',
            'type' => 'switch',
            'title' => $this->l('Check for theme updates?'),
            'default' => '1',
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'separator',
        ),

        array(
            'id' => 'ganalytics',
            'type' => 'module',
            'module' => 'ganalytics',
            'title' => $this->l('Google Analytics'),
            'desc' => $this->l('You can paste your Google Analytics tracking code using native PrestaShop module.'),
            'configuration' => true,
            'non_exists' => $this->l('You can get this module from addons.prestashop.com - http://addons.prestashop.com/en/natif/4168-ganalytics.html'),
        ),
    )
);

$sections[] = array(
    'title' => $this->l('Top bar'),
    'icon' => 'icon icon-gear',
    'fields' => array(
        array(
            'id' => 'ph_show_topbar',
            'type' => 'switch',
            'title' => $this->l('Show top bar?'),
            'default' => '1',
        ),
        array(
            'id' => 'blockcurrencies',
            'type' => 'module',
            'module' => 'blockcurrencies',
            'title' => $this->l('Block with currencies'),
        ),

        array(
            'id' => 'blocklanguages',
            'type' => 'module',
            'module' => 'blocklanguages',
            'title' => $this->l('Block with languages'),
        ),
    )
);

$sections[] = array(
    'title' => $this->l('Header'),
    'icon' => 'icon icon-gear',
    'fields' => array(

        array(
            'id' => 'header_background_image',
            'type' => 'uploadImage',
            'title' => $this->l('Background image for header:'),
            'desc' => $this->l('You can select photo, pattern, anything. One options gives you thousands of possibilities to makes your store looks amazing.'),
            'default' => '',
            'css' => array(
                'selector' => 'header.top .pattern',
                'property' => 'background-image',
            )
        ),

        array(
            'id' => 'header_background_height',
            'type' => 'text',
            'title' => $this->l('Height for header background'),
            'default' => '143px',
            'css' => array(
                'selector' => 'header.top .pattern',
                'property' => 'height',
            ),
            'required' => false,
        ),

        array(
            'id' => 'header_background_attachment',
            'type' => 'select',
            'title' => $this->l('Background attachment:'),
            'options' => array(
                'scroll' => 'Scroll',
                'fixed' => 'Fixed',
                'local' => 'Local',
                'initial' => 'Initial',
                'inherit' => 'Inherit'
            ),
            'default' => 'scroll',
            'css' => array(
                'selector' => 'header.top .pattern',
                'property' => 'background-attachment',
            )
        ),

        array(
            'id' => 'header_background_repeat',
            'type' => 'select',
            'title' => $this->l('Background repeat:'),
            'options' => array(
                'no-repeat' => 'No repeat',
                'repeat' => 'Repeat All',
                'repeat-x' => 'Repeat Horizontally',
                'repeat-y' => 'Repeat Vertically'
            ),
            'default' => 'no-repeat',
            'css' => array(
                'selector' => 'header.top .pattern',
                'property' => 'background-repeat',
            )
        ),

        array(
            'id' => 'header_background_size',
            'type' => 'select',
            'title' => $this->l('Background size:'),
            'options' => array(
                'inherit' => 'Inherit',
                'cover' => 'Cover',
                'contain' => 'Contain',
            ),
            'default' => 'inherit',
            'css' => array(
                'selector' => 'header.top .pattern',
                'property' => 'background-size',
            )
        ),

        array(
            'id' => 'header_background_position',
            'type' => 'select',
            'title' => $this->l('Background position:'),
            'options' => array(
                'left top' => 'Left Top',
                'left center' => 'Left center',
                'left bottom' => 'Left bottom',
                'center top' => 'center top',
                'center center' => 'center center',
                'center bottom' => 'center bottom',
                'right top' => 'right top',
                'right center' => 'right center',
                'right bottom' => 'right bottom',
            ),
            'default' => 'left top',
            'css' => array(
                'selector' => 'header.top .pattern',
                'property' => 'background-position',
            )
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'separator',
        ),


        array(
            'id' => 'menutop_sticky',
            'type' => 'select',
            'title' => $this->l('Sticky effect:'),
            'options' => array(
                'header' => 'Header sticky',
                'without' => 'Without sticky effect',
            ),
            'default' => 'without',
        ),
       
        array(
            'id' => 'blockcart',
            'type' => 'module',
            'module' => 'blockcart',
            'title' => $this->l('Basket and user information'),
        ),

        array(
            'id' => 'ph_megamenu',
            'type' => 'module',
            'module' => 'ph_megamenu',
            'title' => $this->l('Mega Menu'),
            'desc' => $this->l('You can manage Mega Menu by going to Preferences -> Mega Menu'),
            'configuration' => false,
        ),

        array(
            'id' => 'blocksearch',
            'type' => 'module',
            'module' => 'blocksearch',
            'title' => $this->l('Search bar'),
        ),
    )
);

$sections[] = array(
    'title' => $this->l('Footer'),
    'icon' => 'icon icon-minus',
    'fields' => array(
        array(
            'id' => 'copyright',
            'type' => 'textLang',
            'title' => $this->l('Copyright text'),
            'desc' => $this->l('Appears at the bottom of the store'),
            'default' => PrestaHomeOptions::prepareValueForLangs('All rights reserved'),
        ),

        array(
            'id' => 'ph_totop_button',
            'type' => 'switch',
            'title' => $this->l('Show back to top arrow?'),
            'default' => '1',
        ),

        array(
            'id' => 'blockcategories',
            'type' => 'module',
            'module' => 'blockcategories',
            'title' => $this->l('Categories'),
        ),

        array(
            'id' => 'blockcms',
            'type' => 'module',
            'module' => 'blockcms',
            'title' => $this->l('CMS Block'),
            'configuration' => true,
        ),

        array(
            'id' => 'blockcontactinfos',
            'type' => 'module',
            'module' => 'blockcontactinfos',
            'title' => $this->l('Contact Informations'),
            'configuration' => true,
        ),

        array(
            'id' => 'blockmyaccountfooter',
            'type' => 'module',
            'module' => 'blockmyaccountfooter',
            'title' => $this->l('My Account Block'),
            'configuration' => true,
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Social box'),
        ),

        array(
            'id' => 'ph_socialbox_fb',
            'type' => 'textLang',
            'title' => $this->l('Facebook link'),
            'default' => PrestaHomeOptions::prepareValueForLangs('http://facebook.com'),
            'required' => false,
        ),
        
        array(
            'id' => 'ph_socialbox_g',
            'type' => 'textLang',
            'title' => $this->l('Google+ link'),
            'default' => PrestaHomeOptions::prepareValueForLangs('http://plus.google.com/'),
            'required' => false,
        ),
        
        
        array(
            'id' => 'ph_socialbox_tw',
            'type' => 'textLang',
            'title' => $this->l('Twitter link'),
            'default' => PrestaHomeOptions::prepareValueForLangs('http://twitter.com/'),
            'required' => false,
        ),
        
        array(
            'id' => 'ph_socialbox_dribbble',
            'type' => 'textLang',
            'title' => $this->l('Dribbble link'),
            'default' => PrestaHomeOptions::prepareValueForLangs('http://dribbble.com/'),
            'required' => false,
        ),

        array(
            'id' => 'ph_socialbox_be',
            'type' => 'textLang',
            'title' => $this->l('Behance link'),
            'default' => PrestaHomeOptions::prepareValueForLangs('http://behance.net/'),
            'required' => false,
        ),
    )
);

$sections[] = array(
    'title' => $this->l('Homepage'),
    'icon' => 'icon icon-home',
    'fields' => array(

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Sliders and Icons'),
        ),

        array(
            'id' => 'homeslider',
            'type' => 'module',
            'module' => 'homeslider',
            'title' => $this->l('Simple slider - homeslider'),
            'configuration' => true,
        ),

        array(
            'id' => 'revsliderprestashop',
            'type' => 'module',
            'module' => 'revsliderprestashop',
            'title' => $this->l('SliderRevolution'),
            'configuration' => true,
        ),

        array(
            'id' => 'ph_iconboxes',
            'type' => 'module',
            'module' => 'ph_iconboxes',
            'title' => $this->l('Icon boxes module'),
            'configuration' => true,
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Carousels'),
        ),

        array(
            'id' => 'ph_homepage_carousel',
            'type' => 'switch',
            'title' => $this->l('Use carousels for products on homepage?'),
            'desc' => $this->l('You can disable carousels on homepage if you want. Remember to setup number of displayed products in each of homepage products module.'),
            'default' => '1',
        ),

        array(
            'id' => 'autoplay_carousels_start',
            'type' => 'switch',
            'title' => $this->l('Use autoplay for carousels?'),
            'default' => '0',
        ),

        array(
            'id' => 'autoplay_carousels',
            'type' => 'text',
            'title' => $this->l('Speed of carousel slides'),
            'default' => '5000',
            'required' => false,
        ),

        array(
            'id' => 'items_two_carousels',
            'type' => 'select',
            'title' => $this->l('Number of items per line'),
            'desc' => $this->l('When enable are two columns'),
            'options' => array(
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ),
            'default' => '3',
            'required' => false,
        ),

        array(
            'id' => 'items_one_carousels',
            'type' => 'select',
            'title' => $this->l('Number of items per line'),
            'desc' => $this->l('When enable is only one column'),
            'options' => array(
                '2' => '2',
                '4' => '4',
                '6' => '6',
            ),
            'default' => '4',
            'required' => false,
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Modules'),
        ),

        array(
            'id' => 'homefeatured',
            'type' => 'module',
            'module' => 'homefeatured',
            'title' => $this->l('Featured products'),
            'configuration' => true,
        ),

        array(
            'id' => 'blocknewproducts',
            'type' => 'module',
            'module' => 'blocknewproducts',
            'title' => $this->l('New products'),
            'configuration' => true,
        ),

        array(
            'id' => 'blockbestsellers',
            'type' => 'module',
            'module' => 'blockbestsellers',
            'title' => $this->l('Best sales'),
            'configuration' => true,
        ),

        array(
            'id' => 'blockmanufacturer',
            'type' => 'module',
            'module' => 'blockmanufacturer',
            'title' => $this->l('Manufacturers carousel'),
        ),

        array(
            'id' => 'ph_reviewscarousel',
            'type' => 'module',
            'module' => 'ph_reviewscarousel',
            'title' => $this->l('Reviews carousel'),
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Blog'),
        ),

        array(
            'id' => 'ph_recentposts',
            'type' => 'module',
            'module' => 'ph_recentposts',
            'title' => $this->l('Recent posts'),
        ),

        array(
            'id' => 'ph_recentposts_title',
            'type' => 'textLang',
            'title' => $this->l('Title - bold'),
            'default' => PrestaHomeOptions::prepareValueForLangs('latest'),
        ),
        
        array(
            'id' => 'ph_recentposts_title_two',
            'type' => 'textLang',
            'title' => $this->l('Title'),
            'default' => PrestaHomeOptions::prepareValueForLangs('blog posts'),
        ),

        array(
            'id' => 'ph_recentposts_viewall',
            'type' => 'textLang',
            'title' => $this->l('View all posts button text'),
            'default' => PrestaHomeOptions::prepareValueForLangs('read our blog'),
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'separator',
        ),

        array(
            'id' => 'recent_background_image',
            'type' => 'uploadImage',
            'title' => $this->l('Background image for recent blog posts:'),
            'desc' => $this->l('You can select photo, pattern, anything. One options gives you thousands of possibilities to makes your store looks amazing.'),
            'default' => '',
            'css' => array(
                'selector' => 'body div.simpleblog-recent',
                'property' => 'background-image',
            )
        ),

        array(
            'id' => 'recent_background_attachment',
            'type' => 'select',
            'title' => $this->l('Background attachment:'),
            'options' => array(
                'scroll' => 'Scroll',
                'fixed' => 'Fixed',
                'local' => 'Local',
                'initial' => 'Initial',
                'inherit' => 'Inherit'
            ),
            'default' => 'scroll',
            'css' => array(
                'selector' => 'body div.simpleblog-recent',
                'property' => 'background-attachment',
            )
        ),

        array(
            'id' => 'recent_background_repeat',
            'type' => 'select',
            'title' => $this->l('Background repeat:'),
            'options' => array(
                'no-repeat' => 'No repeat',
                'repeat' => 'Repeat All',
                'repeat-x' => 'Repeat Horizontally',
                'repeat-y' => 'Repeat Vertically'
            ),
            'default' => 'no-repeat',
            'css' => array(
                'selector' => 'body div.simpleblog-recent',
                'property' => 'background-repeat',
            )
        ),

        array(
            'id' => 'recent_background_size',
            'type' => 'select',
            'title' => $this->l('Background size:'),
            'options' => array(
                'inherit' => 'Inherit',
                'cover' => 'Cover',
                'contain' => 'Contain',
            ),
            'default' => 'inherit',
            'css' => array(
                'selector' => 'body div.simpleblog-recent',
                'property' => 'background-size',
            )
        ),

        array(
            'id' => 'recent_background_position',
            'type' => 'select',
            'title' => $this->l('Background position:'),
            'options' => array(
                'left top' => 'Left Top',
                'left center' => 'Left center',
                'left bottom' => 'Left bottom',
                'center top' => 'center top',
                'center center' => 'center center',
                'center bottom' => 'center bottom',
                'right top' => 'right top',
                'right center' => 'right center',
                'right bottom' => 'right bottom',
            ),
            'default' => 'left top',
            'css' => array(
                'selector' => 'body div.simpleblog-recent',
                'property' => 'background-position',
            )
        ),
    )
);

$sections[] = array(
    'title' => $this->l('Category page'),
    'icon' => 'icon icon-list',
    'fields' => array(
                array(
            'id' => 'show_category_title',
            'type' => 'switch',
            'title' => $this->l('Display category title?'),
            'desc' => $this->l('We recommend always display the title'),
            'default' => '1',
            'label_on' => $this->l('Yes'),
            'label_off' => $this->l('No'),
        ),
        array(
            'id' => 'show_category_image',
            'type' => 'switch',
            'title' => $this->l('Display category image/scene image?'),
            'default' => '1',
            'label_on' => $this->l('Yes'),
            'label_off' => $this->l('No'),
        ),

        array(
            'id' => 'show_category_description',
            'type' => 'switch',
            'title' => $this->l('Display category description?'),
            'default' => '1',
            'label_on' => $this->l('Yes'),
            'label_off' => $this->l('No'),
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'separator',
        ),

        array(
            'id' => 'show_subcategories',
            'type' => 'switch',
            'title' => $this->l('Display subcategories?'),
            'default' => '1',
            'label_on' => $this->l('Yes'),
            'label_off' => $this->l('No'),
        ),

        array(
            'id' => 'show_subcategories_title',
            'type' => 'switch',
            'title' => $this->l('Display subcategories title?'),
            'default' => '1',
            'label_on' => $this->l('Yes'),
            'label_off' => $this->l('No'),
        ),
    )
);

$sections[] = array(
    'title' => $this->l('Product page'),
    'icon' => 'icon icon-file',
    'fields' => array(

        array(
            'id' => 'socialsharing',
            'type' => 'module',
            'module' => 'socialsharing',
            'title' => $this->l('Social icons'),
            'configuration' => true,
        ),

        array(
            'id' => 'productcomments',
            'type' => 'module',
            'module' => 'productcomments',
            'title' => $this->l('Product comments'),
            'configuration' => true,
        ),

        array(
            'id' => 'productscategory',
            'type' => 'module',
            'module' => 'productscategory',
            'title' => $this->l('Products from the same category'),
            'configuration' => true,
        ),

        array(
            'id' => 'crossselling',
            'type' => 'module',
            'module' => 'crossselling',
            'title' => $this->l('Crossselling'),
            'configuration' => true,
        ),

        array(
            'id' => 'sendtoafriend',
            'type' => 'module',
            'module' => 'sendtoafriend',
            'title' => $this->l('Send to friend module'),
        ),

        array(
            'id' => 'ph_relatedposts',
            'type' => 'module',
            'module' => 'ph_relatedposts',
            'title' => $this->l('Related posts'),
            'desc' => $this->l('Related posts from blog'),

        ),
    )
);

$sections[] = array(
    'title' => $this->l('Product lists'),
    'icon' => 'icon icon-list',
    'fields' => array(

         array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'info-box',
            'title' => $this->l('IMPORTANT:'),
            'desc' => $this->l('Remember: All options set here also apply to lists of products on the main page.'),
        ),

        // array(
        //     'id' => 'product_list_layout',
        //     'type' => 'select',
        //     'title' => $this->l('Default product list layout:'),
        //     'options' => array(
        //         'grid' => 'Grid',
        //         'list' => 'List',
        //     ),
        //     'default' => 'grid',
        //     'desc' => $this->l('Important: This option not apply to product lists on homepage'),
        // ),

        array(
            'id' => 'ph_list_items',
            'type' => 'select',
            'title' => $this->l('Number of items per line'),
            'options' => array(
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '6' => '6',
            ),
            'default' => '3',
            'required' => false,
        ),

        array(
            'id' => 'ph_quickview',
            'type' => 'switch',
            'title' => $this->l('Enable Quick View?'),
            'default' => '1',
            'label_on' => $this->l('Yes'),
            'label_off' => $this->l('No'),
        ),

        array(
            'id' => 'ph_display_add2cart',
            'type' => 'switch',
            'title' => $this->l('Display "Add to cart" button?'),
            'desc' => $this->l('Button of course will appear only if product is available for order'),
            'default' => '1',
            'label_on' => $this->l('Yes'),
            'label_off' => $this->l('No'),
        ),
        
        array(
            'id' => 'ph_display_price_wo_reduction',
            'type' => 'switch',
            'title' => $this->l('Display price without reduction?'),
            'default' => '1',
            'label_on' => $this->l('Yes'),
            'label_off' => $this->l('No'),
        ),

        array(
            'id' => 'ph_display_noavailable_text',
            'type' => 'switch',
            'title' => $this->l('Display "No available" text?'),
            'desc' => $this->l('If product is no longer available for order you can display "No available" text on product lists instead of "Add to cart" button'),
            'default' => '1',
            'label_on' => $this->l('Yes'),
            'label_off' => $this->l('No'),
        ),

        array(
            'id' => 'ph_noavailable_text',
            'type' => 'textLang',
            'title' => $this->l('Custom "No available" text'),
            'default' => PrestaHomeOptions::prepareValueForLangs('Sold out'),
            'required' => false,
        ),
    )
);

$sections[] = array(
    'title' => $this->l('Sidebars'),
    'icon' => 'icon icon-columns',
    'fields' => array(

                array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'info-box',
            'title' => $this->l('IMPORTANT:'),
            'desc' => $this->l('Sometimes, it may be that you turn on the module, but can not see it. To fix this, go to Modules tab and reinstall this unvisible module.'),
        ),

        array(
            'id' => 'blockcategories',
            'type' => 'module',
            'module' => 'blockcategories',
            'title' => $this->l('Categories block'),
            'configuration' => true,
        ),

         array(
            'id' => 'blockmyaccount',
            'type' => 'module',
            'module' => 'blockmyaccount',
            'title' => $this->l('My Account Block'),
            'configuration' => false,
        ),

        array(
            'id' => 'blocklayered',
            'type' => 'module',
            'module' => 'blocklayered',
            'title' => $this->l('Layered navigation block'),
            'configuration' => true,
        ),

        array(
            'id' => 'blocktags',
            'type' => 'module',
            'module' => 'blocktags',
            'title' => $this->l('Tags'),
            'configuration' => true,
        ),

        array(
            'id' => 'blockcms',
            'type' => 'module',
            'module' => 'blockcms',
            'title' => $this->l('Block cms information'),
            'configuration' => true,
        ),

        array(
            'id' => 'blockviewed',
            'type' => 'module',
            'module' => 'blockviewed',
            'title' => $this->l('Last viewed products'),
            'configuration' => false,
        ),

        array(
            'id' => 'blocknewproducts',
            'type' => 'module',
            'module' => 'blocknewproducts',
            'title' => $this->l('Block new products'),
            'configuration' => true,
        ),

        array(
            'id' => 'blockspecials',
            'type' => 'module',
            'module' => 'blockspecials',
            'title' => $this->l('Block special products'),
            'configuration' => true,
        ),

        array(
            'id' => 'blockwishlist',
            'type' => 'module',
            'module' => 'blockwishlist',
            'title' => $this->l('Wish list block'),
            'configuration' => false,
        ),

    )
);

/**


        Google fonts options


**/

$sections[] = array(
    'title' => $this->l('Google fonts'),
    'icon' => 'icon icon-font',
    'fields' => array(

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'info-box',
            'title' => $this->l('IMPORTANT:'),
            'desc' => $this->l('Font settings (size, name) from "Theme Style" page works only if you set "Use custom fonts settings?" to "Yes"'),
        ),

        array(
            'id' => 'use_custom_fonts',
            'type' => 'switch',
            'title' => $this->l('Use custom fonts settings?'),
            'default' => '0',
            'label_on' => $this->l('Yes'),
            'label_off' => $this->l('No'),
        ),

        array(
            'id' => 'include_google_cyrillic',
            'type' => 'switch',
            'title' => $this->l('Include Cyrillic subsets?'),
            'default' => '0',
            'label_on' => $this->l('Yes'),
            'label_off' => $this->l('No'),
        ),

        array(
            'id' => 'include_google_greek',
            'type' => 'switch',
            'title' => $this->l('Include Greek subsets?'),
            'default' => '0',
            'label_on' => $this->l('Yes'),
            'label_off' => $this->l('No'),
        ),
    )
);

/**


        THEME STYLE SECTION


**/

$sections[] = array(
    'title' => $this->l('Theme style'),
    'icon' => 'icon icon-asterisk',
    'fields' => array(

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Fonts'),
        ),

        array(
            'id' => 'normal_font_family',
            'type' => 'font',
            'title' => $this->l('Primary font-family:'),
            'desc' => $this->l('Global font used in text, tables etc.'),
            'default' => 'PT Sans',
            'css' => array(
                'selector' => 'body',
                'property' => 'font-family',
                'dependency' => array(
                    'use_custom_fonts' => '1'
                ),
            ),
        ),

        array(
            'id' => 'secondary_font_family',
            'type' => 'font',
            'title' => $this->l('Secondary font-family:'),
            'desc' => $this->l('Font used in headings, price, labels'),
            'default' => 'Roboto Slab',
            'css' => array(
                'selector' => '.page-heading > span, .button-mini, .shopping_cart .cart_block dl dt .price, .slider .text h1, .slider .text h2, .banner-separate .banner-content h2,
div.heading_block h3, div.heading_block h4, #layered_block_left .layered_subtitle, #layered_block_left #enabled_filters .layered_subtitle,
.product_list_ph .product .labels, .product_list_ph .product .info h3, .product_list_ph .product .info span.price, .product_list_ph .product .info span.old-price,
#newsletterRegistrationForm h5, #newsletterRegistrationForm .button, .prefooter .block_footer h4, body.pagenotfound h1, body.pagenotfound h2, body.pagenotfound h3,
form.std h3 span, .send_friend_form_content h3 span, .label, .nav-tabs > li, .reviews-carousel-wrapper .reviews-carousel-item .see-more-btn, table#product_comparison a.product-name,
table.table tbody td.cart_avail .label, table.table tbody td.cart_quantity .cart_quantity_input, table.table tbody td.cart_discount_delete .cart_quantity_input, table.table tbody td.cart_quantity .cart_ph_input, 
table.table tbody td.cart_discount_delete .cart_ph_input, table.table .price, #order_step li, .content_scene_cat_bg .cat_desc h2, #subcategories h4, #image-block .labels',
                'property' => 'font-family',
                'dependency' => array(
                    'use_custom_fonts' => '1'
                ),
            ),
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Global options'),
        ),

        array(
            'id' => 'ph_layout',
            'type' => 'select',
            'title' => $this->l('Site style:'),
            'options' => array(
                'wide' => 'Wide',
                'boxed' => 'Boxed'
            ),
            'default' => 'wide',
        ),

        array(
            'id' => 'ph_site_width',
            'type' => 'select',
            'title' => $this->l('Site max width:'),
            'options' => array(
                '1140px' => '1140px',
                '1400px' => '1400px',
                '100%' => '100%'
            ),
            'default' => '1140px',
            'css' => array(
                'selector' => '.container',
                'property' => 'width',
                'dependency' => array(
                    'ph_layout' => 'wide'
                ),
                'before' => '@media (min-width: 1400px) {',
                'after' => '}',
            ),
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'separator',
        ),

        array(
            'id' => 'global_style',
            'type' => 'select',
            'title' => $this->l('Color scheme:'),
            'desc' => $this->l('Hint: If you choose "Custom"  you\'ll be able to set custom colors and create your own color scheme.'),
            'options' => array(
                'default' => 'Default',
                'blue' => 'Blue',
                'green' => 'Green',
                'yellow' => 'Yellow',
                'grey' => 'Grey',
                'custom' => 'Custom',
            ),
            'default' => 'default',
        ),

        /**


        BACKGROUND SECTION


        **/

        array(
            'id' => 'background_image',
            'type' => 'uploadImage',
            'title' => $this->l('Background image for boxed version:'),
            'desc' => $this->l('You can select photo, pattern, anything. One options gives you thousands of possibilities to makes your store looks amazing.'),
            //'default' => array('url' => '[theme_url]images/subcategory_img.jpg', 'path' => '[theme_path]images/subcategory_img.jpg'),
            'default' => '',
            'css' => array(
                'selector' => 'body.boxed',
                'property' => 'background-image',
                'dependency' => array(
                    'ph_layout' => 'boxed'
                ),
            )
        ),

        array(
            'id' => 'background_attachment',
            'type' => 'select',
            'title' => $this->l('Background attachment:'),
            'options' => array(
                'scroll' => 'Scroll',
                'fixed' => 'Fixed',
                'local' => 'Local',
                'initial' => 'Initial',
                'inherit' => 'Inherit'
            ),
            'default' => 'scroll',
            'css' => array(
                'selector' => 'body.boxed',
                'property' => 'background-attachment',
                'dependency' => array(
                    'ph_layout' => 'boxed'
                ),
            )
        ),

        array(
            'id' => 'background_repeat',
            'type' => 'select',
            'title' => $this->l('Background repeat:'),
            'options' => array(
                'no-repeat' => 'No repeat',
                'repeat' => 'Repeat All',
                'repeat-x' => 'Repeat Horizontally',
                'repeat-y' => 'Repeat Vertically'
            ),
            'default' => 'repeat',
            'css' => array(
                'selector' => 'body.boxed',
                'property' => 'background-repeat',
                'dependency' => array(
                    'ph_layout' => 'boxed'
                ),
            )
        ),

        array(
            'id' => 'background_size',
            'type' => 'select',
            'title' => $this->l('Background size:'),
            'options' => array(
                'inherit' => 'Inherit',
                'cover' => 'Cover',
                'contain' => 'Contain',
            ),
            'default' => 'inherit',
            'css' => array(
                'selector' => 'body.boxed',
                'property' => 'background-size',
                'dependency' => array(
                    'ph_layout' => 'boxed'
                ),
            )
        ),

        array(
            'id' => 'background_position',
            'type' => 'select',
            'title' => $this->l('Background position:'),
            'options' => array(
                'left top' => 'Left Top',
                'left center' => 'Left center',
                'left bottom' => 'Left bottom',
                'center top' => 'center top',
                'center center' => 'center center',
                'center bottom' => 'center bottom',
                'right top' => 'right top',
                'right center' => 'right center',
                'right bottom' => 'right bottom',
            ),
            'default' => 'left top',
            'css' => array(
                'selector' => 'body.boxed',
                'property' => 'background-position',
                'dependency' => array(
                    'ph_layout' => 'boxed'
                ),
            )
        ),

        array(
            'id' => 'background_color',
            'type' => 'colorpicker',
            'title' => $this->l('Background color:'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => 'body.boxed',
                'property' => 'background-color',
                'dependency' => array(
                    'ph_layout' => 'boxed'
                ),
            )
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'separator',
        ),

        array(
            'id' => 'boxed_background',
            'type' => 'colorpicker',
            'title' => $this->l('Content background for boxed version:'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => 'body.boxed .boxed-wrapper',
                'property' => 'background',
                'dependency' => array(
                    'control_layout' => 'boxed'
                ),
            )
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Colors'),
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'info-box',
            'title' => $this->l('IMPORTANT:'),
            'desc' => $this->l('Custom colors apply only if you set "Color scheme" option to "Custom"'),
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'colorpicker-load-schemes',
            'title' => $this->l('Use colors from defined scheme'),
            'desc' => $this->l('This option restores the default colors of the selected palette.'),
        ),
        
        array(
            'id' => 'primary_color',
            'type' => 'colorpicker',
            'title' => $this->l('Primary color:'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => array(
                    'a:hover, .main-color-txt, body.cms div.rte ul li, .page-heading, div.topbar .select-options.active i, div.topbar .select-options ul li a:hover, div.topbar .select-options p span, div.topbar .shortlinks ul li a:hover, #search_block_top i:hover, .shopping_cart p a, .shopping_cart a.cart-contents:hover > i, .shopping_cart a.cart-contents.active > i, .shopping_cart a.cart-contents > span span, .shopping_cart .cart_block dl dt .product-name, .shopping_cart .cart_block dl dt .product-name a, .shopping_cart .cart_block dl dt .remove_link a:hover, .shopping_cart .cart_block .cart-prices span.price, .shopping_cart .cart_block .cart-buttons a:hover, .shopping-cart-mobile a.cart-contents > span span, .slider .text h1 span, .slider .text h2 span, .main-box .icon:after, div.heading_block h3, div.heading_block h4, div.heading_block h3 a, div.heading_block h4 a, #categories_block_left.category ul li i, #categories_block_left.category ul ul li:hover i, #categories_block_left.category ul ul li.active i, #categories_block_left.category ul ul li.active2 i, #layered_block_left #enabled_filters li a i, .block_tags .block_content a:hover, .product_list_ph.list .product .list_info div.price, .star_content div.star_on:after, .star_content div.star_hover:after, .prefooter .block_footer h4, .prefooter .block_footer h4 a, .prefooter .block_footer ul li a:hover, .prefooter .block_footer ul li a:hover:before, .prefooter .block_footer.block_contact .item.even, .prefooter .block_footer.block_contact .item.even i, .bottom p strong, .breadcrumb span.navigation_page, body.pagenotfound h1, form.std h3, form.std .form-control:focus, form.std label sup, form.std div.radio input + label:before, form.std div.radio input:checked + label, form.std div.checkbox input + label:before, form.std div.checkbox input:checked + label, form.std div.submit .required, #sitemap_content .sitemap_block ul li a:hover, .panel-group.dark .panel-heading h4 a:hover, .reviews-carousel-wrapper .reviews-carousel-item h4 a:hover, #order .heading-counter span, table.table tbody td.cart_description .product-name a, .summary-shopping table tr.cart_total_price, #add_to_cart .button, .selectBox-options li.selectBox-hover a, .selectBox-options li.selectBox-selected a, .ph_megamenu_mobile_toggle a.show_megamenu:hover, .ph_megamenu_mobile_toggle a.hide_megamenu:hover, #ph_megamenu_wrapper #ph_megamenu .mega-menu ul li a:hover, #ph_megamenu_wrapper #ph_megamenu .dropdown li a:hover, #ph_megamenu_wrapper #ph_megamenu .dropdown li a:hover:before, #ph_megamenu_wrapper #ph_megamenu > li:first-child > a, .ph_simpleblog .simpleblog-posts .post-title h4 a, .sidebar div.heading_block h3 a:hover, .sidebar div.heading_block h4 a:hover',
                    'body.cms div.rte .testimonials .inner:after, #search_query_top:focus, .shopping_cart a.cart-contents:hover > span, .shopping_cart a.cart-contents.active > span, .main-box:hover, div.heading_block, .block_tags .block_content a:hover, .prefooter .block_footer h4, body.pagenotfound a.home i, form.std .form-control:focus',
                    '.main-color, .arrow-ph, body.cms div.rte ol li:before, body.cms div.rte .testimonials .inner, .button.btn-primary, .button-mini:hover, .shopping_cart .cart_block dl dt .remove_link a, .shopping_cart .cart_block .cart-buttons a, .slider ol.dots li.active, .slider ol.dots li:hover, .mini_slider .dots li.active, .mini_slider .dots li:hover, #categories_block_left ul li.active > a, #categories_block_left ul li.active2 > a, #categories_block_left ul li a:hover, #layered_block_left #enabled_filters .layered_subtitle, .social-icons a:hover, .prefooter .block_footer.block_contact .item, div.pagination ul li.current span, div.pagination ul li a:hover, body.pagenotfound .arrow-notfound, body.pagenotfound a.home:hover i, .label.label-primary, .nav-tabs > li.active > a, .nav-tabs > li.active a:focus, .reviews-carousel-wrapper .reviews-carousel-item .see-more-btn:hover, #slider-range .ui-slider-range, table.table tbody td.cart_avail .label-success, table.table tbody td.cart_quantity .cart_quantity_button a, table.table tbody td.cart_delete a, #quantity_wanted_p .counter .btn-q:hover, #ph_megamenu_wrapper #ph_megamenu, #order_step li.step_current, #my-account ul.myaccount-link-list li a:hover, .rev_slider_wrapper .tp-leftarrow.default, .rev_slider_wrapper .tp-rightarrow.default, .rev_slider_wrapper .tp-bullets .bullet.selected, .rev_slider_wrapper .tp-bullets .bullet:hover',
                ),
                'property' => array(
                    'color',
                    'border-color',
                    'background',
                ),
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#f7c322',
                'grey' => '#cfcfcf',
            ),
        ),
        
        array(
            'id' => 'secondary_color',
            'type' => 'colorpicker',
            'title' => $this->l('Secondary color:'),
            'default' => '#2c313b',
            'css' => array(
                'selector' => array(
                    '#search_block_top i, .shopping_cart a.cart-contents > i, div.topbar .shortlinks ul li a:hover',
                    '#ph_megamenu_wrapper #ph_megamenu > li:first-child > a, .sidebar div.heading_block, #newsletterRegistrationForm, .prefooter .prefooter-blocks',
                ),
                'property' => array(
                    'color',
                    'background',
                ),
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#000000',
                'green' => '#2c313b',
                'yellow' => '#151515',
                'grey' => '#f2f2f2',
            )
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Top bar'),
        ),

        /**


        TOPBAR SECTION


        **/
        
        array(
            'id' => 'topbar_bg',
            'type' => 'colorpicker',
            'title' => $this->l('Topbar background:'),
            'default' => '#1d1f25',
            'css' => array(
                'selector' => 'div.topbar, div.topbar .select-options ul',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#1d1f25',
                'yellow' => '#151515',
                'grey' => '#f2f2f2',
            )
        ),
        
        array(
            'id' => 'topbar_quick',
            'type' => 'colorpicker',
            'title' => $this->l('Topbar quick links and text color:'),
            'default' => '#697080',
            'css' => array(
                'selector' => 'div.topbar .shortlinks ul li a, div.topbar .select-options p, div.topbar .select-options ul li a, div.topbar .select-options ul li > span',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#ffffff',
                'green' => '#697080',
                'yellow' => '#697080',
                'grey' => '#697080',
            )
        ),
        
        array(
            'id' => 'topbar_border',
            'type' => 'colorpicker',
            'title' => $this->l('Topbar border:'),
            'desc' => $this->l('Set color of borders that appear in topbar.'),
            'default' => '#2a2e38',
            'css' => array(
                'selector' => 'div.topbar .select-options, div.topbar .shortlinks ul li, div.topbar .select-options ul li, div.topbar .select-options:nth-of-type(2)',
                'property' => 'border-color',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#386fc5',
                'green' => '#2a2e38',
                'yellow' => '#252525',
                'grey' => '#e7e7e7',
            )
        ),
        
        array(
            'id' => 'topbar_special',
            'type' => 'colorpicker',
            'title' => $this->l('Color of currency and language type:'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => 'div.topbar .select-options p span',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#ffffff',
                'green' => '#7bbd42',
                'yellow' => '#f7c322',
                'grey' => '#888888',
            )
        ),


        /**


        CART SECTION


        **/
        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Basket'),
        ),

        array(
            'id' => 'cart_bg',
            'type' => 'colorpicker',
            'title' => $this->l('Basket background on hover:'),
            'default' => '#1d1f25',
            'css' => array(
                'selector' => '.shopping_cart a.cart-contents:hover > span, .cart_block .block_content .products, .shopping_cart .cart_block .block_content',
                'property' => 'background',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'cart_border',
            'type' => 'colorpicker',
            'title' => $this->l('Basket border color on hover:'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => '.shopping_cart a.cart-contents:hover > span',
                'property' => 'border-color',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#f7c322',
                'grey' => '#cfcfcf',
            )
        ),

        array(
            'id' => 'cart_bg_icon',
            'type' => 'colorpicker',
            'title' => $this->l('Basket icon background on hover:'),
            'default' => '#131519',
            'css' => array(
                'selector' => '.shopping_cart a.cart-contents:hover > i, .shopping_cart a.cart-contents.active > i',
                'property' => 'background',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'cart_color_icon',
            'type' => 'colorpicker',
            'title' => $this->l('Basket icon color on hover:'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => '.shopping_cart a.cart-contents:hover > i, .shopping_cart a.cart-contents.active > i',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#f7c322',
                'grey' => '#cfcfcf',
            )
        ),
        
        array(
            'id' => 'cart_info',
            'type' => 'colorpicker',
            'title' => $this->l('Text color in basket summary:'),
            'default' => '#eeeeee',
            'css' => array(
                'selector' => '.shopping_cart .cart_block .cart-prices span',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),
        
        array(
            'id' => 'cart_info_price',
            'type' => 'colorpicker',
            'title' => $this->l('Price color in basket summary:'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => '.shopping_cart .cart_block .cart-prices span.price',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#f7c322',
                'grey' => '#cfcfcf',
            )
        ),
         

        /**


        SEARCH SECTION


        **/

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Searchbox'),
        ),
        
        array(
            'id' => 'search_icon_color',
            'type' => 'colorpicker',
            'title' => $this->l('Search icon color:'),
            'default' => '#2c313b',
            'css' => array(
                'selector' => '#search_block_top i',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'search_icon_color_hover',
            'type' => 'colorpicker',
            'title' => $this->l('Search icon color on hover:'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => '#search_block_top i:hover',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#f7c322',
                'grey' => '#cfcfcf',
            )
        ),

        array(
            'id' => 'search_icon_bg_hover',
            'type' => 'colorpicker',
            'title' => $this->l('Search icon background on hover:'),
            'default' => '#131519',
            'css' => array(
                'selector' => '#search_block_top input[type="submit"].hover',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#f7c322',
                'grey' => '#888888',
            )
        ),
        
        array(
            'id' => 'search_icon_bg_font_size',
            'type' => 'font-size',
            'title' => $this->l('Set size for search icon:'),
            'default' => '24px',
            'size_from' => 14,
            'size_to' => 48,
            'css' => array(
                'selector' => '#search_block_top i',
                'property' => 'font-size'
            ),
        ),
        
        array(
            'id' => 'search_border',
            'type' => 'colorpicker',
            'title' => $this->l('Searchbox border color'),
            'default' => '#606775',
            'css' => array(
                'selector' => '#search_query_top',
                'property' => 'border-color',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'search_border_focus',
            'type' => 'colorpicker',
            'title' => $this->l('Searchbox border color on focus'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => '#search_query_top:focus',
                'property' => 'border-color',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#f7c322',
                'grey' => '#cfcfcf',
            )
        ),


        /**


        PRESTAHOME MEGAMENU SECTION


        **/

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('PrestaHome Mega Menu'),
        ),

        array(
            'id' => 'megamenu_font_size',
            'type' => 'font-size',
            'title' => $this->l('Font-size:'),
            'default' => '16px',
            'size_from' => 12,
            'size_to' => 48,
            'css' => array(
                'selector' => '#ph_megamenu_wrapper #ph_megamenu > li > a',
                'property' => 'font-size',
                'dependency' => array(
                    'use_custom_fonts' => '1'
                ),
            ),
        ),
        
        array(
            'id' => 'megamenu_bg_global',
            'type' => 'colorpicker',
            'title' => $this->l('Megamenu background'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => '#ph_megamenu_wrapper #ph_megamenu',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#151515',
                'grey' => '#f2f2f2',
            )
        ),

        array(
            'id' => 'megamenu_border_global',
            'type' => 'colorpicker',
            'title' => $this->l('Megamenu border color'),
            'desc' => $this->l('Border color in first level'),
            'default' => '#d13562',
            'css' => array(
                'selector' => '#ph_megamenu_wrapper #ph_megamenu > li',
                'property' => 'border-color',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#386fc5',
                'green' => '#62a22b',
                'yellow' => '#252525',
                'grey' => '#e7e7e7',
            )
        ),

        array(
            'id' => 'megamenu_bg_global_submenu',
            'type' => 'colorpicker',
            'title' => $this->l('Megamenu submenu background'),
            'default' => '#131519',
            'css' => array(
                'selector' => '#ph_megamenu_wrapper #ph_megamenu .dropdown, #ph_megamenu_wrapper #ph_megamenu .mega-menu',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'separator',
        ),

        array(
            'id' => 'megamenu_bg_home',
            'type' => 'colorpicker',
            'title' => $this->l('Megamenu home background'),
            'default' => '#2c313b',
            'css' => array(
                'selector' => '#ph_megamenu_wrapper #ph_megamenu > li:first-child > a',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#000000',
                'green' => '#2c313b',
                'yellow' => '#f7c322',
                'grey' => '#cfcfcf',
            )
        ),

        array(
            'id' => 'megamenu_bg_home_hover',
            'type' => 'colorpicker',
            'title' => $this->l('Megamenu home background on hover'),
            'default' => '#131519',
            'css' => array(
                'selector' => '#ph_megamenu_wrapper #ph_megamenu > li:first-child > a:hover',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#000000',
                'green' => '#2c313b',
                'yellow' => '#f7c322',
                'grey' => '#888888',
            )
        ),

        array(
            'id' => 'megamenu_bg_home_icon',
            'type' => 'colorpicker',
            'title' => $this->l('Megamenu home icon'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => '#ph_megamenu_wrapper #ph_megamenu > li:first-child > a',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
        ),

        array(
            'id' => 'megamenu_bg_home_icon',
            'type' => 'colorpicker',
            'title' => $this->l('Megamenu home icon on hover'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => '#ph_megamenu_wrapper #ph_megamenu > li:first-child > a:hover',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'separator',
        ),

        array(
            'id' => 'megamenu_link',
            'type' => 'colorpicker',
            'title' => $this->l('Menu link color:'),
            'default' => '#eeeeee',
            'css' => array(
                'selector' => 'html #ph_megamenu_wrapper #ph_megamenu > li > a',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'megamenu_link_hover',
            'type' => 'colorpicker',
            'title' => $this->l('Menu link color on hover:'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => 'html #ph_megamenu_wrapper #ph_megamenu > li.open > a',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
        ),

        array(
            'id' => 'megamenu_dropdown_link',
            'type' => 'colorpicker',
            'title' => $this->l('Menu dropdown link color:'),
            'default' => '#697080',
            'css' => array(
                'selector' => 'html #ph_megamenu_wrapper #ph_megamenu .dropdown li a, html #ph_megamenu_wrapper #ph_megamenu .dropdown li a::before',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'megamenu_dropdown_link_hover',
            'type' => 'colorpicker',
            'title' => $this->l('Menu dropdown link color - hover:'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => 'html #ph_megamenu_wrapper #ph_megamenu .dropdown li a:hover, html #ph_megamenu_wrapper #ph_megamenu .dropdown li a:hover::before',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#f7c322',
                'grey' => '#cfcfcf',
            )
        ),

        array(
            'id' => 'megamenu_dropdown_link_bg_hover',
            'type' => 'colorpicker',
            'title' => $this->l('Menu dropdown link background - hover:'),
            'default' => '#131519',
            'css' => array(
                'selector' => 'html #ph_megamenu_wrapper #ph_megamenu .dropdown li a:hover',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Mobile menu'),
        ),

        array(
            'id' => 'mobile_megamenu_link',
            'type' => 'colorpicker',
            'title' => $this->l('Mobile menu link color:'),
            'default' => '#f0f0f0',
            'css' => array(
                'selector' => 'html .ph_megamenu.mobile_menu > li > a, html .ph_megamenu.mobile_menu .dropdown li a',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'mobile_megamenu_link_hover',
            'type' => 'colorpicker',
            'title' => $this->l('Mobile menu link color on hover:'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => 'html .ph_megamenu.mobile_menu > li > a:hover, html .ph_megamenu.mobile_menu >li .open > a,  html .ph_megamenu.mobile_menu .dropdown li a:hover',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
        ),

        array(
            'id' => 'mobile_megamenu_link_bg',
            'type' => 'colorpicker',
            'title' => $this->l('Mobile menu link background color:'),
            'default' => '#222222',
            'css' => array(
                'selector' => 'html .ph_megamenu.mobile_menu li > a',
                'property' => 'background',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'mobile_megamenu_link_bg_hover',
            'type' => 'colorpicker',
            'title' => $this->l('Mobile menu link background color - hover:'),
            'default' => '#131519',
            'css' => array(
                'selector' => 'html .ph_megamenu.mobile_menu li > a:hover',
                'property' => 'background',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'mobile_megamenu_link_border_bottom',
            'type' => 'colorpicker',
            'title' => $this->l('Mobile menu link border bottom color:'),
            'default' => '#333333',
            'css' => array(
                'selector' => 'html .ph_megamenu.mobile_menu li, html .ph_megamenu.mobile_menu .dropdown li, html .ph_megamenu.mobile_menu .dropdown li a',
                'property' => 'border-color',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'mobile_megamenu_more_icon',
            'type' => 'colorpicker',
            'title' => $this->l('Mobile menu "+" and "-" background:'),
            'default' => '#111111',
            'css' => array(
                'selector' => 'html .ph_megamenu.mobile_menu li > a span.marker',
                'property' => 'background',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'mobile_megamenu_toggle_color',
            'type' => 'colorpicker',
            'title' => $this->l('Mobile menu switcher link color:'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => '.ph_megamenu_mobile_toggle a.show_megamenu, .ph_megamenu_mobile_toggle a.hide_megamenu',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'mobile_megamenu_toggle_color_hover',
            'type' => 'colorpicker',
            'title' => $this->l('Mobile menu switcher link color - hover:'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => '.ph_megamenu_mobile_toggle a.show_megamenu, .ph_megamenu_mobile_toggle a.hide_megamenu',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#f7c322',
                'grey' => '#cfcfcf',
            )
        ),

        array(
            'id' => 'mobile_megamenu_toggle_bg',
            'type' => 'colorpicker',
            'title' => $this->l('Mobile menu switcher background:'),
            'default' => '#222222',
            'css' => array(
                'selector' => '.ph_megamenu_mobile_toggle a.show_megamenu, .ph_megamenu_mobile_toggle a.hide_megamenu',
                'property' => 'background',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'mobile_megamenu_toggle_bg_hover',
            'type' => 'colorpicker',
            'title' => $this->l('Mobile menu switcher background - hover:'),
            'default' => '#333333',
            'css' => array(
                'selector' => '.ph_megamenu_mobile_toggle a.show_megamenu:hover, .ph_megamenu_mobile_toggle a.hide_megamenu:hover',
                'property' => 'background',
            ),
            'is_color_scheme' => true
        ),

        /**


        SIDEBAR


        **/
        
        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Sidebar'),
        ),
        
        array(
            'id' => 'sidebar_h_size',
            'type' => 'font-size',
            'title' => $this->l('Font-size:'),
            'default' => '16px',
            'size_from' => 12,
            'size_to' => 48,
            'css' => array(
                'selector' => '.sidebar div.heading_block h3, .sidebar div.heading_block h4',
                'property' => 'font-size',
                'dependency' => array(
                    'use_custom_fonts' => '1'
                ),
            ),
        ),
        
        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'separator',
        ),
        
        array(
            'id' => 'sidebar_h_color',
            'type' => 'colorpicker',
            'title' => $this->l('Headings color:'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => '.sidebar div.heading_block h3, .sidebar div.heading_block h4, .sidebar div.heading_block h3 a, .sidebar div.heading_block h4 a',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'sidebar_i_color',
            'type' => 'colorpicker',
            'title' => $this->l('Headings icon color:'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => '.sidebar div.heading_block i',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'sidebar_h_bg',
            'type' => 'colorpicker',
            'title' => $this->l('Background in headings:'),
            'default' => '#2c313b',
            'css' => array(
                'selector' => '.sidebar div.heading_block',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#000000',
                'green' => '#2c313b',
                'yellow' => '#151515',
                'grey' => '#f2f2f2',
            )
        ),
        
        array(
            'id' => 'sidebar_content_bg',
            'type' => 'colorpicker',
            'title' => $this->l('Background in block content:'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => 'div.block .block_content',
                'property' => 'background',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'separator',
        ),
        
        array(
            'id' => 'sidebar_cat_border',
            'type' => 'colorpicker',
            'title' => $this->l('Border color in blockcategory:'),
            'default' => '#eeeeee',
            'css' => array(
                'selector' => '#categories_block_left ul li',
                'property' => 'border-color',
            ),
            'is_color_scheme' => true
        ),
        
        array(
            'id' => 'sidebar_cat_link',
            'type' => 'colorpicker',
            'title' => $this->l('Links color in blockcategory:'),
            'default' => '#2c313b',
            'css' => array(
                'selector' => '#categories_block_left ul li a',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),
        
        array(
            'id' => 'sidebar_cat_link_hover',
            'type' => 'colorpicker',
            'title' => $this->l('Links color on hover in blockcategory:'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => '#categories_block_left ul li a:hover',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#ffffff',
                'green' => '#ffffff',
                'yellow' => '#ffffff',
                'grey' => '#ffffff',
            )
        ),
        
        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'separator',
        ),
        

        array(
            'id' => 'sidebar_o_link',
            'type' => 'colorpicker',
            'title' => $this->l('Links color in other blocks:'),
            'default' => '#767676',
            'css' => array(
                'selector' => 'div.block .block_content ul li a',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),
        
        array(
            'id' => 'sidebar_o_link_hover',
            'type' => 'colorpicker',
            'title' => $this->l('Links color in other blocks on hover:'),
            'default' => '#2c313b',
            'css' => array(
                'selector' => 'div.block .block_content ul li a:hover',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),

         /**


        FOOTER SECTION


        **/

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Footer'),
        ),

        array(
            'id' => 'footer_headings_font_size',
            'type' => 'font-size',
            'title' => $this->l('Font size for headings:'),
            'default' => '16px',
            'size_from' => 12,
            'size_to' => 72,
            'css' => array(
                'selector' => '.prefooter .block_footer h4',
                'property' => 'font-size',
                'dependency' => array(
                    'use_custom_fonts' => '1'
                ),
            ),
        ),

        array(
            'id' => 'footer_headings_color',
            'type' => 'colorpicker',
            'title' => $this->l('Headings color'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => array(
                    '.prefooter .block_footer h4, .prefooter .block_footer h4 a',
                    '.prefooter .block_footer h4',
                ),
                'property' => array(
                    'color',
                    'border-color'
                ),
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#f7c322',
                'grey' => '#7bbd42',
            )
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'separator',
        ),

        array(
            'id' => 'footer_bg',
            'type' => 'colorpicker',
            'title' => $this->l('Footer background:'),
            'default' => '#2c313b',
            'css' => array(
                'selector' => '.prefooter .prefooter-blocks',
                'property' => 'background',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'footer_copy_bg',
            'type' => 'colorpicker',
            'title' => $this->l('Copyright background:'),
            'default' => '#1d1f25',
            'css' => array(
                'selector' => '.bottom',
                'property' => 'background',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'separator',
        ),

        array(
            'id' => 'footer_link_color',
            'type' => 'colorpicker',
            'title' => $this->l('Links color:'),
            'default' => '#8f97ac',
            'css' => array(
                'selector' => '.prefooter .block_footer ul li a, .prefooter .block_footer ul li a::before',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'footer_link_color',
            'type' => 'colorpicker',
            'title' => $this->l('Copyright text color:'),
            'default' => '#697080',
            'css' => array(
                'selector' => '.bottom p',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Text options'),
        ),
        
        array(
            'id' => 'font_color',
            'type' => 'colorpicker',
            'title' => $this->l('Font color:'),
            'desc' => $this->l('This option affect on text color in cms page, short description or long description in product cart.'),
            'default' => '#767676',
            'css' => array(
                'selector' => 'body, body.cms div.rte p',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),
        
        array(
            'id' => 'link_color',
            'type' => 'colorpicker',
            'title' => $this->l('Link color:'),
            'desc' => $this->l('Set main color of links.'),
            'default' => '#2c313b',
            'css' => array(
                'selector' => 'a',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
        ),
        
        array(
            'id' => 'h_color',
            'type' => 'colorpicker',
            'title' => $this->l('Headings color:'),
            'default' => '#2c313b',
            'css' => array(
                'selector' => 'h1, h2, h3, h4, h5, h6, .center_column h1',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'h_sub_color',
            'type' => 'colorpicker',
            'title' => $this->l('Subheadings color:'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => '.page-heading',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#f7c322',
                'grey' => '#cfcfcf',
            )
        ),

        array(
            'id' => 'h_sub_border',
            'type' => 'colorpicker',
            'title' => $this->l('Subheadings border color:'),
            'default' => '#2c313b',
            'css' => array(
                'selector' => '.page-heading::before',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'h_sub_size',
            'type' => 'font-size',
            'title' => $this->l('Font size for subheadings:'),
            'default' => '18px',
            'size_from' => 12,
            'size_to' => 72,
            'css' => array(
                'selector' => '.page-heading',
                'property' => 'font-size',
                'dependency' => array(
                    'use_custom_fonts' => '1'
                ),
            ),
        ),

        /**

        Buttons

        **/

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Buttons'),
        ),

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'info-box',
            'title' => $this->l('HINT:'),
            'desc' => $this->l('There are two types of buttons in PrestaShop, normal (primary) and exclusive (secondary), exclusive buttons in most cases are more important, for example those buttons always encourages visitor to go further in order process. Also they are used as "Add to cart" buttons.'),
        ),
        
        array(
            'id' => 'buttons_size',
            'type' => 'font-size',
            'title' => $this->l('Font size for buttons:'),
            'default' => '18px',
            'size_from' => 12,
            'size_to' => 72,
            'css' => array(
                'selector' => '.button',
                'property' => 'font-size',
                'dependency' => array(
                    'use_custom_fonts' => '1'
                ),
            ),
        ),

        /**

        Standard

        **/
        array(
            'id' => 'button_color',
            'type' => 'colorpicker',
            'title' => $this->l('Primary button background:'),
            'default' => '#dbdbdb',
            'css' => array(
                'selector' => '.button',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
        ),
        
        array(
            'id' => 'button_color_hover',
            'type' => 'colorpicker',
            'title' => $this->l('Primary button hover background:'),
            'default' => '#2c313b',
            'css' => array(
                'selector' => '.button:hover',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
        ),

        array(
            'id' => 'button_text_color',
            'type' => 'colorpicker',
            'title' => $this->l('Primary button text color:'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => '.button',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),
        
        /**

        Exclusive buttons

        **/

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'separator',
        ),

        array(
            'id' => 'button_color_ex',
            'type' => 'colorpicker',
            'title' => $this->l('Secondary button color:'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => '.button.btn-primary',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#f7c322',
                'grey' => '#f7c322',
            )
        ),
        
        array(
            'id' => 'button_color_ex_hover',
            'type' => 'colorpicker',
            'title' => $this->l('Secondary button color hover:'),
            'default' => '#2c313b',
            'css' => array(
                'selector' => '.button.btn-primary:hover',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
        ),

        array(
            'id' => 'button_text_color_ex',
            'type' => 'colorpicker',
            'title' => $this->l('Secondary button text color:'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => '.button.btn-primary',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),

        /**

        LABELS

        **/

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Labels'),
        ),

        array(
            'id' => 'label_new',
            'type' => 'colorpicker',
            'title' => $this->l('Label new background:'),
            'default' => '#bcd634',
            'css' => array(
                'selector' => '.product_list_ph .product .labels .new, #image-block .labels .new',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
        ),

        array(
            'id' => 'label_sale',
            'type' => 'colorpicker',
            'title' => $this->l('Label sale background:'),
            'default' => '#ff4938',
            'css' => array(
                'selector' => '.product_list_ph .product .labels .sale, #image-block .labels .sale',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
        ),

        array(
            'id' => 'label_sold',
            'type' => 'colorpicker',
            'title' => $this->l('Label sold out background:'),
            'default' => '#2c313b',
            'css' => array(
                'selector' => '.product_list_ph .product .labels span',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
        ),

        /**

        Product cart

        **/

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Product cart'),
        ),

        array(
            'id' => 'product_hover_rgba',
            'type' => 'colorpicker',
            'title' => $this->l('Background for product image hover'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => array('#thumbs_list_frame .item a .hover_bg, #view_full_size .hover_bg'),
                'property' => array(
                    'background',
                ),
                'callback' => 'convertToRGBA',
                'callback_params' => array(
                    'alpha' => '0.8',
                )
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#151515',
                'grey' => '#f2f2f2',
            ),
        ),

        /**

        Product list

        **/

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Product list'),
        ),
  
        array(
            'id' => 'product_list_rgba',
            'type' => 'colorpicker',
            'title' => $this->l('Background for product list info'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => array('.product_list_ph .product .info'),
                'property' => array(
                    'background',
                ),
                'callback' => 'convertToRGBA',
                'callback_params' => array(
                    'alpha' => '0.8',
                )
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#151515',
                'grey' => '#f2f2f2',
            ),
        ),


        array(
            'id' => 'product_list_title',
            'type' => 'colorpicker',
            'title' => $this->l('Product name color:'),
            'default' => '#eeeeee',
            'css' => array(
                'selector' => '.product_list_ph .product .info h3 a',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
        ),

        array(
            'id' => 'product_list_price',
            'type' => 'colorpicker',
            'title' => $this->l('Product price color:'),
            'default' => '#eeeeee',
            'css' => array(
                'selector' => '.product_list_ph .product .info span.price',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
        ),

        array(
            'id' => 'product_list_price_old',
            'type' => 'colorpicker',
            'title' => $this->l('Product old price color:'),
            'default' => '#eeeeee',
            'css' => array(
                'selector' => '.product_list_ph .product .info span.old-price',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
        ),

        /**

        CAROUSELS

        **/

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Carousels'),
        ),

        array(
            'id' => 'carousels_txt',
            'type' => 'colorpicker',
            'title' => $this->l('Carousel text color in heading:'),
            'desc' => $this->l('This color we use for all the carousels in Madrid Theme'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => 'div.heading_block h3, div.heading_block h4',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#151515',
                'grey' => '#888888',
            )
        ),

        array(
            'id' => 'carousels_icon',
            'type' => 'colorpicker',
            'title' => $this->l('Carousel icon background in heading:'),
            'desc' => $this->l('This color we use for all the carousels in Madrid Theme'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => '.center_column div.heading_block h3 i, .center_column div.heading_block h4 i',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#151515',
                'grey' => '#f7c322',
            )
        ),

        array(
            'id' => 'carousels_border',
            'type' => 'colorpicker',
            'title' => $this->l('Carousel border color in heading:'),
            'desc' => $this->l('This color we use for all the carousels in Madrid Theme'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => 'div.heading_block',
                'property' => 'border-color',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#151515',
                'grey' => '#f7c322',
            )
        ),

        array(
            'id' => 'arrows_color_txt',
            'type' => 'colorpicker',
            'title' => $this->l('Arrows color:'),
            'desc' => $this->l('This color we use for all the arrows in the carousels in Madrid Theme'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => '.arrow-ph',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
        ),

        array(
            'id' => 'arrows_color_txt_hover',
            'type' => 'colorpicker',
            'title' => $this->l('Arrows color on hover:'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => '.arrow-ph:hover',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
        ),

        array(
            'id' => 'arrows_color',
            'type' => 'colorpicker',
            'title' => $this->l('Arrows background color:'),
            'desc' => $this->l('This color we use for all the arrows in the carousels in Madrid Theme'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => '.arrow-ph',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#f7c322',
                'grey' => '#f7c322',
            )
        ),
        
        array(
            'id' => 'arrows_color_hover',
            'type' => 'colorpicker',
            'title' => $this->l('Arrows background color on hover:'),
            'desc' => $this->l('Set background color on hover for arrows'),
            'default' => '#2c313b',
            'css' => array(
                'selector' => '.arrow-ph:hover',
                'property' => 'background',
            ),
            'is_color_scheme' => true,
        ),

        /**

        FORMS

        **/

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Forms'),
        ),
        
        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'info-box',
            'title' => $this->l('INFO:'),
            'desc' => $this->l('This section contains style for all forms in shop.'),
        ),
        
        array(
            'id' => 'form_bg',
            'type' => 'colorpicker',
            'title' => $this->l('Form background:'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => 'form.std, .send_friend_form_content',
                'property' => 'background',
            ),
            'is_color_scheme' => true
        ),
        
        array(
            'id' => 'form_font_head',
            'type' => 'colorpicker',
            'title' => $this->l('Form heading color:'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => 'form.std h3, .send_friend_form_content h3',
                'property' => 'color',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#f7c322',
                'grey' => '#cfcfcf',
            )
        ),
        
        array(
            'id' => 'border_color_input',
            'type' => 'colorpicker',
            'title' => $this->l('Fields border color:'),
            'default' => '#dfdfdf',
            'css' => array(
                'selector' => 'form.std .form-control, .send_friend_form_content .form-control',
                'property' => 'border-color',
            ),
            'is_color_scheme' => true
        ),

        array(
            'id' => 'border_color_input_focus',
            'type' => 'colorpicker',
            'title' => $this->l('Fields border color on focus:'),
            'default' => '#ff4178',
            'css' => array(
                'selector' => 'form.std .form-control:focus, .send_friend_form_content .form-control:focus',
                'property' => 'border-color',
            ),
            'is_color_scheme' => true,
            'defaults' => array(
                'blue' => '#3e79d5',
                'green' => '#7bbd42',
                'yellow' => '#f7c322',
                'grey' => '#cfcfcf',
            )
        ),
        
        array(
            'id' => 'border_shadow_input',
            'type' => 'colorpicker',
            'title' => $this->l('Fields shadow color:'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => 'form.std .form-control, .send_friend_form_content .form-control',
                'property' => 'box-shadow',
                'shadow' => '0 1px 2px',
            ),
            'is_color_scheme' => true
        ),

        /**

        TABLES

        **/

        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'sub-title-block',
            'title' => $this->l('Tables'),
        ),
        
        
        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'info-box',
            'title' => $this->l('INFO:'),
            'desc' => $this->l('This section contains style for all tables in shop.'),
        ),
        
        array(
            'id' => 'table_h_bg',
            'type' => 'colorpicker',
            'title' => $this->l('Table heading background:'),
            'default' => '#2c313b',
            'css' => array(
                'selector' => 'table.table thead th, table.table-content th',
                'property' => 'background',
            ),
            'is_color_scheme' => true
        ),
        
        array(
            'id' => 'table_h_color',
            'type' => 'colorpicker',
            'title' => $this->l('Table heading color:'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => 'table.table thead th, table.table-content th',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),
        
        array(
            'id' => 'rand-'.rand(9999, 99999),
            'type' => 'separator',
        ),
        
        array(
            'id' => 'table_bg',
            'type' => 'colorpicker',
            'title' => $this->l('Table background:'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => 'table.table, table.table-content',
                'property' => 'background',
            ),
            'is_color_scheme' => true
        ),
        
        array(
            'id' => 'table_text_color',
            'type' => 'colorpicker',
            'title' => $this->l('Text color in table:'),
            'default' => '#767676',
            'css' => array(
                'selector' => 'table.table tbody td, table.table-content td',
                'property' => 'color',
            ),
            'is_color_scheme' => true
        ),
        
        array(
            'id' => 'table_border',
            'type' => 'colorpicker',
            'title' => $this->l('Table border:'),
            'default' => '#ffffff',
            'css' => array(
                'selector' => 'table.table, table.table-content',
                'property' => 'border-color',
            ),
            'is_color_scheme' => true
        ),
        
        array(
            'id' => 'table_border_separator_color',
            'type' => 'colorpicker',
            'title' => $this->l('Rows separator color in table:'),
            'default' => '#dddddd',
            'css' => array(
                'selector' => '.table thead > tr > th, .table thead > tr > td, .table tbody > tr > th, .table tbody > tr > td, .table tfoot > tr > th, .table tfoot > tr > td',
                'property' => 'border-color',
            ),
            'is_color_scheme' => true
        ),
    )
);

$sections[] = array(
    'title' => $this->l('Contact Page'),
    'icon' => 'icon icon-envelope',
    'fields' => array(

        array(
            'id' => 'contact_right_section_display',
            'type' => 'switch',
            'title' => $this->l('Display custom content in contact page'),
            'default' => '1',
            'label_on' => $this->l('Yes'),
            'label_off' => $this->l('No'),
        ),

        array(
            'id' => 'contact_right_section_title',
            'type' => 'textLang',
            'title' => $this->l('Title of the custom content'),
            'default' => PrestaHomeOptions::prepareValueForLangs('About us'),
            'required' => false,
        ),

        array(
            'id' => 'contact_right_section',
            'type' => 'textareaLang',
            'title' => $this->l('Contact page custom content'),
            'default' => PrestaHomeOptions::prepareValueForLangs('<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eget urna vel diam rutrum pretium quis eu nisi. Maecenas condimentum risus nunc, sed vestibulum diam dapibus elementum. Phasellus tincidunt eros vitae erat aliquet, sit amet vulputate velit pretium. Nam tempor sed felis eget accumsan. Donec hendrerit leo nec ligula fringilla, ut tempus turpis accumsan.</p>

                            <p>
                                Ut quis neque pulvinar, pharetra tellus in, condimentum eros. In et hendrerit arcu. Maecenas quis sodales metus, vitae ultricies quam. Mauris ornare tristique risus ac sodales. Fusce interdum dui id diam fringilla varius. Sed at venenatis felis. 
                            </p>

                            <p>
                                <b>Customer service:</b><br>
                                <a href="#">test@prestahome.com</a><br>
                                tel. +48 600-000-999
                            </p>

                            <p>
                                <b>Payments:</b><br>
                                <a href="#">test@prestahome.com</a>
                            </p>

                            <p>
                                <b>Shipping:</b><br>
                                <a href="#">test@prestahome.com</a>
                            </p>'),
        ),

        array(
            'id' => 'contact_map_display',
            'type' => 'switch',
            'title' => $this->l('Display map in contact page'),
            'default' => '1',
            'label_on' => $this->l('Yes'),
            'label_off' => $this->l('No'),
        ),

        array(
            'id' => 'contact_map_section',
            'type' => 'textLang',
            'title' => $this->l('Contact page custom content'),
            'default' => PrestaHomeOptions::prepareValueForLangs('<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d156388.80311681767!2d21.06119405!3d52.23293794999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471ecc669a869f01%3A0x72f0be2a88ead3fc!2sWarszawa!5e0!3m2!1spl!2spl!4v1423469455419" width="600" height="450" style="border:0"></iframe>'),
        ),

    )
);

$file_suffix = '';
if (Shop::getContext() == Shop::CONTEXT_GROUP) {
    $file_suffix = '_group_'.(int)Context::getContext()->shop->getContextShopGroupID();
} elseif (Shop::getContext() == Shop::CONTEXT_SHOP) {
    $file_suffix = '_shop_'.(int)Context::getContext()->shop->getContextShopID();
}

$sections[] = array(
    'title' => $this->l('Custom CSS / JS'),
    'icon' => 'icon icon-gears',
    'fields' => array(

        array(
            'id' => 'custom_css',
            'type' => 'custom_js',
            'file' => _PS_MODULE_DIR_.'prestahome/views/css/custom'.$file_suffix.'.css',
            'title' => $this->l('Write custom css code here'),
            'desc' => $this->l('This code will be available in head section'),
            'content' => Tools::file_get_contents(_PS_MODULE_DIR_.'prestahome/views/css/custom'.$file_suffix.'.css'),
        ),

        array(
            'id' => 'custom_js_footer',
            'type' => 'custom_js',
            'file' => _PS_MODULE_DIR_.'prestahome/views/js/custom'.$file_suffix.'.js',
            'content' => Tools::file_get_contents(_PS_MODULE_DIR_.'prestahome/views/js/custom-footer'.$file_suffix.'.js'),
            'title' => $this->l('Write custom javascript code here'),
            'desc' => $this->l('This code will be available right before closing body tag'),
        ),

    )
);

return $sections;

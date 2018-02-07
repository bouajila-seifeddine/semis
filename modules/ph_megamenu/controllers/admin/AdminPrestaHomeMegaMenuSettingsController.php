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
require_once _PS_MODULE_DIR_ . 'ph_megamenu/ph_megamenu.php';

class AdminPrestaHomeMegaMenuSettingsController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->bootstrap = true;

        $standard_options = array(
            'general' => array(
                'title' =>  $this->l('General Settings'),
                'image' =>   '../img/t/AdminOrderPreferences.gif',
                'fields' => array(

                    'PH_MM_USE_SLIDE_EFFECT' => array(
                        'title' => $this->l('Use "Slide" effect on Mega Menu?'),
                        'validation' => 'isBool',
                        'required' => true,
                        'type' => 'bool'
                    ), // PH_MM_USE_SLIDE_EFFECT

                    'PH_MM_DEFAULT_LABEL_BG' => array(
                        'title' => $this->l('Default background of labels:'),
                        'required' => true,
                        'type' => 'color',
                        'class' => 'color mColorPickerInput',
                        'name' => 'PH_MM_DEFAULT_LABEL_BG',
                        'size' => '',
                    ), // PH_MM_DEFAULT_LABEL_BG

                    'PH_MM_DEFAULT_LABEL_COLOR' => array(
                        'title' => $this->l('Default text color of labels:'),
                        'required' => true,
                        'type' => 'color',
                        'name' => 'PH_MM_DEFAULT_LABEL_COLOR',
                        'size' => '',
                    ), // PH_MM_DEFAULT_LABEL_COLOR
                   
                ),
                'submit' => array('title' => $this->l('Update'), 'class' => 'button'),
            ),

            'mega_categories' => array(
                'submit' => array('title' => $this->l('Update'), 'class' => 'button'),
                'title' =>  $this->l('Mega Menu - Categories'),
                'fields' => array(

                    'PH_MM_CATEGORIES_SORTBY' => array(
                        'title' => $this->l('Sort categories in menu by:'),
                        'desc' => $this->l('Select which method use to sort categories in Mega Categories blocks'),
                        'show' => true,
                        'required' => true,
                        'type' => 'radio',
                        'choices' => array(
                            'position' => $this->l('Position (1-9)'),
                            'name' => $this->l('Name (A-Z)'),
                            'id' => $this->l('ID (1-9)'),
                        )
                    ), // PH_MM_CATEGORIES_SORTBY

                ),
            ),

            'mega_products' => array(
                'submit' => array('title' => $this->l('Update'), 'class' => 'button'),
                'title' =>  $this->l('Mega Menu - Products'),
                'fields' => array(

                    'PH_MM_PRODUCT_WIDTH' => array(
                        'title' => $this->l('Width of 1 product:'),
                        'cast' => 'intval',
                        'desc' => $this->l('By default 1 products has width of 3 columns, you can change this to 2, 3, 4 or 6 columns'),
                        'show' => true,
                        'required' => true,
                        'type' => 'radio',
                        'choices' => array(
                            '2' => $this->l('2 columns - maximum 6 products in one full width menu'),
                            '3' => $this->l('3 columns - maximum 4 products in one full width menu'),
                            '4' => $this->l('4 columns - maximum 3 products in one full width menu'),
                            '6' => $this->l('6 columns - maximum 2 products in one full width menu'),
                        )
                    ), // PH_MM_PRODUCT_WIDTH

                    'PH_MM_PRODUCT_SHOW_TITLE' => array(
                        'title' => $this->l('Show product title?'),
                        'validation' => 'isBool',
                        'required' => true,
                        'type' => 'bool'
                    ), // PH_MM_PRODUCT_SHOW_TITLE

                    'PH_MM_PRODUCT_SHOW_DESC' => array(
                        'title' => $this->l('Show product description?'),
                        'validation' => 'isBool',
                        'required' => true,
                        'type' => 'bool'
                    ), // PH_MM_PRODUCT_SHOW_DESC

                    // 'PH_MM_PRODUCT_SHOW_SECOND_IMAGE' => array(
                    //     'title' => $this->l('Show product second image on hover?'),
                    //     'validation' => 'isBool',
                    //     'required' => true,
                    //     'type' => 'bool'
                    // ), // PH_MM_PRODUCT_SHOW_SECOND_IMAGE

                    'PH_MM_PRODUCT_SHOW_PRICE' => array(
                        'title' => $this->l('Show product price?'),
                        'validation' => 'isBool',
                        'required' => true,
                        'type' => 'bool'
                    ), // PH_MM_PRODUCT_SHOW_PRICE

                    'PH_MM_PRODUCT_SHOW_ADD2CART' => array(
                        'title' => $this->l('Show "Add to cart" button?'),
                        'validation' => 'isBool',
                        'required' => true,
                        'type' => 'bool'
                    ), // PH_MM_PRODUCT_SHOW_ADD2CART

                    'PH_MM_PRODUCT_SHOW_VIEW' => array(
                        'title' => $this->l('Show "View" button?'),
                        'validation' => 'isBool',
                        'required' => true,
                        'type' => 'bool'
                    ), // PH_MM_PRODUCT_SHOW_VIEW

                    // 'PH_MM_PRODUCT_SHOW_QUICK_VIEW' => array(
                    //     'title' => $this->l('Show "Quick view" button?'),
                    //     'validation' => 'isBool',
                    //     'required' => true,
                    //     'type' => 'bool'
                    // ), // PH_MM_PRODUCT_SHOW_QUICK_VIEW

                    

                ),
            ),

            'custom_code' => array(
                'submit' => array('title' => $this->l('Update'), 'class' => 'button'),
                'title' =>  $this->l('Custom JS / CSS'),
                'fields' => array(

                    'PH_MM_CSS' => array(
                        'title' => $this->l('Custom CSS'),
                        'show' => true,
                        'required' => false,
                        'type' => 'textarea',
                        'cols' => '70',
                        'rows' => '10'
                    ), // PH_MM_CSS


                    'PH_MM_JS' => array(
                        'title' => $this->l('Custom JS'),
                        'show' => true,
                        'required' => false,
                        'type' => 'textarea',
                        'cols' => '70',
                        'rows' => '10'
                    ), // PH_MM_JS

                ),
            ),
        );

        $this->fields_options = $standard_options;
    }

    public function beforeUpdateOptions()
    {
        $customCSS = '/** custom CSS for PrestaHomeMegaMenu **/'.PHP_EOL;
        $customCSS .= Tools::getValue('PH_MM_CSS', false);

        if($customCSS)
        {
            $handle = _PS_MODULE_DIR_ . 'ph_megamenu/css/custom.css';

            if(!file_put_contents($handle, $customCSS))
            {
                die(Tools::displayError('Problem with saving custom CSS, contact with module author'));
            }
        }
        $customJS = '/** custom JS for PrestaHomeMegaMenu **/'.PHP_EOL;
        $customJS .= Tools::getValue('PH_MM_JS', false);

        if($customJS)
        {
            $handle = _PS_MODULE_DIR_ . 'ph_megamenu/js/custom.js';

            if(!file_put_contents($handle, $customJS))
            {
                die(Tools::displayError('Problem with saving custom JS, contact with module author'));
            }
        }
    }
}

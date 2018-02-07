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

class PrestaHomeMegaMenu extends ObjectModel
{

    public $id;
    public $id_prestahome_megamenu;

    public $id_parent = 0;
    public $position;

    public $type = 0;
    public $new_window = 0;
    public $active = 1;
    public $display_title = 1;

    public $align = 0;
    public $columns = 12;
    public $class;
    public $icon;

    public $label_color = '#FFFFFF';
    public $label_bg = '#F7BA0B';
    public $label_position;

    public $id_category_parent = 0;
    public $id_cms_category_parent = 0;
    public $id_product;

    public $date_add;
    public $date_upd;

    // Multi-Lang
    public $title;
    public $url;

    public $content;
    public $content_before;
    public $content_after;
    public $label_text;

    /**
    @since 1.0.4
    **/
    public $access;

    /**
    @since 1.0.10
    **/
    public $hide_on_mobile = 0;
    public $hide_on_desktop = 0;
    public $background_img;
    public $background_size = 'cover';
    public $background_repeat = 'no-repeat';
    public $background_attachment = 'scroll';
    public $background_position = 'bottom right';
    public $new_row = 0;

    public static $definition = array(
        'table' => 'prestahome_megamenu',
        'primary' => 'id_prestahome_megamenu',
        'multilang' => true,
        'fields' => array(
            'id_parent'          => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'position'          => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),

            // defaults - type 0 = default, 1 megamenu
            'type'              => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'new_window'        => array('type' => self::TYPE_BOOL),
            'active'            => array('type' => self::TYPE_BOOL),
            'access'            => array('type' => self::TYPE_STRING),
            'display_title'     => array('type' => self::TYPE_BOOL),
            'hide_on_mobile'    => array('type' => self::TYPE_BOOL),
            'hide_on_desktop'   => array('type' => self::TYPE_BOOL),
            'new_row'           => array('type' => self::TYPE_BOOL),

            // design
            'align'                 => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'columns'               => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'class'                 => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 50),
            'icon'                  => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255),
            'background_img'        => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255),
            'background_size'       => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255),
            'background_repeat'     => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255),
            'background_attachment' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255),
            'background_position'   => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255),

            // label
            'label_color'          => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 10),
            'label_bg'             => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 10),
            'label_position'       => array('type' => self::TYPE_STRING, 'size' => 5),

            // elements
            'id_category_parent'       => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_cms_category_parent'   => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_product'               => array('type' => self::TYPE_STRING, 'size' => '3999999999999'),
            
            // misc
            'date_add'          => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd'          => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

            // Lang fields
            'title'             => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 255),
            'url'               => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 3999999999999, 'required' => false),
            'content'           => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 3999999999999),
            'content_before'       => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 3999999999999),
            'content_after'    => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 3999999999999),
            'label_text'        => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 255),
        ),
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id, $id_lang, $id_shop);


        //echo $this->id_product;

        if($this->id)
        {
            //$this->id_product = explode(',', $this->id_product);
        }
    }

    public  function add($autodate = true, $null_values = false)
    {
        $this->position = PrestaHomeMegaMenu::getNewLastPosition($this->id_parent);

        $ret = parent::add($autodate, $null_values);
        return $ret;
    }

    public function delete()
    {
        if(parent::delete())
        {
            return $this->cleanPositions($this->id_parent) && $this->removeChildrens($this->id_prestahome_megamenu);
        }
        return false;
    }

    public function removeChildrens($id_parent)
    {
        $result = Db::getInstance()->executeS('
            SELECT `id_prestahome_megamenu`
            FROM `'._DB_PREFIX_.'prestahome_megamenu`
            WHERE `id_parent` = '.(int)$id_parent.'
        ');
        $sizeof = count($result);
        for ($i = 0; $i < $sizeof; ++$i)
        {
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'prestahome_megamenu` WHERE id_prestahome_megamenu = '.(int)$result[$i]['id_prestahome_megamenu']);
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'prestahome_megamenu_lang` WHERE id_prestahome_megamenu = '.(int)$result[$i]['id_prestahome_megamenu']);
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'prestahome_megamenu_shop` WHERE id_prestahome_megamenu = '.(int)$result[$i]['id_prestahome_megamenu']);
        }
        return true; 
    }

    public static function getParents($id_lang)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT *
            FROM `'._DB_PREFIX_.'prestahome_megamenu` pmm
            LEFT JOIN `'._DB_PREFIX_.'prestahome_megamenu_lang` pmml
                ON (pmm.`id_prestahome_megamenu` = pmml.`id_prestahome_megamenu` AND pmml.`id_lang` = '.(int)$id_lang.')
            WHERE pmm.id_parent = 0
            ORDER BY pmm.`position` ASC
        ');

        return $result;
    }

    public static function getNbMenus($id_parent = null)
    {
        return (int)Db::getInstance()->getValue('
            SELECT COUNT(*)
            FROM `'._DB_PREFIX_.'prestahome_megamenu` pmm
            '.(!is_null($id_parent) ? 'WHERE pmm.`id_parent` = '.(int)$id_parent : '')
        );
    }

    public static function getNewLastPosition($id_parent)
    {
        return (Db::getInstance()->getValue('
            SELECT IFNULL(MAX(position),0)+1
            FROM `'._DB_PREFIX_.'prestahome_megamenu`
            WHERE `id_parent` = '.(int)$id_parent
        ));
    }

    public function move($direction)
    {
        $nb_menus = PrestaHomeMegaMenu::getNbMenus($this->id_parent);
        if ($direction != 'l' && $direction != 'r')
            return false;
        if ($nb_menus <= 1)
            return false;
        if ($direction == 'l' && $this->position <= 1)
            return false;
        if ($direction == 'r' && $this->position >= $nb_menus)
            return false;

        $new_position = ($direction == 'l') ? $this->position - 1 : $this->position + 1;
        Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'prestahome_megamenu` pmm
            SET position = '.(int)$this->position.'
            WHERE id_parent = '.(int)$this->id_parent.'
                AND position = '.(int)$new_position
        );
        $this->position = $new_position;
        return $this->update();
    }

    public function cleanPositions($id_parent)
    {
        $result = Db::getInstance()->executeS('
            SELECT `id_prestahome_megamenu`
            FROM `'._DB_PREFIX_.'prestahome_megamenu`
            WHERE `id_parent` = '.(int)$id_parent.'
            ORDER BY `position`
        ');
        $sizeof = count($result);
        for ($i = 0; $i < $sizeof; ++$i)
            Db::getInstance()->execute('
                UPDATE `'._DB_PREFIX_.'prestahome_megamenu`
                SET `position` = '.($i + 1).'
                WHERE `id_prestahome_megamenu` = '.(int)$result[$i]['id_prestahome_megamenu']
            );
        return true;
    }

    public function updatePosition($way, $position)
    {
        if (!$res = Db::getInstance()->executeS('
            SELECT pmm.`id_prestahome_megamenu`, pmm.`position`, pmm.`id_parent`
            FROM `'._DB_PREFIX_.'prestahome_megamenu` pmm
            WHERE pmm.`id_parent` = '.(int)$this->id_parent.'
            ORDER BY pmm.`position` ASC'
        ))
            return false;

        foreach ($res as $megamenu)
            if ((int)$megamenu['id_prestahome_megamenu'] == (int)$this->id)
                $moved_menu = $megamenu;

        if (!isset($moved_menu) || !isset($position))
            return false;
        // < and > statements rather than BETWEEN operator
        // since BETWEEN is treated differently according to databases
        $result = (Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'prestahome_megamenu`
            SET `position`= `position` '.($way ? '- 1' : '+ 1').'
            WHERE `position`
            '.($way
                ? '> '.(int)$moved_menu['position'].' AND `position` <= '.(int)$position
                : '< '.(int)$moved_menu['position'].' AND `position` >= '.(int)$position).'
            AND `id_parent`='.(int)$moved_menu['id_parent'])
        && Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'prestahome_megamenu`
            SET `position` = '.(int)$position.'
            WHERE `id_parent` = '.(int)$moved_menu['id_parent'].'
            AND `id_prestahome_megamenu`='.(int)$moved_menu['id_prestahome_megamenu']));
        return $result;
    }

    public function update($null_values = false)
    {
        $current_menu = new PrestaHomeMegaMenu($this->id);
        if ($current_menu->id_parent != $this->id_parent)
            $this->position = PrestaHomeMegaMenu::getNewLastPosition($this->id_parent);

        return parent::update($null_values);
    }

    public static function getChildrens($id_parent)
    {
        $id_lang = Context::getContext()->language->id;

        $menus = DB::getInstance()->executeS('
            SELECT *
            FROM `'._DB_PREFIX_.'prestahome_megamenu` pmm
            LEFT JOIN `'._DB_PREFIX_.'prestahome_megamenu_lang` pmml
                ON (pmm.`id_prestahome_megamenu` = pmml.`id_prestahome_megamenu` AND pmml.`id_lang` = '.(int)$id_lang.')
            WHERE pmm.`id_parent` = '.(int)$id_parent.' AND pmm.active = 1
            ORDER BY pmm.`position` ASC
        ');

        foreach($menus as $key => $menu)
        {
            $instance = new PrestaHomeMegaMenu($menu['id_prestahome_megamenu']);

            if(!$instance->isAccessGranted())
            {
                unset($menus[$key]);
            }
        }

        return $menus;
    }

    public static function getMegaMenu()
    {
        $id_lang = Context::getContext()->language->id;

        $menus = DB::getInstance()->executeS('
            SELECT *
            FROM `'._DB_PREFIX_.'prestahome_megamenu` pmm
            INNER JOIN `'._DB_PREFIX_.'prestahome_megamenu_lang` pmml
                ON (pmm.`id_prestahome_megamenu` = pmml.`id_prestahome_megamenu` AND pmml.`id_lang` = '.(int)$id_lang.')
            INNER JOIN `'._DB_PREFIX_.'prestahome_megamenu_shop` pmms
                ON (pmm.`id_prestahome_megamenu` = pmms.`id_prestahome_megamenu` AND pmms.`id_shop` = '.(int)Context::getContext()->shop->id.')
            WHERE pmm.`id_parent` = 0 AND pmm.active = 1
            ORDER BY pmm.`position` ASC
        ');

        $return_menus = array();

        foreach($menus as $key => $menu)
        {
            $instance = new PrestaHomeMegaMenu($menu['id_prestahome_megamenu']);

            if($instance->isAccessGranted())
            {
                $return_menus[$key] = $menu;

                if($menu && $menu['id_parent'] == 0)
                {
                    $childrens =  PrestaHomeMegaMenu::getChildrens($menu['id_prestahome_megamenu']);

                    if(sizeof($childrens) > 0)
                    {
                        $return_menus[$key]['childrens'] = $childrens;
                    }
                }
            }
        }

        return $return_menus;
    }

    public static function getMenusWithoutChildrens()
    {
        $result = Db::getInstance()->executeS('
            SELECT `id_prestahome_megamenu`
            FROM `'._DB_PREFIX_.'prestahome_megamenu`
            WHERE `type` IN (2)');

        $return = array();
        if(sizeof($result))
        {
            foreach($result as $id)
            {
                $return[] = $id['id_prestahome_megamenu'];
            }
            return $return;
        }
        else
            return array();
    }

    public static function getNewRows()
    {
        $result = Db::getInstance()->executeS('
            SELECT `id_prestahome_megamenu`
            FROM `'._DB_PREFIX_.'prestahome_megamenu`
            WHERE `type` IN (7)');

        $return = array();
        if(sizeof($result))
        {
            foreach($result as $id)
            {
                $return[] = $id['id_prestahome_megamenu'];
            }
            return $return;
        }
        else
            return array();
    }

    public static function getTabProducts($ids)
    {
        $context = Context::getContext();
        $id_lang = $context->language->id;

        $front = true;
        if (!in_array($context->controller->controller_type, array('front', 'modulefront')))
            $front = false;

        $groups = FrontController::getCurrentCustomerGroups();
        $sql_groups = (count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1');

        $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, MAX(product_attribute_shop.id_product_attribute) id_product_attribute,
                    pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`,
                    pl.`name`, MAX(image_shop.`id_image`) id_image, il.`legend`, m.`name` AS manufacturer_name,
                    DATEDIFF(
                        p.`date_add`,
                        DATE_SUB(
                            NOW(),
                            INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
                        )
                    ) > 0 AS new
                FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p').'
                LEFT JOIN '._DB_PREFIX_.'product_attribute pa ON (pa.id_product = p.id_product)
                '.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on=1').'
                '.Product::sqlStock('p', 0, false, $context->shop).'
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
                    p.`id_product` = pl.`id_product`
                    AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
                )
                LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
                Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
                LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
                WHERE product_shop.`active` = 1
                AND product_shop.`show_price` = 1
                '.($front ? ' AND p.`visibility` IN ("both", "catalog")' : '').'
                AND p.`id_product` IN ('.((is_array($ids) && count($ids)) ? implode(', ', pSQL($ids)) : 0).')
                AND p.`id_product` IN (
                    SELECT cp.`id_product`
                    FROM `'._DB_PREFIX_.'category_group` cg
                    LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
                    WHERE cg.`id_group` '.pSQL($sql_groups).'
                )
                GROUP BY product_shop.id_product
                ORDER BY `pl`.name ASC';

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if (!$result)
            return false;

        return Product::getProductsProperties($id_lang, $result);

    }

    public function isAccessGranted()
    {
        if ($userGroups = Context::getContext()->customer->getGroups())
        {
            if (!isset($this->id_prestahome_megamenu))
                return false;

            $tmpLinkGroups = unserialize($this->access);
            $linkGroups = array();

            foreach ($tmpLinkGroups as $groupID => $status)
            {
                if ($status)
                    $linkGroups[] = $groupID;
            }

            // Check if groups are intersecting
            $intersect = array_intersect($userGroups, $linkGroups);
            if (count($intersect))
                return true;
            else
                return false;
        }
    }
}
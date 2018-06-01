<?php
/**
* NOTICE OF LICENSE
*
*   This file is property of Petr Hucik. You may NOT redistribute the code in any way
*   See license.txt for the complete license agreement
*
* @author    Petr Hucik
* @website   https://www.getdatakick.com
* @copyright Petr Hucik <petr@getdatakick.com>
* @license   see license.txt
* @version   2.1.3
*/
namespace Datakick\Schema\Prestashop;

class Shops {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'shops',
      'singular' => 'shop',
      'description' => 'Shops',
      'key' => array('id'),
      'category' => 'common',
      'display' => 'name',
      'parameters' => array(),
      'psTab' => 'AdminShopGroup',
      'psController' => 'AdminShop',
      'psClass' => 'Shop',
      'permissions' => array(
        'view' => true
      ),
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:id>'
        )
      ),
      'tables' => array(
        's' => array(
          'table' => 'shop'
        ),
        'sg' => array(
          'table' => 'shop_group',
          'require' => array('s'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'sg.id_shop_group = s.id_shop_group',
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'sql' => 's.id_shop',
          'require' => array('s'),
          'selectRecord' => 'shops',
          'update' => false
        ),
        'categoryId' => array(
          'type' => 'number',
          'description' => 'root category id',
          'sql' => 's.id_category',
          'require' => array('s'),
          'update' => false
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'sql' => 's.name',
          'require' => array('s'),
          'update' => array(
            's' => 'name'
          )
        ),
        'active' => array(
          'type' => 'boolean',
          'description' => 'is active',
          'sql' => 's.active',
          'require' => array('s'),
          'update' => array(
            's' => 'active'
          )
        ),
        'deleted' => array(
          'type' => 'boolean',
          'description' => 'is deleted',
          'sql' => 's.deleted',
          'require' => array('s'),
          'update' => array(
            's' => 'deleted'
          )
        ),
      ),
      'links' => array(
      )
    ));
  }
}

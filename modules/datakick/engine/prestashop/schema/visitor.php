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

class Visitor {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'visitors',
      'singular' => 'visitor',
      'description' => 'Visitors',
      'key' => array('id'),
      'display' => 'name',
      'parameters' => array('shop', 'shopGroup', 'shareCustomers'),
      'category' => 'statistics',
      'psTab' => 'AdminStats',
      'tables' => array(
        'g' => array(
          'table' => 'guest'
        ),
        'os' => array(
          'table' => 'operating_system',
          'require' => array('g'),
          'join' => array(
            'type' => 'LEFT',
            'conditions' => array(
              'g.id_operating_system = os.id_operating_system'
            )
          )
        ),
        'b' => array(
          'table' => 'web_browser',
          'require' => array('g'),
          'join' => array(
            'type' => 'LEFT',
            'conditions' => array(
              'g.id_web_browser = b.id_web_browser'
            )
          )
        ),
        'c' => array(
          'table' => 'customer',
          'join' => array(
            'type' => 'LEFT',
            'conditions' => array(
              'c.id_customer = g.id_customer',
              'if(<bind-param:shareCustomers:1>, <bind-param:shopGroup:c.id_shop_group>, <bind-param:shop:c.id_shop>)'
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'sql' => 'g.id_guest',
          'require' => array('g'),
          'selectRecord' => 'visitors',
          'update' => false
        ),
        'customerId' => array(
          'type' => 'number',
          'description' => 'customer id',
          'sql' => 'g.id_customer',
          'require' => array('g'),
          'selectRecord' => 'customers',
          'update' => false
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'sql' => "COALESCE(TRIM(CONCAT(c.firstname, ' ', c.lastname)), 'Anonymous guest')",
          'require' => array('c'),
          'update' => false
        ),
        'operatingSystem' => array(
          'type' => 'string',
          'description' => 'operating system',
          'sql' => "os.name",
          'require' => array('os'),
          'update' => false
        ),
        'webBrowser' => array(
          'type' => 'string',
          'description' => 'web browser',
          'sql' => "b.name",
          'require' => array('b'),
          'update' => false
        ),
        'language' => array(
          'type' => 'string',
          'description' => 'accept language',
          'sql' => "g.accept_language",
          'require' => array('g'),
          'update' => false
        ),
        'hasJavascript' => array(
          'type' => 'boolean',
          'description' => 'supports javascript',
          'sql' => "g.javascript",
          'require' => array('g'),
          'update' => false
        ),
        'hasJava' => array(
          'type' => 'boolean',
          'description' => 'supports java applets',
          'sql' => "g.sun_java",
          'require' => array('g'),
          'update' => false
        ),
        'hasFlash' => array(
          'type' => 'boolean',
          'description' => 'supports adobe flash',
          'sql' => "g.adobe_flash",
          'require' => array('g'),
          'update' => false
        ),
        'hasDirector' => array(
          'type' => 'boolean',
          'description' => 'supports adobe director',
          'sql' => "g.adobe_director",
          'require' => array('g'),
          'update' => false
        ),
        'hasQuicktime' => array(
          'type' => 'boolean',
          'description' => 'supports apple quicktime',
          'sql' => "g.apple_quicktime",
          'require' => array('g'),
          'update' => false
        ),
        'hasRealPlayer' => array(
          'type' => 'boolean',
          'description' => 'supports real player',
          'sql' => "g.real_player",
          'require' => array('g'),
          'update' => false
        ),
        'hasWindowsMedia' => array(
          'type' => 'boolean',
          'description' => 'supports windows media',
          'sql' => "g.windows_media",
          'require' => array('g'),
          'update' => false
        ),
        'mobileTheme' => array(
          'type' => 'boolean',
          'description' => 'used mobile theme',
          'sql' => "g.mobile_theme",
          'require' => array('g'),
          'update' => false
        ),
        'lastSeen' => array(
          'type' => 'datetime',
          'description' => 'last seen',
          'sql' => "(SELECT MAX(date_add) FROM "._DB_PREFIX_."connections conn WHERE conn.id_guest = g.id_guest)",
          'require' => array('g'),
          'update' => false
        )
      ),
      'expressions' => array(
      ),
      'links' => array(
        'customer' => array(
          'description' => "Customer",
          'collection' => 'customers',
          'type' => 'HAS_ONE',
          'sourceFields' => array('customerId'),
          'targetFields' => array('id'),
          'unidirectional' => true
        ),
        'sessions' => array(
          'description' => "Sessions",
          'collection' => 'sessions',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('visitorId'),
        ),
        'cart' => array(
          'description' => "Carts",
          'collection' => 'carts',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('visitorId')
        )
      )
    ));
  }
}

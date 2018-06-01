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

class Session {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'sessions',
      'singular' => 'session',
      'description' => 'Sessions',
      'key' => array('id'),
      'display' => 'name',
      'parameters' => array('shop', 'shopGroup', 'shareCustomers'),
      'category' => 'statistics',
      'psTab' => 'AdminStats',
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'tables' => array(
        'c'  => array(
          'table' => 'connections',
          'conditions' => array(
            '<bind-param:shop:c.id_shop>'
          )
        ),
        'g' => array(
          'table' => 'guest',
          'require' => array('c'),
          'join' => array(
            'type' => 'LEFT',
            'conditions' => array(
              'g.id_guest = c.id_guest'
            )
          )
        ),
        'cs' => array(
          'table' => 'connections_source',
          'require' => array('c'),
          'join' => array(
            'type' => 'LEFT',
            'conditions' => array(
              'c.id_connections = cs.id_connections',
              'cs.id_connections_source = (SELECT MIN(id_connections_source) FROM '._DB_PREFIX_.'connections_source cs2 WHERE cs2.id_connections = c.id_connections)'
            )
          )
        ),
        'cust' => array(
          'table' => 'customer',
          'require' => array('g'),
          'join' => array(
            'type' => 'LEFT',
            'conditions' => array(
              'cust.id_customer = g.id_customer',
              'if(<bind-param:shareCustomers:1>, <bind-param:shopGroup:cust.id_shop_group>, <bind-param:shop:cust.id_shop>)'
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'sql' => 'c.id_connections',
          'require' => array('c'),
          'selectRecord' => 'sessions',
          'update' => false
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'sql' => 'c.id_shop',
          'require' => array('c'),
          'update' => false,
          'hidden' => true
        ),
        'customerId' => array(
          'type' => 'number',
          'description' => 'customer id',
          'sql' => 'g.id_customer',
          'require' => array('g'),
          'selectRecord' => 'customers',
          'update' => false
        ),
        'visitorId' => array(
          'type' => 'number',
          'description' => 'visitor id',
          'sql' => 'c.id_guest',
          'selectRecord' => 'visitors',
          'require' => array('c'),
          'update' => false
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'sql' => "CONCAT('[', c.date_add, '] [', INET_NTOA(c.ip_address), '] ', COALESCE(TRIM(CONCAT(cust.firstname, ' ', cust.lastname)), 'Anonymous guest'))",
          'require' => array('cust', 'c'),
          'update' => false
        ),
        'ipAddress' => array(
          'type' => 'string',
          'description' => 'ip address',
          'sql' => "INET_NTOA(c.ip_address)",
          'require' => array('c'),
          'update' => false
        ),
        'referer' => array(
          'type' => 'string',
          'description' => 'HTTP referer',
          'sql' => "c.http_referer",
          'require' => array('c'),
          'update' => false
        ),
        'date' => array(
          'type' => 'datetime',
          'description' => 'date/time',
          'sql' => "c.date_add",
          'require' => array('c'),
          'update' => false
        ),
        'landingUrl' => array(
          'type' => 'string',
          'description' => 'landing url',
          'sql' => "cs.request_uri",
          'require' => array('cs'),
          'update' => false
        ),
        'keywords' => array(
          'type' => 'string',
          'description' => 'search engine keywords',
          'sql' => "cs.keywords",
          'require' => array('cs'),
          'update' => false
        ),
        'pages' => array(
          'type' => 'number',
          'description' => 'pages',
          'sql' => '(SELECT COUNT(id_page) FROM '._DB_PREFIX_.'connections_page cp WHERE cp.id_connections = c.id_connections)',
          'require' => array('c'),
          'update' => false
        ),
        'distinctPages' => array(
          'type' => 'number',
          'description' => 'distinct pages',
          'sql' => '(SELECT COUNT(distinct id_page) FROM '._DB_PREFIX_.'connections_page cp WHERE cp.id_connections = c.id_connections)',
          'require' => array('c'),
          'update' => false
        ),
      ),
      'expressions' => array(
        'date' => array(
          'type' => 'string',
          'expression' => "toString(<field:date>)",
          'description' => 'date/time'
        )
      ),
      'links' => array(
        'visitor' => array(
          'description' => "Visitor",
          'collection' => 'visitors',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('visitorId'),
          'targetFields' => array('id'),
        ),
        'pageViews' => array(
          'description' => "Page views",
          'collection' => 'pageViews',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('sessionId'),
        )
      )
    ));
  }
}

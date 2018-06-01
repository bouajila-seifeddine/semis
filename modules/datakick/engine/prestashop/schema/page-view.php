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

class PageView {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'pageViews',
      'singular' => 'pageView',
      'description' => 'Page Views',
      'key' => array('pageId'),
      'display' => 'name',
      'category' => 'statistics',
      'psTab' => 'AdminStats',
      'tables' => array(
        'cp'  => array(
          'table' => 'connections_page',
        ),
        'p' => array(
          'table' => 'page',
          'require' => array('cp'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'p.id_page = cp.id_page'
            )
          )
        ),
        'pt' => array(
          'table' => 'page_type',
          'require' => array('p'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'p.id_page_type = pt.id_page_type'
            )
          )
        ),
      ),
      'fields' => array(
        'pageId' => array(
          'type' => 'number',
          'description' => 'page id',
          'sql' => 'cp.id_page',
          'require' => array('cp'),
          'selectRecord' => 'pageViews',
          'update' => false
        ),
        'sessionId' => array(
          'type' => 'number',
          'description' => 'session id',
          'sql' => 'cp.id_connections',
          'require' => array('cp'),
          'selectRecord' => 'sessions',
          'update' => false
        ),
        'objectId' => array(
          'type' => 'number',
          'description' => 'object id',
          'sql' => 'p.id_object',
          'require' => array('p'),
          'update' => false
        ),
        'pageType' => array(
          'type' => 'string',
          'description' => 'page type',
          'sql' => 'pt.name',
          'require' => array('pt'),
          'update' => false
        ),
        'timeEnter' => array(
          'type' => 'datetime',
          'description' => 'enter date/time',
          'sql' => 'cp.time_start',
          'require' => array('cp'),
          'update' => false
        ),
        'timeLeave' => array(
          'type' => 'datetime',
          'description' => 'leave date/time',
          'sql' => 'cp.time_end',
          'require' => array('cp'),
          'update' => false
        ),
      ),

      'expressions' => array(
        'name' => array(
          'type' => 'string',
          'expression' => "if(<field:objectId> > 0, <field:pageType> + ': ' + <field:objectId>, <field:pageType>)",
          'description' => 'name'
        ),
        'duration' => array(
          'type' => 'number',
          'expression' => "coalesce(toUnixTimestamp(<field:timeLeave>) - toUnixTimestamp(<field:timeEnter>), 0)",
          'description' => 'duration'
        ),
        'timeEnter' => array(
          'type' => 'string',
          'expression' => "toString(<field:timeEnter>)",
          'description' => 'enter date/time',
        ),
        'timeLeave' => array(
          'type' => 'string',
          'expression' => "toString(<field:timeLeave>)",
          'description' => 'leave date/time',
        )
      ),

      'links' => array(
        'session' => array(
          'description' => "Session",
          'collection' => 'sessions',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('sessionId'),
          'targetFields' => array('id'),
        )
      )
    ));
  }
}

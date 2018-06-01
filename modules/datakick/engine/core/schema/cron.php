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
namespace Datakick\Schema\Core;

class Cron {
  public function register($dictionary) {
    $dictionary->registerSystemCollection(array(
      'id' => 'crons',
      'singular' => 'cron',
      'description' => 'Crons - Timers',
      'key' => array('cron'),
      'display' => 'cron',
      'category' => 'system',
      'create' => false,
      'delete' => false,
      'permissions' => array(
        'view' => true,
        'edit' => false,
        'delete' => false,
        'create' => false
      ),
      'tables' => array(
        'cron' => array(
          'table' => 'cron-type',
        ),
      ),
      'fields' => array(
        'cron' => array(
          'type' => 'string',
          'description' => 'name',
          'mapping' => array('cron' => 'cron'),
          'selectRecord' => 'crons',
          'update' => false
        ),
        'priority' => array(
          'type' => 'number',
          'description' => 'priority',
          'mapping' => array('cron' => 'priority'),
          'update' => false
        ),
        'active' => array(
          'type' => 'boolean',
          'description' => 'active',
          'update' => true,
          'mapping' => array(
            'cron' => 'active'
          )
        ),
        'history' => array(
          'type' => 'string',
          'description' => 'history',
          'mapping' => array('cron' => 'history'),
          'update' => false
        ),
        'last' => array(
          'type' => 'datetime',
          'description' => 'last execution',
          'mapping' => array('cron' => 'last'),
          'update' => false
        )
      ),
      'links' => array(
      )
    ));
  }
};

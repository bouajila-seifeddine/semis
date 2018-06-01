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

class Schedule {
  public function register($dictionary) {
    $dictionary->registerSystemCollection(array(
      'id' => 'schedules',
      'singular' => 'schedule',
      'description' => 'Schedule',
      'key' => array('id'),
      'display' => 'name',
      'category' => 'system',
      'usage' => 'schedule',
      'create' => true,
      'delete' => true,
      'permissions' => array(
        'view' => true,
      ),
      'restrictions' => array(
        'user' => array(
          'user' => '<field:userId>',
          'public' => '1',
        )
      ),
      'tables' => array(
        'schedule' => array(
          'table' => 'schedule',
        ),
        'type' => array(
          'table' => 'task-type',
          'require' => array('schedule'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "schedule.task_type = type.type",
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'unique id',
          'selectRecord' => 'schedules',
          'update' => false,
          'mapping' => array(
            'schedule' => 'id'
          )
        ),
        'frequency' => array(
          'type' => 'string',
          'description' => 'frequency',
          'update' => false,
          'required' => true,
          'mapping' => array(
            'schedule' => 'frequency'
          )
        ),
        'next' => array(
          'type' => 'datetime',
          'description' => 'next occurence',
          'update' => false,
          'required' => true,
          'mapping' => array(
            'schedule' => 'next'
          )
        ),
        'last' => array(
          'type' => 'datetime',
          'description' => 'last execution',
          'update' => false,
          'mapping' => array(
            'schedule' => 'last'
          )
        ),
        'typeId' => array(
          'type' => 'string',
          'description' => 'Type ID',
          'update' => false,
          'mapping' => array(
            'schedule' => 'task_type'
          )
        ),
        'type' => array(
          'type' => 'string',
          'description' => 'Type',
          'update' => false,
          'mapping' => array(
            'type' => 'name'
          )
        ),
        'taskName' => array(
          'type' => 'string',
          'description' => 'Name',
          'update' => false,
          'mapping' => array(
            'type' => 'name'
          )
        ),
        'recordType' => array(
          'type' => 'string',
          'description' => 'Record type',
          'update' => false,
          'mapping' => array(
            'schedule' => 'record_type'
          )
        ),
        'recordId' => array(
          'type' => 'number',
          'description' => 'Record id',
          'update' => false,
          'mapping' => array(
            'schedule' => 'record_id'
          )
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'Name',
          'update' => true,
          'mapping' => array(
            'schedule' => 'name'
          )
        ),
        'processing' => array(
          'type' => 'string',
          'description' => 'Processing',
          'update' => false,
          'mapping' => array(
            'schedule' => 'processing'
          )
        ),
        'active' => array(
          'type' => 'boolean',
          'description' => 'is enabled',
          'update' => true,
          'mapping' => array(
            'schedule' => 'active'
          )
        ),
        'userId' => array(
          'type' => 'number',
          'description' => 'User id',
          'selectRecord' => array(
            'role' => 'user'
          ),
          'update' => true,
          'mapping' => array(
            'schedule' => 'user_id'
          )
        )
      ),
      'links' => array(
        'parameters' => array(
          'description' => 'Parameters',
          'collection' => 'scheduleParameters',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('scheduleId'),
          'delete' => true,
        ),
        'owner' => array(
          'description' => 'Owner',
          'collection' => array(
            'role' => 'user'
          ),
          'type' => 'BELONGS_TO',
          'sourceFields' => array('userId'),
          'targetFields' => array('id'),
          'generateReverse' => array(
            'id' => 'schedules',
            'description' => 'Scheduled tasks',
            'type' => 'HAS_MANY'
          )
        )
      )
    ));

    $dictionary->registerSystemCollection(array(
      'id' => 'scheduleParameters',
      'singular' => 'scheduleParameter',
      'description' => 'Schedule Parameter',
      'key' => array('scheduleId', 'name'),
      'category' => 'system',
      'display' => 'value',
      'delete' => true,
      'permissions' => array(
        'view' => true,
      ),
      'tables' => array(
        'd' => array(
          'table' => 'schedule-parameter',
        )
      ),
      'fields' => array(
        'scheduleId' => array(
          'type' => 'number',
          'description' => 'schedule id',
          'mapping' => array('d' => 'schedule_id'),
          'selectRecord' => 'schedules',
          'update' => false
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'Name',
          'mapping' => array('d' => 'name'),
          'update' => false
        ),
        'value' => array(
          'type' => 'string',
          'description' => 'Value',
          'mapping' => array('d' => 'value'),
          'update' => false
        )
      ),
      'links' => array(
        'schedule' => array(
          'description' => 'Schedule',
          'collection' => 'schedules',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('scheduleId'),
          'targetFields' => array('id')
        )
      )
    ));
  }
}

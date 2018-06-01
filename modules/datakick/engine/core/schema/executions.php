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

class Executions {
  public function register($dictionary) {
    $dictionary->registerSystemCollection(array(
      'id' => 'executions',
      'singular' => 'execution',
      'description' => 'Executions',
      'key' => array('id'),
      'category' => 'system',
      'display' => 'name',
      'create' => true,
      'delete' => true,
      'permissions' => array(
        'view' => true
      ),
      'restrictions' => array(
        'user' => array(
          'user' => '<field:userId>',
          'public' => '0',
        )
      ),
      'tables' => array(
        'e' => array(
          'table' => 'executions',
        ),
        'type' => array(
          'table' => 'task-type',
          'mapping' => array('e'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "e.task_type = type.type",
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'mapping' => array('e' => 'id'),
          'selectRecord' => 'executions',
          'update' => false
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'Name',
          'mapping' => array('e' => 'name'),
          'update' => false
        ),
        'taskType' => array(
          'type' => 'string',
          'description' => 'Type ID',
          'mapping' => array('e' => 'task_type'),
          'update' => false
        ),
        'recordType' => array(
          'type' => 'string',
          'description' => 'Record type',
          'mapping' => array('e' => 'record_type'),
          'update' => false
        ),
        'recordId' => array(
          'type' => 'number',
          'description' => 'Record id',
          'mapping' => array('e' => 'record_id'),
          'update' => false
        ),
        'type' => array(
          'type' => 'string',
          'description' => 'Type',
          'mapping' => array('type' => 'name'),
          'update' => false
        ),
        'source' => array(
          'type' => 'string',
          'description' => 'Source',
          'mapping' => array('e' => 'source'),
          'update' => false
        ),
        'sourceId' => array(
          'type' => 'number',
          'description' => 'Source ID',
          'mapping' => array('e' => 'source_id'),
          'update' => false
        ),
        'start' => array(
          'type' => 'datetime',
          'description' => 'Date',
          'mapping' => array('e' => 'start'),
          'update' => false
        ),
        'duration' => array(
          'type' => 'number',
          'description' => 'Duration',
          'mapping' => array('e' => 'duration'),
          'update' => false
        ),
        'status' => array(
          'type' => 'string',
          'description' => 'Status',
          'mapping' => array('e' => 'status'),
          'values' => array(
            'running' => 'Running',
            'deferred' => 'Deferred',
            'failed' => 'Failed',
            'killed' => 'Killed',
            'paused' => 'Paused',
            'success' => 'Success'
          ),
          'update' => true
        ),
        'error' => array(
          'type' => 'string',
          'description' => 'Error message',
          'mapping' => array('e' => 'error'),
          'update' => false
        ),
        'result' => array(
          'type' => 'string',
          'description' => 'Result',
          'mapping' => array('e' => 'result'),
          'update' => false
        ),
        'lastUpdated' => array(
          'type' => 'datetime',
          'description' => 'Last updated',
          'mapping' => array('e' => 'last_updated'),
          'update' => false
        ),
        'record' => array(
          'type' => 'string',
          'description' => 'Record',
          'mapping' => array('e' => 'record'),
          'update' => false
        ),
        'userId' => array(
          'type' => 'number',
          'description' => 'User id',
          'mapping' => array('e' => 'user_id'),
          'selectRecord' => array(
            'role' => 'user'
          ),
          'update' => false
        ),
      ),
      'links' => array(
        'parameters' => array(
          'description' => 'Parameters',
          'collection' => 'executionParameters',
          'type' => 'HAS_MANY',
          'delete' => true,
          'sourceFields' => array('id'),
          'targetFields' => array('executionId')
        ),
        'owner' => array(
          'description' => 'User',
          'collection' => array(
            'role' => 'user'
          ),
          'type' => 'BELONGS_TO',
          'sourceFields' => array('userId'),
          'targetFields' => array('id'),
          'generateReverse' => array(
            'id' => 'executions',
            'description' => 'Executions',
            'type' => 'HAS_MANY'
          )
        )
      )
    ));

    $dictionary->registerSystemCollection(array(
      'id' => 'executionParameters',
      'singular' => 'executionParameter',
      'description' => 'Execution Parameter',
      'key' => array('executionId', 'name'),
      'category' => 'system',
      'display' => 'name',
      'permissions' => array(
        'view' => true,
        'edit' => false,
        'create' => false,
        'delete' => false
      ),
      'tables' => array(
        'd' => array(
          'table' => 'execution-parameters',
        )
      ),
      'fields' => array(
        'executionId' => array(
          'type' => 'number',
          'description' => 'execution id',
          'mapping' => array('d' => 'execution_id'),
          'selectRecord' => 'executions',
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
        'execution' => array(
          'description' => 'Execution',
          'collection' => 'executions',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('executionId'),
          'targetFields' => array('id')
        )
      )
    ));
  }
}

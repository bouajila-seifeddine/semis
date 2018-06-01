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

class Endpoint {
  public function register($dictionary) {
    $dictionary->registerSystemCollection(array(
      'id' => 'endpoints',
      'singular' => 'endpoint',
      'description' => 'Endpoint',
      'key' => array('id'),
      'category' => 'system',
      'display' => 'name',
      'usage' => 'endpoint',
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
        'endpoint' => array(
          'table' => 'endpoint',
        ),
        'type' => array(
          'table' => 'task-type',
          'require' => array('endpoint'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "endpoint.task_type = type.type",
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'selectRecord' => 'endpoints',
          'description' => 'unique id',
          'mapping' => array(
            'endpoint' => 'id'
          ),
          'update' => false
        ),
        'endpoint' => array(
          'type' => 'string',
          'description' => 'endpoint',
          'mapping' => array(
            'endpoint' => 'endpoint'
          ),
          'update' => false
        ),
        'typeId' => array(
          'type' => 'string',
          'description' => 'Type ID',
          'mapping' => array(
            'endpoint' => 'task_type'
          ),
          'update' => false
        ),
        'type' => array(
          'type' => 'string',
          'description' => 'Type',
          'mapping' => array(
            'type' => 'name'
          ),
          'update' => false
        ),
        'recordType' => array(
          'type' => 'string',
          'description' => 'Record type',
          'mapping' => array(
            'endpoint' => 'record_type'
          ),
          'update' => false
        ),
        'recordId' => array(
          'type' => 'number',
          'description' => 'Record id',
          'mapping' => array(
            'endpoint' => 'record_id'
          ),
          'update' => false
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'Name',
          'mapping' => array(
            'endpoint' => 'name'
          ),
          'update' => true,
        ),
        'active' => array(
          'type' => 'boolean',
          'description' => 'is enabled',
          'update' => true,
          'mapping' => array(
            'endpoint' => 'active'
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
            'endpoint' => 'user_id'
          )
        ),
      ),
      'links' => array(
        'parameters' => array(
          'description' => 'Parameters',
          'collection' => 'endpointParameters',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('endpointId'),
          'delete' => true
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
            'id' => 'endpoints',
            'description' => 'Endpoints',
            'type' => 'HAS_MANY'
          )
        )
      )
    ));

    $dictionary->registerSystemCollection(array(
      'id' => 'endpointParameters',
      'singular' => 'endpointParameter',
      'description' => 'Endpoint Parameter',
      'key' => array('endpointId', 'name'),
      'category' => 'system',
      'display' => 'value',
      'delete' => true,
      'create' => true,
      'permissions' => array(
        'view' => true,
      ),
      'tables' => array(
        'd' => array(
          'table' => 'endpoint-parameter',
        )
      ),
      'fields' => array(
        'endpointId' => array(
          'type' => 'number',
          'description' => 'endpoint id',
          'mapping' => array('d' => 'endpoint_id'),
          'selectRecord' => 'endpoints',
          'update' => false,
          'required' => true,
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'Name',
          'mapping' => array('d' => 'name'),
          'update' => false,
          'required' => true,
        ),
        'value' => array(
          'type' => 'string',
          'description' => 'Value',
          'mapping' => array('d' => 'value'),
          'update' => false
        ),
        'param' => array(
          'type' => 'string',
          'description' => 'Endpoint parameter name',
          'mapping' => array('d' => 'param'),
          'update' => false
        )
      ),
      'links' => array(
        'endpoint' => array(
          'description' => 'Endpoint',
          'collection' => 'endpoints',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('endpointId'),
          'targetFields' => array('id')
        )
      )
    ));
  }
}

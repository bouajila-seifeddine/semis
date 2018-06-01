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

class ImportDatasources {
  public function register($dictionary) {
    $dictionary->registerSystemCollection(array(
      'id' => 'importDatasources',
      'singular' => 'importDatasource',
      'description' => 'Import Datasources',
      'key' => array('id'),
      'category' => 'system',
      'display' => 'name',
      'create' => true,
      'delete' => true,
      'usage' => 'import-datasource',
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
        'ds' => array(
          'table' => 'import-datasource',
          'create' => array(
            'source_refreshed' => '<param:timestamp>'
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'selectRecord' => 'importDatasources',
          'description' => 'unique id',
          'mapping' => array(
            'ds' => 'id'
          ),
          'update' => false
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'Name',
          'update' => true,
          'mapping' => array(
            'ds' => 'name'
          )
        ),
        'fileType' => array(
          'type' => 'string',
          'required' => true,
          'description' => 'File Type',
          'update' => true,
          'mapping' => array(
            'ds' => 'filetype'
          )
        ),
        'structureSha1' => array(
          'type' => 'string',
          'required' => true,
          'description' => 'Structure SHA1',
          'update' => true,
          'mapping' => array(
            'ds' => 'structure_sha1'
          )
        ),
        'structure' => array(
          'type' => 'string',
          'required' => true,
          'description' => 'Structure',
          'update' => true,
          'mapping' => array(
            'ds' => 'structure'
          )
        ),
        'sourceType' => array(
          'type' => 'string',
          'description' => 'Source type',
          'update' => true,
          'mapping' => array(
            'ds' => 'source_type'
          ),
          'values' => array(
            'upload' => 'Uploaded file',
            'url' => "URL",
            'file' => "File on server"
          )
        ),
        'source' => array(
          'type' => 'string',
          'description' => 'Source',
          'update' => true,
          'mapping' => array(
            'ds' => 'source'
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
            'ds' => 'user_id'
          )
        ),
      ),
      'links' => array(
        'owner' => array(
          'description' => 'Owner',
          'collection' => array(
            'role' => 'user'
          ),
          'type' => 'BELONGS_TO',
          'sourceFields' => array('userId'),
          'targetFields' => array('id'),
          'generateReverse' => array(
            'id' => 'importDatasources',
            'description' => 'Import Datasources',
            'type' => 'HAS_MANY'
          )
        )
      )
    ));
  }
}

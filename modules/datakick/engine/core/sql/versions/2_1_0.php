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
namespace Datakick;

class MigrationVersion_2_1_0 extends MigrationVersion {

  protected function registerTables() {
    $this->registerTable('executions',
      array(
        'id' => 'int(11) NOT NULL AUTO_INCREMENT',
        'name' => 'varchar(256)',
        'start' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'last_updated' => 'TIMESTAMP',
        'source' => 'varchar(40)',
        'source_id' => 'int(11)',
        'task_type' => 'varchar(50)',
        'record_type' => 'varchar(50)',
        'record_id' => 'int(11)',
        'record' => 'mediumtext',
        'status' => 'varchar(40)',
        'duration' => 'decimal(21, 4)',
        'full_error' => 'mediumtext',
        'error' => 'mediumtext',
        'result' => 'mediumtext',
        'user_id' => 'int(11)'
      ), array('id'));

    $this->registerTable('execution-parameters',
      array(
        'execution_id' => 'int(11) NOT NULL',
        'name' => 'varchar(50)',
        'value' => 'varchar(256)'
      ),
      array('execution_id', 'name'),
      array('executions' => array(
        'source' => array('execution_id'),
        'target' => array('id')
      )));

    $this->registerTable('xml-templates',
      array(
        'id' => 'int(11) NOT NULL AUTO_INCREMENT',
        'name' => 'varchar(256)',
        'description' => 'text',
        'template' => 'mediumtext',
        'parsed' => 'mediumtext',
        'user_parameters' => 'text',
        'required_parameters' => 'text',
        'icon' => 'varchar(256)',
        'image' => 'varchar(256)',
        'user_id' => 'int(11)',
        'public' => 'int(1)',
      ), array('id'));

    $this->registerTable('lists',
      array(
        'id' => 'int(11) NOT NULL AUTO_INCREMENT',
        'name' => 'varchar(256)',
        'description' => 'text',
        'definition' => 'mediumtext',
        'parsed' => 'mediumtext',
        'required_parameters' => 'text',
        'icon' => 'varchar(256)',
        'image' => 'varchar(256)',
        'user_id' => 'int(11)',
        'public' => 'int(1)',
      ), array('id'));

    $this->registerTable('mass-updates',
      array(
        'id' => 'int(11) NOT NULL AUTO_INCREMENT',
        'name' => 'varchar(256)',
        'description' => 'text',
        'definition' => 'mediumtext',
        'parsed' => 'mediumtext',
        'required_parameters' => 'text',
        'icon' => 'varchar(256)',
        'image' => 'varchar(256)',
        'user_id' => 'int(11)',
        'public' => 'int(1)'
      ), array('id'));

    $this->registerTable('task-type',
      array(
        'type' => 'varchar(50)',
        'name' => 'varchar(256)',
      ), array('type'));

    $this->registerTable('endpoint',
      array(
        'id' => 'int(11) NOT NULL AUTO_INCREMENT',
        'endpoint' => 'varchar(50)',
        'name' => 'varchar(50)',
        'task_type' => 'varchar(50)',
        'record_type' => 'varchar(50)',
        'record_id' => 'int(11)',
        'active' => 'tinyint(1) NOT NULL DEFAULT 1',
        'user_id' => 'int(11)'
      ), array('id'));

    $this->registerTable('endpoint-parameter',
      array(
        'endpoint_id' => 'int(11) NOT NULL',
        'name' => 'varchar(50)',
        'param' => 'varchar(50)',
        'value' => 'varchar(4096)'
      ),
      array('endpoint_id', 'name'),
      array('endpoint' => array(
        'source' => array('endpoint_id'),
        'target' => array('id')
      )));

    $this->registerTable('schedule',
      array(
        'id' => 'int(11) NOT NULL AUTO_INCREMENT',
        'name' => 'varchar(50)',
        'frequency' => 'varchar(50) NOT NULL',
        'next' => 'datetime NOT NULL',
        'task_type' => 'varchar(50)',
        'record_type' => 'varchar(50)',
        'record_id' => 'int(11)',
        'last' => 'datetime',
        'last_execution_id' => 'int(11)',
        'processing' => 'varchar(50)',
        'active' => 'tinyint(1) NOT NULL DEFAULT 1',
        'user_id' => 'int(11)'
      ), array('id'));

    $this->registerTable('schedule-parameter',
      array(
        'schedule_id' => 'int(11) NOT NULL',
        'name' => 'varchar(50)',
        'value' => 'varchar(4096)',
      ),
      array('schedule_id', 'name'),
      array('schedule' => array(
        'source' => array('schedule_id'),
        'target' => array('id')
      )));

    $this->registerTable('place',
      array(
        'id' => 'int(11) NOT NULL AUTO_INCREMENT',
        'type' => 'varchar(50)',
        'name' => 'varchar(256)',
      ), array('id'));

    $this->registerTable('place-config',
      array(
        'place_id' => 'int(11) NOT NULL',
        'name' => 'varchar(50)',
        'value' => 'varchar(256)'
      ),
      array('place_id', 'name'),
      array('place' => array(
        'source' => array('place_id'),
        'target' => array('id')
      )));

    $this->registerTable('cron-type',
      array(
        'cron' => 'varchar(50) NOT NULL',
        'priority' => 'tinyint NOT NULL DEFAULT 100',
        'active' => 'tinyint NOT NULL DEFAULT 1',
        'history' => 'varchar(1024)',
        'last' => 'datetime',
      ), array('cron'));

    $this->registerTable('custom-table',
      array(
        'id' => 'int(11) NOT NULL AUTO_INCREMENT',
        'collection' => 'varchar(256) NOT NULL',
        'table_name' => 'varchar(64) NOT NULL',
      ), array('id'));

    $this->registerTable('custom-field',
      array(
        'id' => 'int(11) NOT NULL AUTO_INCREMENT',
        'custom_table_id' => 'int(11) NOT NULL',
        'alias' => 'varchar(80) NOT NULL',
        'fieldset' => 'varchar(256)',
        'name' => 'varchar(256) NOT NULL',
        'type' => 'varchar(50) NOT NULL',
        'subtype' => 'varchar(50)',
        'description' => 'text',
        'position' => 'tinyint NOT NULL',
        'column_name' => 'varchar(80) NOT NULL',
        'created' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
      ),
      array('id'),
      array('custom-table' => array(
        'source' => array('custom_table_id'),
        'target' => array('id')
      )),
      array(
        array('custom_table_id', 'alias'),
        array('custom_table_id', 'column_name')
      )
    );

    $this->registerTable('options',
      array(
        'name' => 'varchar(50) NOT NULL',
        'user_id' => 'int(11) NOT NULL',
        'type' => 'varchar(20)',
        'value' => 'varchar(256)'
      ),
      array('name', 'user_id'));

    $this->registerTable('user-permissions',
      array(
        'user_id' => 'int(11) NOT NULL',
        'permission' => 'varchar(50) NOT NULL',
        'view' => 'tinyint(1)',
        'create' => 'tinyint(1)',
        'edit' => 'tinyint(1)',
        'delete' => 'tinyint(1)'
      ),
      array('user_id', 'permission'));

    $this->registerTable('role-permissions',
      array(
        'role_id' => 'int(11) NOT NULL',
        'permission' => 'varchar(50) NOT NULL',
        'view' => 'tinyint(1)',
        'create' => 'tinyint(1)',
        'edit' => 'tinyint(1)',
        'delete' => 'tinyint(1)'
      ),
      array('role_id', 'permission'));

    $this->registerTable('user-restrictions',
      array(
        'user_id' => 'int(11) NOT NULL',
        'restriction' => 'varchar(50) NOT NULL',
        'read' => 'varchar(80)',
        'write' => 'varchar(80)'
      ),
      array('user_id', 'restriction'));

    $this->registerTable('role-restrictions',
      array(
        'role_id' => 'int(11) NOT NULL',
        'restriction' => 'varchar(50) NOT NULL',
        'read' => 'varchar(80)',
        'write' => 'varchar(80)'
      ),
      array('role_id', 'restriction'));

    $this->registerTable('import-datasource',
      array(
        'id' => 'int(11) NOT NULL AUTO_INCREMENT',
        'user_id' => 'int(11)',
        'name' => 'varchar(255)',
        'filetype' => 'varchar(20)',
        'structure_sha1' => 'char(40) NOT NULL',
        'structure' => 'mediumtext',
        'source_type' => 'varchar(40)',
        'source' => 'varchar(512)',
        'source_refreshed' => 'datetime NOT NULL'
      ), array('id'));

    $this->registerTable('import-definition',
      array(
        'id' => 'int(11) NOT NULL AUTO_INCREMENT',
        'name' => 'varchar(256)',
        'description' => 'text',
        'definition' => 'mediumtext',
        'parsed' => 'mediumtext',
        'icon' => 'varchar(256)',
        'image' => 'varchar(256)',
        'user_id' => 'int(11)',
        'public' => 'int(1)',
      ), array('id'));

    $this->registerTable('assets',
      array(
        'hash' => 'char(40) NOT NULL',
        'type' => 'varchar(80)',
        'name' => 'varchar(512)',
        'source_type' => 'varchar(40)',
        'origin' => 'varchar(1024)',
        'size' => 'int(11)',
        'created' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'last_checked' => 'TIMESTAMP',
      ), array('hash'));
  }

  protected function getData() {
    return array(
      'cron-type' => array(
        array('cron' => 'cronjobs', 'active' => 1, 'priority' => 100),
        array('cron' => 'cron', 'active' => 1, 'priority' => 1),
        array('cron' => 'webcron', 'active' => 1, 'priority' => 50)
      ),
      'task-type' => array(
        array('type' => 'xml-feed', 'name' => 'XML feed'),
        array('type' => 'xml-file', 'name' => 'XML file'),
        array('type' => 'csv-feed', 'name' => 'CSV feed'),
        array('type' => 'csv-file', 'name' => 'CSV file'),
        array('type' => 'mass-update', 'name' => 'Mass update')
      ),
      'place' => array(
        array('id' => 1, 'type' => 'local', 'name' => 'Data folder')
      ),
      'place-config' => array(
        array('place_id' => 1, 'name' => 'root', 'value' => 'data')
      )
    );
  }

  public function migrateData($factory, $conn) {
    $execution = $this->getTable('executions');

    $sql=array();

    // remove task types
    $sql[] = "UPDATE $execution SET last_updated=start;";
    $sql[] = "UPDATE $execution SET full_error=error, error='There has been an error' WHERE error IS NOT NULL;";
    $sql[] = "UPDATE $execution SET status='failed', error='timeout' WHERE status='running' AND last_updated < DATE_ADD(NOW(), INTERVAL -10 MINUTE);";

    foreach ($sql as $query) {
      if ($this->getConnection()->execute($query) == false) {
        return false;
      }
    }
    return true;
  }
}

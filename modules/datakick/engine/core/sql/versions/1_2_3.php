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

class MigrationVersion_1_2_3 extends MigrationVersion {

  protected function registerTables() {
    $this->registerTable('executions',
      array(
        'id' => 'int(11) NOT NULL AUTO_INCREMENT',
        'name' => 'varchar(256)',
        'start' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'source' => 'varchar(40)',
        'source_id' => 'int(11)',
        'task_type' => 'varchar(50)',
        'record_type' => 'varchar(50)',
        'record_id' => 'int(11)',
        'status' => 'varchar(40)',
        'duration' => 'decimal(21, 4)',
        'error' => 'text',
        'result' => 'text'
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
        'image' => 'varchar(256)'
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
        'image' => 'varchar(256)'
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
      ),
      'place' => array(
        array('id' => 1, 'type' => 'local', 'name' => 'Data folder')
      ),
      'place-config' => array(
        array('place_id' => 1, 'name' => 'root', 'value' => 'data')
      )
    );
  }

  private function migrateTaskId($table) {
    $task = $this->getTable('task');
    $taskdata = $this->getTable('task-data');
    $clause = "(SELECT id, type AS task_type, IF(name='listId', 'lists', 'xmlTemplates') AS record_type, value AS record_id FROM $task t JOIN $taskdata d ON (t.id = d.task_id))";
    $s = array();
    if (! $this->columnExists($table, 'task_type'))
      $s[] = "ALTER TABLE $table ADD COLUMN task_type varchar(50);";

    if (! $this->columnExists($table, 'record_type'))
      $s[] = "ALTER TABLE $table ADD COLUMN record_type varchar(50);";

    if (! $this->columnExists($table, 'record_id'))
      $s[] = "ALTER TABLE $table ADD COLUMN record_id int(11);";

    if ($this->tableExists($task) && $this->tableExists($taskdata))
      $s[] = "UPDATE $table x JOIN $clause c ON (x.task_id = c.id) SET x.task_type = c.task_type, x.record_type = c.record_type, x.record_id = c.record_id;";

    if ($this->columnExists($table, 'task_id'))
      $s[] = "ALTER TABLE $table DROP COLUMN task_id;";

    $s[] = "DELETE FROM $table WHERE task_type IS NULL OR task_type = '';";
    return $s;
  }

  public function migrate() {
    // custom migrate function
    $xml = $this->getTable('xml-templates');
    $list = $this->getTable('lists');
    $task = $this->getTable('task');
    $tasktype = $this->getTable('task-type');
    $taskdata = $this->getTable('task-data');
    $execution = $this->getTable('executions');
    $endpoint = $this->getTable('endpoint');
    $endpointParameter = $this->getTable('endpoint-parameter');
    $schedule = $this->getTable('schedule');

    // remove constraints
    $this->dropConstrainsToTable($task);

    $sql=array();

    // remove task types
    $sql[] = "DELETE FROM $execution WHERE task_type='xml-preview' OR task_type='list-preview';";
    $sql[] = "DELETE FROM $tasktype WHERE type='xml-preview' OR type='list-preview';";

    if ($this->tableExists($taskdata)) {
      $sql[] = "DELETE FROM $taskdata WHERE name NOT IN ('xmlTemplateId', 'listId');";
    }

    $sql = array_merge($sql, $this->migrateTaskId($execution));
    $sql = array_merge($sql, $this->migrateTaskId($endpoint));
    $sql = array_merge($sql, $this->migrateTaskId($schedule));

    // add active column to schedule
    if (! $this->columnExists($schedule, 'active')) {
      $sql[] = "ALTER TABLE $schedule ADD COLUMN active tinyint(1) NOT NULL DEFAULT 1;";
      $sql[] = "UPDATE $schedule SET active = 1;";
    }

    // add active column to endpoint
    if (! $this->columnExists($endpoint, 'active')) {
      $sql[] = "ALTER TABLE $endpoint ADD COLUMN active tinyint(1) NOT NULL DEFAULT 1;";
      $sql[] = "UPDATE $endpoint SET active = 1;";
    }

    // remove task and taskdata table
    $sql[] = "DROP TABLE IF EXISTS $taskdata;";
    $sql[] = "DROP TABLE IF EXISTS $task;";

    $sql[] = "INSERT INTO $endpointParameter(endpoint_id, name, value) SELECT id, 'task::exportColumnNames', '1' from $endpoint where task_type = 'csv-feed' and not exists(select 1 from $endpointParameter where endpoint_id=id and name='task::exportColumnNames');";
    $sql[] = "INSERT INTO $endpointParameter(endpoint_id, name, value) SELECT id, 'task::separator', 'comma' from $endpoint where task_type = 'csv-feed' and not exists(select 1 from $endpointParameter where endpoint_id=id and name='task::separator');";

    foreach ($sql as $query) {
      if ($this->getConnection()->execute($query) == false) {
        return false;
      }
    }
    return true;
  }
}

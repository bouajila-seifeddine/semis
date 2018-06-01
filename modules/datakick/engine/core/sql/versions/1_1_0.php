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

class MigrationVersion_1_1_0 extends MigrationVersion {

  protected function registerTables() {
    $this->registerTable('executions',
      array(
        'id' => 'int(11) NOT NULL AUTO_INCREMENT',
        'start' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'source' => 'varchar(40)',
        'task_type' => 'varchar(50)',
        'task_id' => 'int(11)',
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
        'template' => 'text',
        'parsed' => 'text',
        'user_parameters' => 'text',
        'required_parameters' => 'text',
        'icon' => 'varchar(256)',
        'image' => 'varchar(256)'
      ), array('id'));

    $this->registerTable('task-type',
      array(
        'type' => 'varchar(50)',
        'name' => 'varchar(256)',
      ), array('type'));

    $this->registerTable('task',
      array(
        'id' => 'int(11) NOT NULL AUTO_INCREMENT',
        'type' => 'varchar(50)'
      ),
      array('id'),
      array('task-type' => array(
        'source' => array('type'),
        'target' => array('type')
      )));

    $this->registerTable('task-data',
      array(
        'task_id' => 'int(11) NOT NULL',
        'name' => 'varchar(50)',
        'value' => 'varchar(256)',
      ),
      array('task_id', 'name'),
      array('task' => array(
        'source' => array('task_id'),
        'target' => array('id')
      )));

    $this->registerTable('endpoint',
      array(
        'id' => 'int(11) NOT NULL AUTO_INCREMENT',
        'endpoint' => 'varchar(50)',
        'name' => 'varchar(50)',
        'task_id' => 'int(11) NOT NULL',
      ),
      array('id'),
      array('task' => array(
        'source' => array('task_id'),
        'target' => array('id'),
      )));

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
        'task_id' => 'int(11) NOT NULL',
        'last' => 'datetime',
        'last_execution_id' => 'int(11)',
        'processing' => 'varchar(50)'
      ),
      array('id'),
      array('task' => array(
        'source' => array('task_id'),
        'target' => array('id'),
      )));

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
        array('type' => 'xml-preview', 'name' => 'Generate XML preview'),
        array('type' => 'xml-feed', 'name' => 'XML feed'),
        array('type' => 'xml-file', 'name' => 'XML file'),
      ),
      'place' => array(
        array('id' => 1, 'type' => 'local', 'name' => 'Data folder')
      ),
      'place-config' => array(
        array('place_id' => 1, 'name' => 'root', 'value' => 'data')
      )
    );
  }
}

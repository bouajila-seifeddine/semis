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

class XmlImporter extends XmlReader {
  const IMPORT = 'IMPORT';
  const DRY_RUN = 'DRY_RUN';
  const TEST_MATCHING = 'TEST_MATCHING';

  private $root;
  private $builder;
  private $mode;
  private $init;
  private $executor;
  private $cnt = 0;
  private $state;

  public function __construct(Factory $factory, Context $context, $definition, $mode=self::IMPORT, $state=null) {
    $this->root = $definition['dataset']['root'];
    $this->executor = $mode === self::IMPORT ? new DBExecutor($factory) : new DryRunExecutor();
    $this->builder = new XmlRecordBuilder($factory, $context, $this->executor, $definition, self::getDepth($this->root));
    $this->mode = $mode;
    $importMode = $definition['importMode']['type'];
    $this->state = is_null($state) ? $this->getInitState() : $state;

    if ($importMode === 'replace' && $mode === self::IMPORT) {
      $collection = Utils::extract('collection', $definition);
      $this->init = $factory->getModification($context);
      $this->init->addDelete($collection);
      $this->init->addStatement(new ResetAutoIncrement($factory->getDictionary()->getCollection($collection)));
    }
  }

  public function getState() {
    return $this->state;
  }

  public function before($progress) {
    $ret = true;
    if ($this->getStage() === 'prepare') {
      if ($this->init) {
        $ret = $this->executor->execute($this->init, $progress);
      }
      $progress->setProgress(1, 1, $this->nextStage($ret));
    }
    return $ret;
  }

  public function nextStage($result=null) {
    $this->state = $this->nextStageState($result);
    return $this->state;
  }

  public function setTotal($total) {
    $this->state['total'] = $total;
    return $this->state;
  }

  private function getInitState() {
    return array(
      'stage' => 'init',
      'total' => -1,
      'completed' => 0,
      'entries' => array()
    );
  }

  public function getStage() {
    return $this->state['stage'];
  }

  private function nextStageState($result) {
    $copy = $this->state;
    $copy['stage'] = self::getNextStage($this->mode, $copy['stage']);
    return $copy;
  }

  private static function getNextStage($mode, $stage) {
    $stages;
    if ($mode === self::IMPORT || $mode === self::DRY_RUN) {
      $stages = array(
        'init' => 'file',
        'file' => 'count',
        'count' => 'prepare',
        'prepare' => 'import',
        'import' => 'completed'
      );
    } else if ($mode === self::TEST_MATCHING){
      $stages = array(
        'init' => 'file',
        'file' => 'testing',
        'testing' => 'completed'
      );
    }
    if (isset($stages[$stage])) {
      return $stages[$stage];
    }
    throw new \Exception("Next stage not exists for $stage");
  }

  public function after($progress) {
    $this->nextStage($this->state);
    $this->executor->cleanup($progress);
    return $this->state;
  }

  public function enterNode($path, $node) {
    $builder = $this->getBuilder($path);
    if ($builder) {
      if ($this->isCollectionNode($path)) {
        $this->builder->init();
      }
      $builder->enterNode($path, $node);
    }
  }

  public function leaveNode($path, $node) {
    $builder = $this->getBuilder($path);
    if ($builder) {
      $builder->leaveNode($path, $node);
      if ($this->isCollectionNode($path)) {
        $this->cnt++;
        // ignore this node if it's already completed
        if ($this->cnt <= $this->state['completed']) {
          return;
        }
        $ret = null;
        if ($this->mode === self::DRY_RUN || $this->mode === self::IMPORT) {
          $ret = $builder->execute($this->getProgress());
        } else if ($this->mode === self::TEST_MATCHING) {
          $ret = $builder->testMatching();
        } else {
          throw new \Exception("Invalid import mode: {$this->mode}");
        }
        $this->updateState($ret, $builder);
      }
    }
  }

  private function isCollectionNode($path) {
    $id = $this->printPath($path);
    return $id ===  $this->root;
  }

  private function getBuilder($path) {
    $id = $this->printPath($path);
    if (strpos($id, $this->root) === 0) {
      return $this->builder;
    }
    return null;
  }

  private function updateState($ret, $builder) {
    $this->state['completed'] = $this->cnt;
    if ($ret !== false) {
      $this->state['entries'][] = $ret;
    } else {
      $this->state['entries'][] = array(
        'status' => 'failed',
        'errors' => $builder->getErrors(false),
        'fullErrors' => $builder->getErrors(true)
      );
    }
    $this->getProgress()->setProgress($this->state['total'], $this->state['completed'], $this->state);
  }

  private static function getDepth($path) {
    return substr_count($path, "/") - 1;
  }

}

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

class XmlRecordBuilder extends ImportRecordBuilder {
  // dynamic
  private $currPath;

  // static processing instructions
  private $emptyNode;
  private $depth;
  private $collectPaths = array();
  private $parameterPaths = array();
  private $fieldsPaths = array();
  private $fields = array();
  private $relations = array();
  private $match = array();

  public function __construct(Factory $factory, Context $context, ImportExecutor $executor, $definition, $depth) {
    parent::__construct($factory, $context, $executor, $definition);
    $this->depth = $depth;
    $this->emptyNode = new XmlNode('n/a');
  }

  public function init() {
    parent::init();
    $this->currPath = array(new XmlNode('.'));
  }

  public function enterNode($path, $node) {
    $localPath = $this->getLocalPath($path);
    try {
      if ($localPath != '.' && isset($this->collectPaths[$localPath])) {
        $curr = $this->currPath[count($this->currPath) - 1];
        array_push($this->currPath, $curr->enter($node['name']));
      }
    } catch (\Exception $e) {
      $this->addError("Node '$localPath': " . $e->getMessage());
    }
  }

  public function leaveNode($path, $node) {
    $localPath = $this->getLocalPath($path);
    try {
      if (isset($this->collectPaths[$localPath])) {
        if ($localPath == '.') {
          $curr = $this->currPath[0];
        } else {
          $curr = array_pop($this->currPath);
        }
        foreach ($this->collectPaths[$localPath] as $type) {
          if ($type == '@@value') {
            $curr->setValue(isset($node['value']) ? $node['value'] : null);
          } else {
            $val = isset($node['attributes'][$type]) ? $node['attributes'][$type] : null;
            $curr->setAttribute($type, $val);
          }
        }
      }
    } catch (\Exception $e) {
      $this->addError("Node '$localPath': " . $e->getMessage());
    }
  }

  private function getLocalPath($path) {
    $cnt = count($path);
    $localPath = '.';
    for ($i=$this->depth+1; $i<$cnt; $i++) {
      $localPath .= ($localPath ? '/' : '') . $path[$i]['name'];
    }
    return $localPath;
  }

  private function ensureParents($path) {
    $localPath = '';
    foreach (explode('/', $path) as $l) {
      $localPath .= ($localPath ? '/' : '') . $l;
      if (! isset($this->collectPaths[$localPath])) {
        $this->collectPaths[$localPath] = array();
      }
    }
  }

  private function registerCollectPath($path, $attribute=null) {
    $this->ensureParents($path);
    if ($attribute) {
      $this->collectPaths[$path][] = $attribute;
    } else {
      $this->collectPaths[$path][] = '@@value';
    }
  }

  protected function setupDynamicParameter($param, $def, $paramDef) {
    $type = $def['type'];
    switch ($type) {
      case 'node':
        $path = $def['path'];
        $this->registerPath($this->parameterPaths, $path, $param, $paramDef, $this->wrap($def, new ExtractXmlNodeValue()));
        $this->registerCollectPath($path);
        break;
      case 'attribute':
        $path = $def['path'];
        $attribute = $def['attribute'];
        $this->registerPath($this->parameterPaths, $path, $param, $paramDef, $this->wrap($def, new ExtractXmlAttribute($attribute)));
        $this->registerCollectPath($path, $attribute);
        break;
      default:
        throw new \Exception("Invalid parameter type definition: $type");
    }
  }

  private function wrap($instructions, XmlImportExtractor $extractor, $field=null) {
    $transform = isset($instructions['transform']) ? $instructions['transform'] : array();
    if ($transform || $field) {
      return new ExtractAndTransform($extractor, $this->getTransformation($transform, $field));
    }
    return $extractor;
  }

  protected function getDynamicParameterValues() {
    if (empty($this->parameterPaths)) {
      return array();
    }
    $ret = $this->parameterValues;
    $node = $this->getNode();
    foreach ($this->parameterPaths as $path => $paths) {
      $p = explode('/', $path);
      foreach ($paths as $param => $extractor) {
        $ret[$param] = $node->extractAll($p, $extractor);
      }
    }
    return $ret;
  }

  protected function setupField($field, $def, $fieldDef) {
    $this->setupFieldExt($field, $def, $fieldDef, $this->fieldsPaths);
  }

  protected function setupFieldExt($field, $def, $fieldDef, &$collect) {
    $type = Utils::extract('type', $def);
    switch ($type) {
      case 'node':
        $path = $def['path'];
        $this->registerPath($collect, $path, $field, $fieldDef, $this->wrap($def, new ExtractXmlNodeValue(), $fieldDef));
        $this->registerCollectPath($path);
        break;
      case 'attribute':
        $path = $def['path'];
        $attribute = $def['attribute'];
        $this->registerPath($collect, $path, $field, $fieldDef, $this->wrap($def, new ExtractXmlAttribute($attribute), $fieldDef));
        $this->registerCollectPath($path, $attribute);
        break;
      default:
        throw new \Exception("Invalid field type definition: $type");
    }
  }

  protected function setupRecordMatching($condition) {
    $inputs = Utils::extract('inputs', $condition);
    foreach ($inputs as $id => $def) {
      $type = Utils::extract('type', $def);
      switch ($type) {
        case 'node':
          $path = Utils::extract('path', $def);
          $this->match[$id] = array(
            'path' => explode('/', $path),
            'extractor' => $this->wrap($def, new ExtractXmlNodeValue())
          );
          $this->registerCollectPath($path);
          break;
        case 'attribute':
          $path = Utils::extract('path', $def);
          $attribute = Utils::extract('attribute', $def);
          $this->match[$id] = array(
            'path' => explode('/', $path),
            'extractor' => $this->wrap($def, new ExtractXmlAttribute($attribute))
          );
          $this->registerCollectPath($path, $attribute);
          break;
        default:
          throw new \Exception("Invalid match type definition: $type");
      }
    }
  }

  protected function setupRelation($key, $def, $link, $collection) {
    $source = Utils::extract('source', $def);
    $type = Utils::extract('type', $source);
    $this->relations[$key] = array(
      'type' => $def['type']
    );
    $root = "";
    $repl = "";
    switch ($type) {
      case 'node':
        $path = Utils::extract('path', $source);
        $split = explode('/', $path);
        $root = $path;
        $this->relations[$key]['path'] = $split;
        $repl = $split[count($split)-1];
        $this->registerCollectPath($path);
        break;
      default:
        throw new \Exception("Invalid relation type definition: $type");
    }
    if ($def['type'] === 'dependent') {
      $this->relations[$key]['constants'] = array();
      $this->relations[$key]['fields'] = array();
      $fields = Utils::extract('fields', $def);
      foreach ($fields as $field => $d) {
        $type = $d['type'];
        if ($link->hasExtraField($field)) {
          $fieldDef = $link->getExtraField($field);
        } else {
          $fieldDef = $collection->getField($field);
        }
        if ($d['type'] === 'constant') {
          $id = $fieldDef->getId();
          $this->relations[$key]['constants'][$id] = array(
            'parameters' => $fieldDef->getParameters(),
            'value' => isset($d['value']) ? $d['value'] : null
          );
        } else {
          $this->setupFieldExt($field, $d, $fieldDef, $this->relations[$key]['fields']);
        }
      }
      $keys = array_keys($this->relations[$key]['fields']);
      foreach ($keys as $k) {
        $n = str_replace($root, $repl, $k);
        $v = $this->relations[$key]['fields'][$k];
        unset($this->relations[$key]['fields'][$k]);
        $this->relations[$key]['fields'][$n] = $v;
      }
    } else {
      $this->relations[$key]['foreignKeyMatcher'] = new ForeignKeyMatcher($this->getFactory(), $this->getContext(null), $link, $def);
      $condition = Utils::extract('condition', $def);
      $inputs = Utils::extract('inputs', $condition);
      $this->relations[$key]['inputs'] = array();
      foreach ($inputs as $id => $source) {
        $type = Utils::extract('type', $source);
        switch ($type) {
          case 'node':
            $path = Utils::extract('path', $source);
            $n = str_replace($root, '.', $path);
            $this->relations[$key]['inputs'][$id] = array(
              'path' => explode('/', $n),
              'extractor' =>  new ExtractXmlNodeValue()
            );
            $this->registerCollectPath($path);
            break;
          case 'attribute':
            $path = Utils::extract('path', $source);
            $attribute = Utils::extract('attribute', $source);
            $this->registerCollectPath($path, $attribute);
            $n = str_replace($root, '.', $path);
            $this->relations[$key]['inputs'][$id] = array(
              'path' => explode('/', $n),
              'extractor' => new ExtractXmlAttribute($attribute)
            );
            break;
          default:
            throw new \Exception("Invalid match type definition: $type");
        }
      }
    }
  }

  protected function getRecordMatchingConditionInputValues() {
    if ($this->match) {
      $ret = array();
      $rootNode = $this->getNode();
      foreach ($this->match as $key => $pair) {
        $extractor = $pair['extractor'];
        $path = $pair['path'];
        $values = $rootNode->extractAll($path, $extractor);
        $value = count($values) >= 1 ? $values[0] : null;
        $ret[$key] = $value;
      }
      return $ret;
    }
    return null;
  }

  protected function getFieldValues($params) {
    $node = $this->getNode();
    $fields = array();
    $node->traverse($fields, $params, $this->parameterPaths, $this->fieldsPaths);
    foreach ($this->fieldsPaths as $path => $variants) {
      foreach ($variants as $alias => $arr) {
        if (! isset($fields[$alias])) {
          $field = $arr['def'];
          $extractor = $arr['extractor'];
          $value = $this->emptyNode->extract($extractor, "failed to process value from xml path '$path'");
          $this->emptyNode->importField($fields, $alias, $field, $value, $params);
        }
      }
    }
    return $fields;
  }

  protected function getRelationValues($key, $params, $collection) {
    $node = $this->getNode();
    $rel = $this->relations[$key];
    $nodes = $node->getNodes($rel['path']);
    $ret = array();
    if ($rel['type'] === 'dependent') {
      $fieldsPath = $rel['fields'];
      $constants = array();
      if ($rel['constants']) {
        foreach ($rel['constants'] as $id => $def) {
          $value = isset($def['value']) ? $def['value'] : $def['value'];
          $req = $def['parameters'];
          if ($req) {
            $input = array();
            foreach ($req as $param) {
              $input[$param] = Utils::extract($param, $params);
            }
            $constants[$id] = Utils::cartesian($input, $value);
          } else {
            $constants[$id] = $value;
          }
        }
      }
      foreach ($nodes as $n) {
        $fields = array();
        $n->traverse($fields, $params, array(), $fieldsPath);
        $ret[] = array_merge($constants, $fields);
      }
    } else {
      $inputs = $rel['inputs'];
      foreach ($nodes as $n) {
        $keys = array();
        foreach ($inputs as $id => $def) {
          $path = $def['path'];
          $extractor = $def['extractor'];
          $value = $n->extractAll($path, $extractor);
          $keys[$id] = count($value)>0 ? $value[0] : null;
        }
        if ($keys) {
          $fkeys = $rel['foreignKeyMatcher']->getForeignKeys($keys);
          foreach ($fkeys as $entry) {
            // TODO: add extra fields
            $ret[] = $entry;
          }
        }
      }
    }
    return $ret;
  }

  private function registerPath(&$paths, $path, $key, $def, $extractor) {
    if (! isset($paths[$path])) {
      $paths[$path] = array();
    }
    $paths[$path][$key] = array(
      'def' => $def,
      'extractor' => $extractor
    );
  }

  private function getNode() {
    return $this->currPath[0];
  }

}

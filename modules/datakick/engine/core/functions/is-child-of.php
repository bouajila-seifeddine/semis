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

class IsChildOfFunction extends Func {
    public function __construct($factory) {
        parent::__construct('isChildOf', 'boolean', array(
            'names' => array('collection', 'id', 'parentId'),
            'types' => array('string', 'number', 'number')
        ), true);
        $this->factory = $factory;
        $this->count = 1;
    }

    public function getType($parameterTypes, $parameters, $dictionary, $query, Context $context) {
        $colId = $this->getCollection($parameters[0], $query);
        $this->getHierarchyDefinition($colId);
        return 'boolean';
    }

    private function getCollection($objectAlias, $query) {
        if (gettype($objectAlias) != 'string') {
            throw new \Exception("Collection parameter must be literal: $objectAlias");
        }
        return $query->getCollectionByAlias($objectAlias);
    }

    private function getHierarchyDefinition($colId) {
        $col = $this->factory->getDictionary()->getCollection($colId);
        if (! isset($col['hierarchy'])) {
            throw new \Exception("Collection {$colId} is not hierarchical");
        }
        return $col['hierarchy'];
    }

    public function evaluate($args, $argsTypes, Context $context) {
        throw new \Exception("Use Extractor");
    }

    public function getExtractor($childExtractors, $childTypes, Context $context, $query, $factory) {
        $objectAlias = $childExtractors[0]->getValue(null);
        $colId = $this->getCollection($objectAlias, $query);
        $hierarchy = $this->getHierarchyDefinition($colId);
        return new IsChildOfExtractor($factory, $context, $colId, $hierarchy, $childExtractors);
    }

    public function jsEvaluate() {
        return 'return true;';
    }

    public function getSqlExpression($args, $type, $argTypes, $query, Context $context) {
        $alias = $args[0];
        $id = $args[1];
        $parentId = $args[2];

        $colId = $query->getCollectionByAlias($alias);
        $col = $this->factory->getDictionary()->getCollection($colId);
        if (! isset($col['hierarchy'])) {
            throw new \Exception("Collection {$colId} is not hierarchical, can't use isChildOf function");
        }
        $hierarchy = $col['hierarchy'];
        $idField = $col['key'][0];
        $cnt = $this->count++;
        $parentAlias = $alias.'_parent_'.$cnt;
        $query->exposeCollection($colId, $parentAlias, array(
            'joinType' => 'INNER',
            'conditions' => array(
                "<target:{$idField}> = {$parentId}"
            )
        ));
        $left = $query->exposeComponentField($alias, $hierarchy['left']);
        $right = $query->exposeComponentField($alias, $hierarchy['right']);
        $parentLeft = $query->exposeComponentField($parentAlias, $hierarchy['left']);
        $parentRight = $query->exposeComponentField($parentAlias, $hierarchy['right']);
        return "(({$left} >= {$parentLeft}) AND ({$right} <= {$parentRight}))";
    }
}


class IsChildOfExtractor extends Extractor {
    public function __construct($factory, Context $context, $collection, $hierarchy, $childExtractors) {
        $this->factory = $factory;
        $this->context = $context;
        $this->collection = $collection;
        $this->hierarchy = $hierarchy;
        $this->idExtractor = $childExtractors[1];
        $this->parentExtractor = $childExtractors[2];
    }

    private function getMap() {
        if (! $this->data) {
            $this->data = array();
            $query = $this->factory->getQuery();
            $query->exposeCollection($this->collection, 'hier');
            $idAlias = $query->exposeKeyFields('hier')[0];
            $leftAlias = $query->exposeField('hier', $this->hierarchy['left']);
            $rightAlias = $query->exposeField('hier', $this->hierarchy['right']);
            $result = $query->execute($this->factory, $this->context);
            while ($row = $result->fetch()) {
                $this->data[$row[$idAlias]] = array(
                    'left' => (int)$row[$leftAlias],
                    'right' => (int)$row[$rightAlias],
                );
            }
        }
        return $this->data;
    }

    public function getValue($resultset) {
        $map = $this->getMap();
        $id = $this->idExtractor->getValue($resultset);
        $parent = $this->parentExtractor->getValue($resultset);
        $left = $map[$id]['left'];
        $right = $map[$id]['right'];
        $parentLeft = $map[$parent]['left'];
        $parentRight = $map[$parent]['right'];
        return (($left >= $parentLeft) && ($right <= $parentRight));
    }
}

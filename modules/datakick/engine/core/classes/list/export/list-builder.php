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

class ListBuilder {
    private $cnt = 0;

    public function __construct($results, $count, $progress, $extractors) {
        $this->results = $results;
        $this->cnt = $count;
        $this->progress = $progress;
        $this->extractors = $extractors;
    }

    public function build($listOutput) {
        $this->progress->start('Build List');
        $ret = true;
        if ($this->results) {
            $cnt = 0;
            while ($row = $this->fetch()) {
                $this->outputRow($listOutput, $row);
                $cnt++;
                $this->progress->setProgress($this->cnt, $cnt);
            }
        } else {
            if ($this->extractors) {
                $this->outputRow($listOutput, null);
                $this->progress->setProgress(1, 1);
            } else {
                $ret = false;
            }
        }
        $listOutput->finish();
        $this->progress->end();
        return $ret;
    }

    private function outputRow($listOutput, $row) {
        $listOutput->addRow(array_map(function($extractor) use ($row) {
            return $extractor->getValue($row);
        }, $this->extractors));
    }

    public function fetch() {
        return $this->results->fetch();
    }

    public function getCount() {
      return $this->cnt;
    }
}

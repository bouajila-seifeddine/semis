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

class Currency {
    const MIXED = -1;
    private $currencyId;
    private $value;

    public function __construct($currencyId, $value) {
        $this->currencyId = $currencyId;
        $this->value = $value;
    }

    public function getCurrencyId() {
        return $this->currencyId;
    }

    public function getValue() {
        return $this->value;
    }

    public function resolveCurrencyId($other) {
        if ($this->currencyId === $other->getCurrencyId())
            return $this->currencyId;
        return self::MIXED;
    }

    public function __toString() {
      return "currency({$this->currencyId}, {$this->value})";
    }
}

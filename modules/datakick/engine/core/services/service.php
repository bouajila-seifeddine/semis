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

abstract class Service {
  const OUTPUT_HANDLED = 'OUTPUT_HANDLED';
  private $name;
  private $request;

  public function __construct($name) {
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }

  public function getParameter($name, $isRequired=true) {
    if (isset($this->request[$name])) {
      return $this->request[$name];
    }
    if ($isRequired)
      throw new UserError("Required parameter '$name' was not provided");
    return null;
  }

  public function getParameterWithDefault($name, $default) {
    if (isset($this->request[$name])) {
      return $this->request[$name];
    }
    return $default;
  }

  public function getDateParameter($name, $isRequired=true) {
    $val = $this->getParameter($name, $isRequired);
    if (! is_null($val)) {
      return Types::convertValue('datetime', $val);
    }
    return new \DateTime();
  }

  public function getArrayParameter($name, $isRequired=true) {
    $ret = $this->getParameter($name, $isRequired);
    if (! is_null($ret) && is_array($ret))
      return $ret;
    if ($isRequired) {
      throw new UserError("Parameter '$name' is not an array");
    }
    return array();
  }

  public function getIdParameter() {
    $id = $this->getParameter('id');
    if ($id === -1 || $id === '-1' || $id === 'new')
      return null;
    if (is_numeric($id))
      return (int)$id;
    throw new UserError("Invalid id value: $id");
  }

  // payload type: json, form-data
  public function payloadType() {
    return 'json';
  }

  public function handle($factory, $request) {
    $this->request = $request;
    $ret = $this->process($factory, $request);
    $this->request = null;
    return $ret;
  }

  public abstract function process($factory, $request);
}

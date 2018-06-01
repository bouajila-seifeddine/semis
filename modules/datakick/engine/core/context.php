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

class Context {
  private $factory;
  private $parameterTypes = array();
  private $userParameterTypes = array();
  private $parameters = array();
  private $available = array();
  private $inputValues = array();
  const NOT_FOUND = '@@not/found@@';

  public function __construct($factory, $provider, $systemParameterTypes) {
    $this->factory = $factory;
    $this->provider = $provider;
    $this->parameterTypes = $systemParameterTypes;
    foreach ($systemParameterTypes as $key => $def) {
      if (isset($def['derived']) || (isset($def['provided']) && $def['provided']) || isset($def['useDefault'])) {
        $this->available[$key] = $def;
      }
    }
    $this->setParameter('timestamp', new \DateTime());
  }

  public function getInputValue($key, $type) {
    if (array_key_exists($key, $this->inputValues)) {
      $input = $this->inputValues[$key];
      $value = $input['value'];
      if ($input['type'] !== $type) {
        if ($input['type'] === 'string') {
          return Types::convertValue('string', $value, false);
        } else {
          throw new UserError("Can't convert input value '$key' from {$input['type']} to $type");
        }
      } else {
        return $value;
      }
    }
    throw new UserError("Input value '$key' not provided");
  }

  public function setInputValue($key, $value, $type) {
    $this->inputValues[$key] = array(
      'value' => $value,
      'type' => $type
    );
  }

  public function setUserParameters(array $userParameterTypes) {
    $this->parameterTypes = array_merge($userParameterTypes, $this->parameterTypes);
  }

  public function setValue($key, $value) {
    if (isset($this->parameterTypes[$key])) {
      $this->addParameter($key, $value, $this->parameterTypes[$key], 'set');
    } else {
      throw new UserError("Parameter '$key' is not defined");
    }
  }

  public function setValues(array $parameters) {
    foreach ($parameters as $key => $value) {
      $this->setValue($key, $value);
    }
  }

  public function hasParameter($key) {
    return $this->doGetParameter($key, self::NOT_FOUND) !== self::NOT_FOUND;
  }

  public function getType($key) {
    $parameter = $this->getParameter($key);
    return $parameter['type'];
  }

  public function getValue($key) {
    $parameter = $this->getParameter($key);
    return $parameter['value'];
  }

  public function getParameter($key) {
    $ret = $this->doGetParameter($key);
    if ($ret === self::NOT_FOUND)
      throw new UserError("Parameter '$key' not provided");
    return $ret;
  }

  public function doGetParameter($key) {
    if (isset($this->parameters[$key])) {
      return $this->parameters[$key];
    }
    if (isset($this->available[$key])) {
      $def = $this->available[$key];
      $provideMethod = '';
      if (isset($def['provided']) && $def['provided']) {
        $value = $this->provider->getParameter($key, $def);
        $provideMethod = 'provided';
      } else if (isset($def['derived'])) {
        $dependencies = array();
        foreach($def['derived'] as $dep) {
          array_push($dependencies, $this->getValue($dep));
        }
        $value = $this->provider->deriveParameter($key, $def, $dependencies);
        $provideMethod = 'derived';
      } else if (isset($def['useDefault'])) {
        $value = $def['default'];
        $provideMethod = 'default';
      }
      return $this->addParameter($key, $value, $def, $provideMethod);
    }
    return self::NOT_FOUND;
  }

  public function getProvideMethod($key) {
    return $this->getParameter($key)['provideMethod'];
  }

  public function setParameter($key, $value) {
    if (isset($this->parameters[$key])) {
      $this->parameters[$key]['value'] = $value;
    } else if (isset($this->available[$key])) {
      $def = $this->available[$key];
      $this->addParameter($key, $value, $def, 'set');
    } else {
      throw new UserError("Parameter '$key' not found");
    }
  }

  private function addParameter($key, $value, $def, $provideMethod) {

    if ($value != '$all') {
      $value = $this->convertValue($key, $value, $def);
      if (isset($def['values']) && !isset($def['values'][$value])) {
        throw new UserError("Parameter '$label' has invalid value '$value'");
      }
    }

    $label = $def['description'];

    $parameter = array(
      'key' => $key,
      'type' => $def['type'],
      'value' => $value,
      'provideMethod' => $provideMethod
    );
    $this->parameters[$key] = $parameter;
    return $parameter;
  }

  private function convertValue($key, $value, $def) {
    try {
      return Types::convertValue($def['type'], $value, false);
    } catch (UserError $e) {
      $this->failure($key, $e->getMessage());
    }
  }

  private function failure($key, $msg) {
    throw new UserError("Failed to resolve parameter '$key': $msg");
  }

}

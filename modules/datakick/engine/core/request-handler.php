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

class RequestHandler {
  private $factory;

  public function __construct($factory) {
    $this->factory = $factory;
  }

  public function handleRequest() {
    $taskHandlesResponse = false;
    try {
      $this->setDebugMode();

      if ($this->factory->trialEnded()) {
        throw new UserError("Datakick trial period ended");
      }

      $endpointName = $this->getParameter("endpoint");
      $endpoints = $this->factory->getRecord("endpoints");
      $endpoint = $endpoints->loadBy(
        array('endpoint' => $endpointName, 'active' => 1),
        array('id', 'typeId', 'type', 'recordType', 'recordId', 'name', 'userId'),
        array('parameters' => array('name', 'param', 'value'))
      );


      $userId = (int)$endpoint['userId'];
      $this->factory->substituteUser($userId);

      $context = $this->factory->getContext('endpoint', $endpoint['id']);
      $taskDef = array(
        'taskType' => $endpoint['typeId'],
        'recordType' => $endpoint['recordType'],
        'recordId' => $endpoint['recordId']
      );
      $typeName = $endpoint['type'];
      $name = $endpoint['name'];
      $parameters = array();
      foreach($endpoint['parameters'] as $par) {
        $parameters[$par['name']] = $par;
      }

      $task = $this->factory->getTasks()->get($taskDef);

      $parameterValues = $this->getParameters($parameters, $task->getRequiredParameters());
      $context->setUserParameters($task->getUserParameters());
      $context->setValues($parameterValues);

      $progress = new Progress(true, $task);
      $handles = $task->handlesResponse();
      if (! $handles)
        ob_start();

      $task->execute($context, $progress);
      if (!$handles || !$task->handledResponse()) {
        ob_end_clean();
        $status = $task->getStatus(false, true);
        if ($status['status'] === 'failed') {
          $this->handleError($status, true);
        } else {
          $ret = $this->factory->debugMode() ? $status : array('status' => $status['status']);
          print_r(json_encode($ret, JSON_PRETTY_PRINT));
        }
      }
    }
    catch (UserError $e) {
      $this->handleError($e, true);
    }
    catch (\Exception $e) {
      $this->handleError($e, false);
    }
  }

  public function handleAjax() {
    header('Access-Control-Allow-Origin: *');
    header("Content-Type: text/json");

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
      die();
    }

    $this->setDebugMode();
    $factory = $this->factory;

    $out = array();
    try {
      $service = $this->getParameter('service');
      $services = $factory->getServices();
      $response = $services->handle($service, $this->getPayload($services->payloadType($service)));
      if ($response === Service::OUTPUT_HANDLED) {
        die();
      }
      $out = array(
        'error' => false,
        'data' => $response
      );
      if ($factory->debugMode()) {
        $out['queries'] = $factory->getConnection()->getQueries();
      }
    } catch (UserError $e) {
      $out = array(
        'error' => $e->getMessage(),
        'userError' => true
      );
    } catch (\Exception $e) {
      $out = array(
        'error' => $e->__toString(),
        'userError' => false
      );
    }
    print json_encode($out);
    die();
  }

  private function setDebugMode() {
    $debug = boolval($this->getParameterWithDefault('debug-mode', false));
    $this->factory->setDebugMode($debug);
  }

  private function getPayload($type='json') {
    if ($type == 'form-data') {
      return $_POST;
    }
    $payload = null;
    $isPost = $_SERVER['REQUEST_METHOD'] == 'POST';
    if ($isPost) {
      $putresource = fopen("php://input", "r");
      while ($putData = fread($putresource, 4096)) {
        $payload .= $putData;
      }
      fclose($putresource);

      if (! $payload) {
        throw new UserError("POST request does not contains payload");
      }

      $decoded = base64_decode($payload, false);
      if (! $decoded) {
        throw new UserError("Failed to decode base64 payload request");
      }

      $arr = json_decode($decoded, true);
      if (! $arr) {
        throw new UserError("POST request does not contains valid JSON");
      }

      return $arr;
    }
    return array();
  }

  private function getParameters($definitions, $required) {
    $ret = array();
    foreach ($required as $key) {
      if (isset($definitions[$key])) {
        $def = $definitions[$key];
        $value = $def['value'];
        $param = $def['param'];
        if (!$this->isValueSet($value) && !$this->isValueSet($param)) {
          throw new UserError("Invalid entry in endpoint_parameter: either value or param must be set");
        }
        if ($this->isValueSet($param) && $this->hasParameter($param)) {
          $value = $this->getParameter($param);
        }
        if (!$this->isValueSet($value)) {
          throw new UserError("Missing required URL parameter `$param`");
        }
        $ret[$key] = $value;
      }
    }
    return $ret;
  }

  private function getMessage($e, $userError) {
    if (! $userError) {
      return "There has been an error";
    }
    if (is_array($e)) {
      return htmlspecialchars($e['error']);
    }
    return htmlspecialchars($e->getMessage());
  }

  private function getExtra($e) {
    if (is_array($e)) {
      if (isset($e['fullError'])) {
        return htmlspecialchars($e['fullError']);
      }
      return null;
    }
    return htmlspecialchars($e->__toString());
  }

  private function handleError($e, $userError=false) {
    $message = $this->getMessage($e, $userError);

    $extra = null;
    $queries = null;
    if ($this->factory->debugMode()) {
      $extra = $this->getExtra($e);
      $queries = array_map(function($q) {
        return $q['sql'];
      }, $this->factory->getConnection()->getQueries());
    }

    header("Content-Type: text/html");
    echo "<h1>Error</h1><pre>$message</pre>";
    if ($extra) {
      echo "<br><hr><br>";
      echo "<pre>$extra</pre>";
    }
    if ($queries) {
      echo "<br><hr><br>";
      echo "<h2>Queries:</h2>";
      foreach($queries as $i=>$q) {
        echo "<h3>#" . ($i+1) . "</h3>";
        echo "<pre>" . htmlspecialchars($q) . "</pre>";
      }
    }
  }

  private function isValueSet($val) {
    return ! is_null($val) && $val !== '';
  }

  private function getParameter($name) {
    $param = $this->getRawParameter($name);
    if (get_magic_quotes_gpc()) {
      $param = stripslashes($param);
    }
    return $param;
  }

  private function getParameterWithDefault($name, $default) {
    if ($this->hasParameter($name)) {
      return $this->getParameter($name);
    }
    return $default;
  }

  private function hasParameter($name) {
    return isset($_GET[$name]) || isset($_POST[$name]);
  }

  private function getRawParameter($name) {
    if (isset($_GET[$name]))
      return $_GET[$name];
    if (isset($_POST[$name]))
      return $_POST[$name];
    throw new UserError("Missing required URL parameter `$name`");
  }
}

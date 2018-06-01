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

class EmailPlace extends Place {
  private $email;

  public function __construct($id, $name, $config, $factory) {
    parent::__construct('email', $id, $name, $config);
    $body = $this->getConfig('body');
    $subject = $this->getConfig('subject');
    $this->email = $factory->createEmail();
    $this->email->setSubject($subject);
    $this->email->setBody($body, 'text');
    $this->email->setBody("<h2>$subject</h2><br><p>$body</p><br>", 'html');
    $this->email->addRecipient($this->getConfig('to'));

    $templateVars = array();
    foreach ($config as $key => $value) {
      if ($key != 'subject' && $key != 'body' && $key != 'to') {
        $templateVars['{' . Utils::decamelize($key) . '}' ] = $value;
      }
    }
    $this->email->addTemplateVars($templateVars);
  }

  public function getPermissionError($path) {
    return null;
  }

  protected function doSaveFile($file, array $path) {
    $filename = count($path) > 0 ? end($path) : $file;
    $content = file_get_contents($file);
    $mimeType = mime_content_type($file);
    $this->email->attach($filename, $mimeType, $content);
    return true;
  }

  protected function doDeleteFile(array $path) {
    $this->setError("Delete file not supported");
  }

  public function finalize() {
    return $this->email->send();
  }

}

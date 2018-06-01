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

class Email {
  private $service;
  private $fromName;
  private $fromEmail;
  private $recipients = array();
  private $bcc = array();
  private $subject;
  private $body = array();
  private $attachements = array();
  private $templateVars = array();

  public function __construct(EmailService $service, $fromEmail, $fromName=null) {
    $this->service = $service;
    $this->fromName = $fromName;
    $this->fromEmail = $fromEmail;
  }

  public function addRecipient($email, $name=null) {
    $this->recipients[] = array(
      'name' => $name,
      'email' => $email
    );
  }

  public function addBCC($email, $name=null) {
    $this->bcc[] = array(
      'name' => $name,
      'email' => $email
    );
  }

  public function setSubject($subject) {
    $this->subject = $subject;
  }

  public function setBody($body, $type='text') {
    $this->body[$type] = $body;
  }

  public function hasHtml() {
    return isset($this->body['html']);
  }

  public function hasText() {
    return isset($this->body['text']);
  }

  public function attach($name, $mime, $content) {
    $this->attachements[] = array(
      'name' => $name,
      'mime' => $mime,
      'content' => $content,
    );
  }

  public function addTemplateVars($vars) {
    $this->templateVars = array_merge($this->templateVars, $vars);
  }

  public function getTemplateVars() {
    return $this->templateVars;
  }

  public function getFromName() {
    return $this->fromName;
  }

  public function getFromEmail() {
    return $this->fromEmail;
  }

  public function getSubject() {
    return $this->subject;
  }

  public function getBody($type) {
    if (! isset($this->body[$type]))
      throw new UserError("Email body type $type not set");
    return $this->body[$type];
  }

  public function getRecipients() {
    return $this->recipients;
  }

  public function getBcc() {
    return $this->recipients;
  }

  public function hasAttachements() {
    return count($this->attachements) > 0;
  }

  public function getAttachements() {
    return $this->attachements;
  }

  public function send() {
    $this->service->send($this);
  }
}

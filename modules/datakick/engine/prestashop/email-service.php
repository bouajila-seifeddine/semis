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

class PrestashopEmailService implements EmailService {
  private static $languageId;
  private $emails = array();

  public function __construct($languageId) {
    self::resolveLanguage($languageId);
  }

  public function send(Email $email) {
    $recipients = $email->getRecipients();
    $to = array_column($recipients, 'email');
    $toNames = array_column($recipients, 'name');
    $bcc = $email->getBcc();
    if ($bcc) {
      $bcc = implode(', ', array_column($bcc, 'email'));
    }
    $this->pushEmail($email);

    $idShop = \Context::getContext()->shop->id;
    $ret = null;
    $conf = null;
    $err = null;
    try {
      // temporarily change configuration type
      $conf = \Configuration::get('PS_MAIL_TYPE', null, null, $idShop);
      $mailType = (
        $email->hasText() && $email->hasHtml() ? \Mail::TYPE_BOTH :
        $email->hasText() ? \Mail::TYPE_TEXT :
        \Mail::TYPE_HTML
      );
      \Configuration::set('PS_MAIL_TYPE', $mailType, null, $idShop);

      $ret = \Mail::Send(
        self::$languageId,          // langId
        'datakick',                 // template name
        $email->getSubject(),       // subject,
        $email->getTemplateVars(),  // template variables
        $to,                        // to addresses
        $toNames,
        $email->getFromEmail(),
        $email->getFromName(),
        $email->getAttachements(),
        null,                       // mode_smtp
        _PS_MAIL_DIR_,              // template_path
        false,                      // die
        null,                       // id_shop,
        $bcc,                       // bcc
        null                        // reply-to
      );
    } catch (\Exception $e) {
      $err = $e;
    }

    if ($conf) {
      \Configuration::set('PS_MAIL_TYPE', $conf, null, $idShop);
    }

    $this->popEmail();

    if ($err)
      throw $err;

    return $ret;
  }

  private function pushEmail($email) {
    array_push($this->emails, $email);
  }

  private function popEmail() {
    return array_pop($this->emails);
  }

  public function getEmail() {
    return end((array_values($this->emails)));
  }

  private static function resolveLanguage($langId) {
    if (is_null(self::$languageId)) {
      self::$languageId = self::getLanguage($langId);
    }
  }


  private static function getLanguage($langId) {
    // keep this first - we need to cache languages
    $langs = \Language::getLanguages(false, false, true);

    if (self::hasTemplate($langId)) {
      return $langId;
    }

    foreach ($langs as $lang) {
      if (self::hasTemplate($lang))
        return $lang;
    }

    if (self::createTemplate($langId)) {
      return $langId;
    }

    throw new UserError("Can't send email - template not found");
  }

  private static function getTemplateDir($langId) {
    $iso = \Language::getIsoById((int)$langId);
    return _PS_MAIL_DIR_ . $iso . DIRECTORY_SEPARATOR;
  }

  private static function hasTemplate($langId) {
    $path = self::getTemplateDir($langId);
    return (file_exists($path . 'datakick.txt') && file_exists($path . 'datakick.html'));
  }

  private static function createTemplate($langId) {
    $path = self::getTemplateDir($langId);
    if (is_writable($path)) {
      touch($path . 'datakick.txt');
      touch($path . 'datakick.html');
      return true;
    }
    return false;
  }
}

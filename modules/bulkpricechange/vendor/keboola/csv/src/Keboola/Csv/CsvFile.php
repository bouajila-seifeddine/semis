<?php
 /**
  * Prestashop Modules & Themen End User License Agreement  *   * This End User License Agreement ("EULA") is a legal agreement between you and Presta-Apps ltd.( herein referred to as "we" or "us" ) with regard to Prestashop Modules & Themen (herein referred to as "Software Product" or "Software"). By installing or using the Software Product you agree to be bound by the terms of this EULA.  *   *    1. Eligible Licensees. This Software is available for license solely to Software Owners, with no right of duplication or further distribution, licensing, or sub-licensing. A Software Owner is someone who legally obtained a copy of the Software Product via Prestashop Store.  *    2. License Grant. We grant you a personal/one commercial, non-transferable and non-exclusive right to use the copy of the Software obtained via Prestashop Store. Modifying, translating, renting, copying, transferring or assigning all or part of the Software, or any rights granted hereunder, to any other persons and removing any proprietary notices, labels or marks from the Software is strictly prohibited. Furthermore, you hereby agree not to create derivative works based on the Software. You may not transfer this Software.  *    3. Copyright. The Software is licensed, not sold. You acknowledge that no title to the intellectual property in the Software is transferred to you. You further acknowledge that title and full ownership rights to the Software will remain the exclusive property of Presta-Apps Mobile, and you will not acquire any rights to the Software, except as expressly set forth above.  *    4. Reverse Engineering. You agree that you will not attempt, and if you are a corporation, you will use your best efforts to prevent your employees and contractors from attempting to reverse compile, modify, translate or disassemble the Software in whole or in part. Any failure to comply with the above or any other terms and conditions contained herein will result in the automatic termination of this license.  *    5. Disclaimer of Warranty. The Software is provided "AS IS" without warranty of any kind. We disclaim and make no express or implied warranties and specifically disclaim the warranties of merchantability, fitness for a particular purpose and non-infringement of third-party rights. The entire risk as to the quality and performance of the Software is with you. We do not warrant that the functions contained in the Software will meet your requirements or that the operation of the Software will be error-free.  *    6. Limitation of Liability. Our entire liability and your exclusive remedy under this EULA shall not exceed the price paid for the Software, if any. In no event shall we be liable to you for any consequential, special, incidental or indirect damages of any kind arising out of the use or inability to use the software.  *    7. Rental. You may not loan, rent, or lease the Software.  *    8. Updates and Upgrades. All updates and upgrades of the Software from a previously released version are governed by the terms and conditions of this EULA.  *    9. Support. Support for the Software Product is provided by Presta-Apps ltd. For product support, please send an email to support at info@iniweb.de  *   10. No Liability for Consequential Damages. In no event shall we be liable for any damages whatsoever (including, without limitation, incidental, direct, indirect special and consequential damages, damages for loss of business profits, business interruption, loss of business information, or other pecuniary loss) arising out of the use or inability to use the Software Product. Because some states/countries do not allow the exclusion or limitation of liability for consequential or incidental damages, the above limitation may not apply to you.  *   11. Indemnification by You. You agree to indemnify, hold harmless and defend us from and against any claims or lawsuits, including attorney's fees that arise or result from the use or distribution of the Software in violation of this Agreement.  *   *   @author    Presta-Apps Limited  *   @website   www.presta-apps.com  *   @contact   info@presta-apps.com  *   @copyright 2009-2015 Presta-Apps Ltd.  *   @license   Proprietary  * 
  */
 namespace Keboola\Csv; class CsvFile extends \SplFileInfo implements \Iterator { const DEFAULT_DELIMITER = ','; const DEFAULT_ENCLOSURE = '"'; protected $_delimiter; protected $_enclosure; protected $_escapedBy; protected $_filePointer; protected $_rowCounter = 0; protected $_currentRow; protected $_lineBreak; public function __construct($fileName, $delimiter = self::DEFAULT_DELIMITER, $enclosure = self::DEFAULT_ENCLOSURE, $escapedBy = "") { parent::__construct($fileName); $this->_escapedBy = $escapedBy; $this->_setDelimiter($delimiter); $this->_setEnclosure($enclosure); } protected function _setDelimiter($delimiter) { $this->_validateDelimiter($delimiter); $this->_delimiter = $delimiter; return $this; } protected function _validateDelimiter($delimiter) { if (strlen($delimiter) > 1) { throw new InvalidArgumentException( "Delimiter must be a single character. \"$delimiter\" received", Exception::INVALID_PARAM, null, 'invalidParam' ); } if (strlen($delimiter) == 0) { throw new InvalidArgumentException( "Delimiter cannot be empty.", Exception::INVALID_PARAM, null, 'invalidParam' ); } } public function getDelimiter() { return $this->_delimiter; } public function getEnclosure() { return $this->_enclosure; } public function getEscapedBy() { return $this->_escapedBy; } protected function _setEnclosure($enclosure) { $this->_validateEnclosure($enclosure); $this->_enclosure = $enclosure; return $this; } protected function _validateEnclosure($enclosure) { if (strlen($enclosure) > 1) { throw new InvalidArgumentException( "Enclosure must be a single character. \"$enclosure\" received", Exception::INVALID_PARAM, null, 'invalidParam' ); } } public function getColumnsCount() { return count($this->getHeader()); } public function getHeader() { $this->rewind(); $current = $this->current(); if (is_array($current)) { return $current; } return array(); } public function writeRow(array $row) { $str = $this->rowToStr($row); $ret = fwrite($this->_getFilePointer('w+'), $str); if (($ret === false) || (($ret === 0) && (strlen($str) > 0))) { throw new Exception( "Cannot open file $this", Exception::WRITE_ERROR, null, 'writeError' ); } } public function rowToStr(array $row) { $return = array(); foreach ($row as $column) { $return[] = $this->getEnclosure() . str_replace($this->getEnclosure(), str_repeat($this->getEnclosure(), 2), $column) . $this->getEnclosure(); } return implode($this->getDelimiter(), $return) . "\n"; } public function getLineBreak() { if (!$this->_lineBreak) { $this->_lineBreak = $this->_detectLineBreak(); } return $this->_lineBreak; } public function getLineBreakAsText() { return trim(json_encode($this->getLineBreak()), '"'); } public function validateLineBreak() { $lineBreak = $this->getLineBreak(); if (in_array($lineBreak, array("\r\n", "\n"))) { return $lineBreak; } throw new InvalidArgumentException( "Invalid line break. Please use unix \\n or win \\r\\n line breaks.", Exception::INVALID_PARAM, null, 'invalidParam' ); } protected function _detectLineBreak() { rewind($this->_getFilePointer()); $sample = fread($this->_getFilePointer(), 10000); rewind($this->_getFilePointer()); $possibleLineBreaks = array( "\r\n", "\r", "\n", ); $lineBreaksPositions = array(); foreach ($possibleLineBreaks as $lineBreak) { $position = strpos($sample, $lineBreak); if ($position === false) { continue; } $lineBreaksPositions[$lineBreak] = $position; } asort($lineBreaksPositions); reset($lineBreaksPositions); return empty($lineBreaksPositions) ? "\n" : key($lineBreaksPositions); } protected function _closeFile() { if (is_resource($this->_filePointer)) { fclose($this->_filePointer); } } public function __destruct() { $this->_closeFile(); } public function current() { return $this->_currentRow; } public function next() { $this->_currentRow = $this->_readLine(); $this->_rowCounter++; } public function key() { return $this->_rowCounter; } public function valid() { return $this->_currentRow !== false; } public function rewind() { rewind($this->_getFilePointer()); $this->_currentRow = $this->_readLine(); $this->_rowCounter = 0; } protected function _readLine() { $this->validateLineBreak(); $enclosure = !$this->getEnclosure() ? chr(0) : $this->getEnclosure(); $escapedBy = !$this->_escapedBy ? chr(0) : $this->_escapedBy; return fgetcsv($this->_getFilePointer(), null, $this->getDelimiter(), $enclosure, $escapedBy); } protected function _getFilePointer($mode = 'r') { if (!is_resource($this->_filePointer)) { $this->_openFile($mode); } return $this->_filePointer; } protected function _openFile($mode) { if ($mode == 'r' && !is_file($this->getPathname())) { throw new Exception( "Cannot open file $this", Exception::FILE_NOT_EXISTS, null, 'fileNotExists' ); } $this->_filePointer = fopen($this->getPathname(), $mode); if (!$this->_filePointer) { throw new Exception( "Cannot open file $this", Exception::FILE_NOT_EXISTS, null, 'fileNotExists' ); } } } 
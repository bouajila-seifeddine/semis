<?php
 /**
  * Prestashop Modules & Themen End User License Agreement
  */
 class Keboola_Sniffs_WhiteSpace_DisallowSpaceIndentSniff implements PHP_CodeSniffer_Sniff { public $supportedTokenizers = array( 'PHP', 'JS', 'CSS', ); public function register() { return array(T_WHITESPACE); } public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) { $tokens = $phpcsFile->getTokens(); $line = $tokens[$stackPtr]['line']; if ($stackPtr > 0 && $tokens[($stackPtr - 1)]['line'] === $line) { return; } if (strpos($tokens[$stackPtr]['content'], " ") !== false) { $error = 'Tabs must be used to indent lines; spaces are not allowed'; $phpcsFile->addError($error, $stackPtr, 'SpacesUsed'); } }} 
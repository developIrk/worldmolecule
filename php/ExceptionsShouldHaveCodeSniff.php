<?php

/**
 * Beauty_Sniffs_PHP_ExceptionsShouldHaveCode
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @author    Gleb An       <gleb@logics.net.au>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://www.gefest.com.au/license Gefest proprietary license
 * @link      http://svn.logics.net.au/foundation/XML
 */

require_once "CodeSniffer/PHP_CodeSniffer_Sniff.php";

/**
 * Beauty_Sniffs_PHP_ExceptionsShouldHaveCode
 *
 * All exceptions should have exception code in addition to exception message
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @author    Gleb An       <gleb@logics.net.au>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.0.0
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

class ExceptionsShouldHaveCodeSniff implements PHP_CodeSniffer_Sniff
    {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */

	public function register()
	    {
		return array(
			T_NEW,
			T_IF,
		       );
	    } //end register()


	/**
	 * Processes this test, when used continue
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int                  $stackPtr  The position of the current token in
	 *                                        the stack passed in $tokens.
	 *
	 * @return void
	 */

	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	    {
		$tokens = $phpcsFile->getTokens();
		if ($tokens[$stackPtr]["code"] === T_NEW)
		    {
			$this->_searchNotCorrectUsingException($phpcsFile, $stackPtr, $tokens);
		    }
		else if ($tokens[$stackPtr]["code"] === T_IF)
		    {
			$token_open_parenthesis = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 2), null, true);
			if ($tokens[$token_open_parenthesis]["code"] === T_OPEN_PARENTHESIS && $tokens[$token_open_parenthesis]["content"] === "(")
			    {
				$this->_searchNotCorrectUsingCondition($phpcsFile, $stackPtr, $tokens);
			    }
		    }
	    } //end process()


	/**
	 * Continues process() of search not correct using Exception
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int                  $stackPtr  The position of the current token int the stack passed in $tokens.
	 * @param array()              $tokens    The set tokens in the current file.
	 *
	 * @return void
	 * @untranslatable Exception
	 * @untranslatable Exceptions
	 */
	private function _searchNotCorrectUsingException(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $tokens)
	    {
		$token_Exception = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 2), null, true);
		if (($tokens[$token_Exception]["code"] === T_STRING) && ($tokens[$token_Exception]["content"] === "Exception"))
		    {
			$token_open_parenthesis = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 3), null, true);
			if ($tokens[$token_open_parenthesis]["code"] === T_OPEN_PARENTHESIS && $tokens[$token_open_parenthesis]["content"] === "(")
			    {
				$token_text = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 4), null, true);
				if ($tokens[$token_text]["content"] === "_")
				    {
					$this->_searchNotCorrectUsingTextInException($phpcsFile, $stackPtr, $tokens);
				    }
				else if ($tokens[$token_text]["code"] === T_CONSTANT_ENCAPSED_STRING)
				    {
					$token_close_parenthesis = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 5), null, true);
					if ($tokens[$token_close_parenthesis]["code"] === T_CLOSE_PARENTHESIS)
					    {
						$phpcsFile->addError(_("Exception constructor requires use of two paramters: message and code"), $stackPtr, "Exceptions");
					    }
				    } //end if
			    } //end if
		    } //end if
	    } //end _searchNotCorrectUsingException()


	/**
	 * Continues process() of search not correct using text in Exception
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int                  $stackPtr  The position of the current token int the stack passed in $tokens.
	 * @param array()              $tokens    The set tokens in the current file.
	 *
	 * @return void
	 * @untranslatable Exceptions
	 */
	private function _searchNotCorrectUsingTextInException(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $tokens)
	    {
		$token_open_parenthesis_text = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 5), null, true);
		$token_string = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 6), null, true);
		if ($tokens[$token_open_parenthesis_text]["code"] === T_OPEN_PARENTHESIS &&
		$tokens[$token_open_parenthesis_text]["content"] === "(" && $tokens[$token_string]["code"] === T_CONSTANT_ENCAPSED_STRING)
		    {
			$token_close_parenthesis_text = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 7), null, true);
			$token_close_parenthesis      = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 8), null, true);
			if ($tokens[$token_close_parenthesis_text]["code"] === T_CLOSE_PARENTHESIS &&
			$tokens[$token_close_parenthesis]["code"] === T_CLOSE_PARENTHESIS)
			    {
				$phpcsFile->addError(_("Exception constructor requires use of two paramters: message and code"), $stackPtr, "Exceptions");
			    }
		    }
	    } //end _searchNotCorrectUsingTextInException()


	/**
	 * Continues process() of search not correct using getMessage() function in condition
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int                  $stackPtr  The position of the current token int the stack passed in $tokens.
	 * @param array()              $tokens    The set tokens in the current file.
	 *
	 * @return void
	 * @untranslatable getMessage
	 */
	private function _searchNotCorrectUsingCondition(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $tokens)
	    {
		$token_var = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 3), null, true);
		$token_object_operator = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 4), null, true);
		if ($tokens[$token_var]["code"] === T_VARIABLE && $tokens[$token_object_operator]["code"] === T_OBJECT_OPERATOR)
		    {
			$token_function = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 5), null, true);
			$token_open_parenthesis = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 6), null, true);
			if ($tokens[$token_function]["code"] === T_STRING && $tokens[$token_function]["content"] === "getMessage"
			 && $tokens[$token_open_parenthesis]["code"] === T_OPEN_PARENTHESIS)
			    {
				$this->_searchCondition($phpcsFile, $stackPtr, $tokens);
			    }
		    } //end if
	    } //end _searchNotCorrectUsingCondition()


	/**
	 * Finds conditions
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int                  $stackPtr  The position of the current token int the stack passed in $tokens.
	 * @param array()              $tokens    The set tokens in the current file.
	 *
	 * @return void
	 * @untranslatable Exceptions
	 */
	private function _searchCondition(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $tokens)
	    {
		$token_close_parenthesis = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 7), null, true);
		$token_comparison        = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 8), null, true);
		if ($tokens[$token_close_parenthesis]["code"] === T_CLOSE_PARENTHESIS &&
		 ($tokens[$token_comparison]["code"] === T_IS_IDENTICAL || $tokens[$token_comparison]["code"] === T_IS_NOT_IDENTICAL))
		    {
			$phpcsFile->addError(_("Comparison of exception getMessage() is forbidden"), $stackPtr, "Exceptions");
		    }
	    } //end _searchCondition()


    } //end class

?>

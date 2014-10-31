<?php
/**
 * vim: set tabstop=4 softtabstop=4:
 */
/**
 * Enforces Yoda conditional statements , based upon Squiz code
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Matt Robinson
 */

/**
 * Squiz_Sniffs.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   John Godley <john@urbangiraffe.com>
 * @author   Greg Sherwood <gsherwood@squiz.net>
 * @author   Marc McIntyre <mmcintyre@squiz.net>
 */
class WordPress_Sniffs_PHP_YodaConditionsSniff implements PHP_CodeSniffer_Sniff
{

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register()
	{
		return array(
			T_IF,
			T_ELSEIF,
		);

	}//end register()


	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param PHP_CodeSniffer_File	$phpcsFile The file being scanned.
	 * @param int					$stackPtr  The position of the current token in the
	 *											stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		$openBracket  = $tokens[$stackPtr]['parenthesis_opener'];
		$closeBracket = $tokens[$stackPtr]['parenthesis_closer'];
		$string = '';
		for ($i = ($openBracket + 1); $i < $closeBracket; $i++) {
			$string .= $tokens[$i]['content'];
		}

        $regex = '#(\$\S+|\w+\(.*\))\s*(!==|===|!=|==)\s*(true|false|null|-?\s*[0-9]+\.?[0-9]*|[\'"][^\$]*[\'"])#si';
        preg_match_all($regex, $string, $matches);
        $matches_size = count($matches[0]);

        for ($i = 0; $i < $matches_size; $i++) {
            $error = sprintf(
                "Found «%s». Use Yoda condition, like «%s %s %s»",
                $matches[0][$i],
                $matches[3][$i],
                $matches[2][$i],
                $matches[1][$i]
            );
			$phpcsFile->addError($error, $stackPtr);
		}

	}//end process()


}//end class

?>

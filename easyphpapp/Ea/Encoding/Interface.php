<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Encoding
 * @subpackage  interface
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.2-20091223
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

/**
 * Interface to manage classes that need to deal with encoding.
 *
 */
interface Ea_Encoding_Interface
{

	/**
	 * Set the default internal encoding.
	 * 
	 * @param string $encoding
	 */
	static public function setDefaultInternalEncoding($encoding);
	
	/**
	 * Get the default internal encoding.
	 * 
	 * @return string
	 */
	static public function getDefaultInternalEncoding();
	
	/**
	 * Set the internal encoding for the instance.
	 * 
	 * @param string $encoding
	 */
	public function setInternalEncoding($encoding);
	
	/**
	 * Get the internal encoding for the instance.
	 * 
	 * @return string
	 */
	public function getInternalEncoding();
	
	/**
	 * Set the default target encoding.
	 * 
	 * @param string $encoding
	 */
	static public function setDefaultTargetEncoding($encoding);
	
	/**
	 * Get the default target encoding.
	 * 
	 * @return string
	 */
	static public function getDefaultTargetEncoding();
	
	/**
	 * Set the target encoding for the instance.
	 * 
	 * @param string $encoding
	 */
	public function setTargetEncoding($encoding);
	
	/**
	 * Get the target encoding for the instance.
	 * 
	 * @return string
	 */
	public function getTargetEncoding();	
	
	/**
	 * Encode from internal to specified encoding.
	 * 
	 * @param string $string
	 * @param string $encoding if null uses the target encoding
	 * @return string
	 */
	public function encode($string, $encoding=null);
	
	/**
	 * Decode from specified to internal encoding.
	 * 
	 * @param string $string
	 * @param string $encoding if null uses the target encoding
	 * @return string
	 */
	public function decode($string, $encoding=null);
	
	/**
	 * Encode from specified to utf8 encoding.
	 * 
	 * @param string $string
	 * @param string $encoding if null uses the internal encoding
	 * @return string
	 */
	public function utf8Encode($string, $encoding=null);
	
	/**
	 * Decode from utf8 to specified.
	 * 
	 * @param string $string
	 * @param string $encoding if null uses the internal encoding
	 * @return string
	 */
	public function utf8Decode($string, $encoding=null);	
	
}
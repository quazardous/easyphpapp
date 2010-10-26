<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Encoding
 * @subpackage  abstract
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.6-20101008
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Encoding/Interface.php';

/**
 * Basic abstract class that need to deal with encoding.
 *
 */
abstract class Ea_Encoding_Abstract implements Ea_Encoding_Interface
{

	static protected $_defaultInternalEncoding=null;
	
	/**
	 * Set the default internal encoding.
	 * 
	 * @param string $encoding
	 */
	static public function setDefaultInternalEncoding($encoding)
	{
		self::$_defaultInternalEncoding=strtoupper($encoding);
	}
	
	/**
	 * Get the default internal encoding.
	 * 
	 * @return string
	 */
	static public function getDefaultInternalEncoding()
	{
		return self::$_defaultInternalEncoding;
	}
	
	public function __construct()
	{
		if(!$this->_internalEncoding)
		{
			$this->_internalEncoding=self::$_defaultInternalEncoding;
		}
		if(!$this->_targetEncoding)
		{
			$this->_targetEncoding=self::$_defaultTargetEncoding;
		}		
	}
	
	protected $_internalEncoding=null;
	
	/**
	 * Set the internal encoding for the instance.
	 * 
	 * @param string $encoding
	 */
	public function setInternalEncoding($encoding)
	{
		$this->_internalEncoding=strtoupper($encoding);
	}
	
	/**
	 * Get the internal encoding for the instance.
	 * 
	 * @return string
	 */
	public function getInternalEncoding()
	{
		return $this->_internalEncoding;
	}
	
	static protected $_defaultTargetEncoding=null;
	
	/**
	 * Set the default target encoding.
	 * 
	 * @param string $encoding
	 */
	static public function setDefaultTargetEncoding($encoding)
	{
		self::$_defaultTargetEncoding=strtoupper($encoding);
	}
	
	/**
	 * Get the default target encoding.
	 * 
	 * @return string
	 */
	static public function getDefaultTargetEncoding()
	{
		return self::$_defaultTargetEncoding;
	}
	
	protected $_targetEncoding=null;
	
	/**
	 * Set the target encoding for the instance.
	 * 
	 * @param string $encoding
	 */
	public function setTargetEncoding($encoding)
	{
		$this->_targetEncoding=strtoupper($encoding);
	}
	
	/**
	 * Get the target encoding for the instance.
	 * 
	 * @return string
	 */
	public function getTargetEncoding()
	{
		return $this->_targetEncoding;
	}	
	
	/**
	 * Encode from internal to specified encoding.
	 * 
	 * @param string $string
	 * @param string $encoding if null uses the target encoding
	 * @return string
	 */
	public function encode($string, $encoding=null)
	{
		if($string===null) return $string;
		if(!$encoding) $encoding=$this->getTargetEncoding();
		if(!$encoding) return $string;
		$internal=$this->getInternalEncoding();
		if(strtoupper($encoding)==$internal) return $string;
		if($internal) return mb_convert_encoding($string, $encoding, $internal);
		return mb_convert_encoding($string, $encoding);
	}
		
	/**
	 * Decode from specified to internal encoding.
	 * 
	 * @param string $string
	 * @param string $encoding if null uses the target encoding
	 * @return string
	 */
	public function decode($string, $encoding=null)
	{
		if($string===null) return $string;
		if(!$encoding) $encoding=$this->getTargetEncoding();
		$internal=$this->getInternalEncoding();
		if(!$internal) return $string;
		if(strtoupper($encoding)==$internal) return $string;
		if($encoding) return mb_convert_encoding($string, $internal, $encoding);
		return mb_convert_encoding($string, $internal);
	}
	
	/**
	 * Encode from specified to utf8 encoding.
	 * 
	 * @param string $string
	 * @param string $encoding if null uses the internal encoding
	 * @return string
	 */
	public function utf8Encode($string, $encoding=null)
	{
		if($string===null) return $string;
		if(!$encoding) $encoding=$this->getInternalEncoding();
		if(strtoupper($encoding)=='UTF-8') return $string;
		if($encoding) return mb_convert_encoding($string, 'UTF-8', $encoding);
		return mb_convert_encoding($string, 'UTF-8');
	}
	
	/**
	 * Decode from utf8 to specified.
	 * 
	 * @param string $string
	 * @param string $encoding if null uses the internal encoding
	 * @return string
	 */
	public function utf8Decode($string, $encoding=null)
	{
		if($string===null) return $string;
		if(!$encoding) $encoding=$this->getInternalEncoding();
		if(!$encoding) return $string;
		if(strtoupper($encoding)=='UTF-8') return $string;
		return mb_convert_encoding($string, $encoding, 'UTF-8');
	}
	
}
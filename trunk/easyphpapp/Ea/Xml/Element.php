<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Tools
 * @subpackage  Xml
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.5.0-20101025
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Encoding/Interface.php';

/**
 * SimpleXMLElement extended class to manage encoding.
 * SimpleXMLElement always needs UTF-8. With Ea_Xml_Element you can specify an internal encoding and a loadtime encoding. 
 * Do not use new Ea_Xml_Element(...) but use load_... static methods.
 * Note that asXML() will always return UTF-8.
 * Because of the strange way SimpleXmlElement is implemented some magic function cannot be overload and it's impossible to add internal properties to the class.
 * So I had tos use a hack (static array+uniqid()) to store object properties...
 * 
 * @see Ea_Encoding_Abstract encoding method implementation are copy/pasted
 */
class Ea_Xml_Element extends SimpleXMLIterator implements Ea_Encoding_Interface
{
	/**
	 * Use to store the uniqid() in the xml top node.
	 * 
	 * @var string
	 */
	static protected $_propertyHackIdAttributeName='prophack:id';
	
	/**
	 * Add the correct namespace for the above attribute.
	 * 
	 * @param self $xml
	 */
	static protected function addPropertyHackNamespace(self $xml)
	{
		if(preg_match('/^([^:]+)[:]/', self::$_propertyHackIdAttributeName, $matches))
		{
			$xml['xmlns:'.$matches[1]]='http://code.google.com/p/easyphpapp/';
		}
	}
	
	/**
	  * Return the uniqid string for this object.
	  * SimpleXmlElement extend classes cannot have normal properties.
      *
	  *	@return string
	*/
	protected function getPropertyHackId()
	{
		$top=parent::xpath('/*');
		$top=$top[0];
		if (empty($top[self::$_propertyHackIdAttributeName]))
		{
			$top[self::$_propertyHackIdAttributeName] = uniqid();
		}
		return (string)$top[self::$_propertyHackIdAttributeName];
	}
	
	static protected $_properties=array();
	
	/**
		Set a property of a SimpleXMLIterator object.

		@param	$name	The name of the property.
		@param	$value The variable to attach to this element. If null it is not attached.
		@return	mixed	The variable attached with the specified name.
	*/
	public function setProperty($name, $value)
	{
		if (!isset(self::$_properties[$this->getPropertyHackId()]))
		{
			self::$_properties[$this->getPropertyHackId()] = array();
		}
		self::$_properties[$this->getPropertyHackId()][$name]=$value;
	}
	
	/**
     * Get a property of a SimpleXMLIterator object.
     *
     * @param	$name	The name of the property.
     * @return	mixed	The variable attached with the specified name.
	*/
	public function getProperty($name)
	{
		if (!isset(self::$_properties[$this->getPropertyHackId()][$name]))
		{
			return null;
		}
		return self::$_properties[$this->getPropertyHackId()][$name];
	}
	
	/**
	 * Static user encoding to UTF-8.
	 * 
	 * @param string $string
	 * @param string $encoding
	 * @return string
	 */
	public static function utf8_encode($string, $encoding=null)
	{
		if($string===null) return $string;
		if(!$encoding) $encoding=$this->getInternalEncoding();
		if(strtoupper($encoding)=='UTF-8') return $string;
		if($encoding) return mb_convert_encoding($string, 'UTF-8', $encoding);
		return mb_convert_encoding($string, 'UTF-8');
	}

	/**
	 * Static user unencoding from UTF-8.
	 * 
	 * @param string $string
	 * @param string $encoding
	 * @return string
	 */
	public static function utf8_unencode($string, $encoding=null)
	{
		if($string===null) return $string;
		if(!$encoding) $encoding=$this->getInternalEncoding();
		if(!$encoding) return $string;
		if(strtoupper($encoding)=='UTF-8') return $string;
		return mb_convert_encoding($string, $encoding, 'UTF-8');
	}

	/**
	 * Return unencoded value of node.
	 * 
	 * @return string
	 */
	public function toString()
	{
		$string=(string)$this;
		return $this->utf8Decode($string);
	}
	
	/**
	 * Class used for new nodes.
	 * 
	 * @var string
	 */
	protected static $_class = __CLASS__;
	
	public static function set_class($class)
	{
		self::$_class = $class;
	}

	
	/**
	 * Return a well-formed XML string based on SimpleXML element
	 * @link http://php.net/manual/en/function.simplexml-element-asXML.php
	 * @param filename string[optional]
	 * @return mixed
	 * 
	 * Not overload, always return UTF-8.
	 */
	//public function asXML ($filename = null) {}

	// public function saveXML () {}

	/**
	 * Runs XPath query on XML data
	 * @link http://php.net/manual/en/function.simplexml-element-xpath.php
	 * @param path string
	 * @return array an array of SimpleXMLElement objects or false in
	 */
	public function xpath($path)
	{
		return parent::xpath($this->utf8Encode($path));
	}
	
	/**
	 * Creates a prefix/ns context for the next XPath query
	 * @link http://php.net/manual/en/function.simplexml-element-registerXPathNamespace.php
	 * @param prefix string
	 * @param ns string
	 * @return bool 
	 */
	public function registerXPathNamespace($prefix, $ns)
	{
		return parent::registerXPathNamespace($this->utf8Encode($prefix), $this->utf8Encode($ns));
	}
	
	/**
	 * Identifies an element's attributes
	 * @link http://php.net/manual/en/function.simplexml-element-attributes.php
	 * @param ns string[optional]
	 * @param is_prefix bool[optional]
	 * @return Ea_Xml_Element 
	 */
	public function attributes($ns = null, $is_prefix = null)
	{
		return parent::attributes($this->utf8Encode($ns), $is_prefix);
	}

	/**
	 * Finds children of given node
	 * @link http://php.net/manual/en/function.simplexml-element-children.php
	 * @param ns string[optional]
	 * @param is_prefix bool[optional]
	 * @return Ea_Xml_Element 
	 */
	public function children($ns = null, $is_prefix = null)
	{
		return parent::children($this->utf8Encode($ns), $is_prefix);
	}

	static protected function recursive_utf8_unencode($string)
	{
		if(is_array($string))
		{
			$res=array();
			foreach($string as $key => $value)
			{
				$res[self::utf8_unencode($key)]=self::recursive_utf8_unencode($value);
			}
			return $res;
		}
		return self::utf8_unencode($string);
	}
	
	/**
	 * Returns namespaces used in document
	 * @return array 
	 */
	public function getNamespaces($recursive = null)
	{
		return self::recursive_utf8_unencode(parent::getNamespaces($recursive));
	}
	

	/**
	 * Returns namespaces declared in document
	 * @return array 
	 */
	public function getDocNamespaces($recursive = null)
	{
		return self::recursive_utf8_unencode(parent::getDocNamespaces($recursive));
	}
	
	/**
	 * Gets the name of the XML element
	 */
	public function getName()
	{
		return self::utf8_unencode(parent::getName());
	}
	
	/**
	 * Adds a child element to the XML node
	 * @return Ea_Xml_Element 
	 */
	public function addChild($name, $value = null, $namespace = null)
	{
		return parent::addChild( $this->utf8Encode($name), $this->utf8Encode($value), $this->utf8Encode($namespace));
	}
	
	/**
	 * Adds an attribute to the SimpleXML element
	 * @return void 
	 */
	public function addAttribute($name, $value = null, $namespace = null)
	{
		return parent::addAttribute($this->utf8Encode($name), $this->utf8Encode($value), $this->utf8Encode($namespace));
	}
	
	/**
	 * Create node from string.
	 * @return Ea_Xml_Element 
	 */
	public static function load_string($data, $options = null, string $ns = null, $is_prefix = false, $encoding=null )
	{
		if(!$encoding) $encoding=self::getDefaultXmlEncoding();
		$xml=simplexml_load_string(self::utf8_encode($data, $encoding), self::$_class, $options, self::utf8_encode($ns, $encoding), $is_prefix);
		if($xml)
		{
			self::addPropertyHackNamespace($xml);
			$xml->setXmlEncoding($encoding);
			$xml->setInternalEncoding(self::getDefaultInternalEncoding());
		}
		return $xml;
	}

	/**
	 * Create node from file.
	 * @return Ea_Xml_Element 
	 */
	public static function load_file($filename, $options = null, string $ns = null, $is_prefix = false, $encoding=null )
	{
		if(!$encoding) $encoding=self::getDefaultXmlEncoding();
		$xml=simplexml_load_file(self::utf8_encode($filename, $encoding), self::$_class, $options, self::utf8_encode($ns, $encoding), $is_prefix);
		if($xml)
		{
			self::addPropertyHackNamespace($xml);
			$xml->setXmlEncoding($encoding);
			$xml->setInternalEncoding(self::getDefaultInternalEncoding());
		}
		return $xml;
	}

	/**
	 * Create node from Dom.
	 * @return Ea_Xml_Element 
	 */
	public static function import_dom(DOMNode $node, $encoding=null)
	{
		if(!$encoding) $encoding=self::getDefaultXmlEncoding();
		$xml=simplexml_import_dom($node, self::$_class);
		if($xml)
		{
			self::addPropertyHackNamespace($xml);
			$xml->setXmlEncoding($encoding);
			$xml->setInternalEncoding(self::getDefaultInternalEncoding());
		}
		return $xml;
	}

	/**
	 * Some alias methods.
	 * 
	 */
	static public function setDefaultXmlEncoding($encoding)
	{
		self::setDefaultTargetEncoding($encoding);
	}
	
	static public function getDefaultXmlEncoding()
	{
		return self::getDefaultTargetEncoding();
	}
	
	public function setXmlEncoding($encoding)
	{
		$this->setTargetEncoding($encoding);
	}
	
	public function getXmlEncoding()
	{
		return $this->getTargetEncoding();
	}
	
	
	/*
	 * Encoding methods are copied from Ea_Encoding_Abstract except for the property hack.
	 * 
	 * */
	
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
	
	// protected $_internalEncoding=null; use setProperty() getProperty()
	
	/**
	 * Set the internal encoding for the instance.
	 * 
	 * @param string $encoding
	 * @see setProperty()
	 */
	public function setInternalEncoding($encoding)
	{
		$this->setProperty('internalEncoding', strtoupper($encoding));
	}
	
	/**
	 * Get the internal encoding for the instance.
	 * 
	 * @return string
	 * @see getProperty()
	 */
	public function getInternalEncoding()
	{
		return $this->getProperty('internalEncoding');
	}
	
	static protected $_defaultTargetEncoding='UTF-8';
	
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
	
	// protected $_targetEncoding=null; use setProperty() getProperty()
	
	/**
	 * Set the target encoding for the instance.
	 * 
	 * @param string $encoding
	 */
	public function setTargetEncoding($encoding)
	{
		$this->setProperty('targetEncoding', strtoupper($encoding));
	}
	
	/**
	 * Get the target encoding for the instance.
	 * 
	 * @return string
	 */
	public function getTargetEncoding()
	{
		return $this->getProperty('targetEncoding');
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
		return mb_convert_encoding($string, $encoding, $internal);
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
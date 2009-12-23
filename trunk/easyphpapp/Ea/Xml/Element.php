<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Tools
 * @subpackage  Xml
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.2-20091222
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

/**
 * SimpleXMLElement extended class to manage encoding.
 * SimpleXMLElement always needs UTF-8. With Ea_Xml_Element you can specify an input encoding. 
 * Do not use new Ea_Xml_Element(...) but use load_... static methods.
 * 
 */
class Ea_Xml_Element extends SimpleXMLElement
{
	protected static $_defaultEncoding=null;
	
	public static function setDefaultEncoding($encoding)
	{
		if(strtoupper($encoding)=='UTF-8') self::$_defaultEncoding=null;
		else self::$_defaultEncoding=$encoding;
	}
	
	public static function getDefaultEncoding()
	{
		if(self::$_defaultEncoding) return self::$_defaultEncoding;
		return 'UTF-8';
	}
	
	protected $_encoding=null;
	public function setEncoding($encoding)
	{
		if(strtoupper($encoding)=='UTF-8') $this->_encoding=null;
		else $this->_encoding=$encoding;
	}
	
	public function getEncoding()
	{
		if($this->_encoding) return $this->_encoding;
		return 'UTF-8';
	}
	
	/**
	 * User encoding to UTF-8.
	 * 
	 * @param $string
	 * @param $encoding
	 * @return unknown_type
	 */
	public static function utf8_encode($string, $encoding=null)
	{
		if($string===null)return null;
		if(!$encoding) $encoding=self::$_defaultEncoding;
		else
		{
			if(strtoupper($encoding)=='UTF-8') $encoding=null;
		}
		if($encoding) return mb_convert_encoding($string, 'UTF-8', $encoding);
		return $string;
	}

	public static function utf8_unencode($string, $encoding=null)
	{
		if($string===null)return null;
		if(!$encoding) $encoding=self::$_defaultEncoding;
		else
		{
			if(strtoupper($encoding)=='UTF-8') $encoding=null;
		}
		if($encoding) return mb_convert_encoding($string, $encoding, 'UTF-8');
		return $string;
	}

	public function utf8Encode($string)
	{
		if($string===null)return null;
		if($this->_encoding) return mb_convert_encoding($string, 'UTF-8', $this->_encoding);
		return $string;
	}

	public function utf8Unencode($string)
	{
		if($string===null)return null;
		if($this->_encoding) return mb_convert_encoding($string, $this->_encoding, 'UTF-8');
		return $string;
	}
	
	/**
	 * Return unencoded value of node.
	 * 
	 * @return string
	 */
	public function toString()
	{
		$string=(string)$this;
		return $this->utf8Unencode($string);
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
	public function xpath(string $path)
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
	public function addAttribute($name, $value, $namespace=null)
	{
		parent::addAttribute($this->utf8Encode($name), $this->utf8Encode($value), $this->utf8Encode($namespace));
	}
	
	/**
	 * Create node from string.
	 * @return Ea_Xml_Element 
	 */
	public static function load_string($data, $options = null, string $ns = null, $is_prefix = false, $encoding=null )
	{
		if(!$encoding) $encoding=self::$_defaultEncoding;
		$xml=simplexml_load_string(self::utf8_encode($data, $encoding), self::$_class, $options, self::utf8_encode($ns, $encoding), $is_prefix);
		$xml->setEncoding($encoding);
		return $xml;
	}

	/**
	 * Create node from file.
	 * @return Ea_Xml_Element 
	 */
	public static function load_file($filename, $options = null, string $ns = null, $is_prefix = false, $encoding=null )
	{
		if(!$encoding) $encoding=self::$_defaultEncoding;
		$xml=simplexml_load_file(self::utf8_encode($filename, $encoding), self::$_class, $options, self::utf8_encode($ns, $encoding), $is_prefix);
		$xml->setEncoding($encoding);
		return $xml;
	}

	/**
	 * Create node from Dom.
	 * @return Ea_Xml_Element 
	 */
	public static function import_dom(DOMNode $node, $encoding=null)
	{
		if(!$encoding) $encoding=self::$_defaultEncoding;
		$xml=simplexml_import_dom($node, self::$_class);
		$xml->setEncoding($encoding);
		return $xml;
	}
	
}
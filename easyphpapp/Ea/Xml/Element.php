<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Tools
 * @subpackage  Xml
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.2-20081127
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

/**
 * SimpleXMLElement extended class to manage encoding.
 * SimpleXMLElement always needs UTF-8. With Ea_Xml_Element you can specify an input encoding. 
 *
 */
class Ea_Xml_Element extends SimpleXMLElement
{
	protected static $_encoding=null;
	
	public static function set_encoding($encoding)
	{
		if(strtoupper($encoding)=='UTF-8') self::$_encoding=null;
		else self::$_encoding=$encoding;
	}
	
	public static function get_encoding()
	{
		if(self::$_encoding) return self::$_encoding;
		else return 'UTF-8';
	}
	
	/**
	 * User encoding to UTF-8.
	 * 
	 * @param $string
	 * @return unknown_type
	 */
	public static function utf8_encode($string)
	{
		if($string===null)return null;
		//if(!is_string($string)) return $string;
		if(self::$_encoding) return mb_convert_encoding($string, 'UTF-8', self::$_encoding);
		return $string;
	}

	public static function utf8_unencode($string)
	{
		if($string===null)return null;
		//if(!is_string($string)) return $string;
		if(self::$_encoding) return mb_convert_encoding($string, self::$_encoding, 'UTF-8');
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
		return self::utf8_unencode($string);
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
		return parent::xpath(self::utf8_encode($path));
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
		return parent::registerXPathNamespace(self::utf8_encode($prefix), self::utf8_encode($ns));
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
		return parent::attributes(self::utf8_encode($ns), $is_prefix);
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
		return parent::children(self::utf8_encode($ns), $is_prefix);
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
		return parent::addChild( self::utf8_encode($name), self::utf8_encode($value), self::utf8_encode($namespace));
	}
	
	/**
	 * Adds an attribute to the SimpleXML element
	 * @return void 
	 */
	public function addAttribute($name, $value, $namespace=null)
	{
		parent::addAttribute(self::utf8_encode($name), self::utf8_encode($value), self::utf8_encode($namespace));
	}
	
	/**
	 * Create node from string.
	 * @return Ea_Xml_Element 
	 */
	public static function load_string($data, $options = null, string $ns = null, $is_prefix = false )
	{
		return simplexml_load_string(self::utf8_encode($data), self::$_class, $options, self::utf8_encode($ns), $is_prefix);
	}

	/**
	 * Create node from file.
	 * @return Ea_Xml_Element 
	 */
	public static function load_file($filename, $options = null, string $ns = null, $is_prefix = false )
	{
		return simplexml_load_file(self::utf8_encode($filename), self::$_class, $options, self::utf8_encode($ns), $is_prefix);
	}

	/**
	 * Create node from Dom.
	 * @return Ea_Xml_Element 
	 */
	public static function import_dom(DOMNode $node)
	{
		return simplexml_import_dom($node, self::$_class);
	}
	
}
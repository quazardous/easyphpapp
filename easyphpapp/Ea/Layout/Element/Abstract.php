<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Abstract.php';
require_once 'Ea/Layout/Element/Exception.php';

/**
 * Abstract tag element layout class.
 * 
 * In HTML : a tag.
 * 
 * Essentially this class manage the attributes of the tag.
 * 
 * @uses Ea_Layout_Element_Exception
 */
abstract class Ea_Layout_Element_Abstract extends Ea_Layout_Abstract
{
	/**
	 * Tag element constructor.
	 *
	 * @param array $config associative array with config params
	 * 
	 * params can be :
	 * 
	 * - tag : the tag name used to render the tag
	 * 		ie: 'br' for <br />
	 * 
	 * - attributes : an array of HTML attributes used to render the tag
	 * 		ie: array('style'=>'color:#fff;')
	 * 
	 * - value|class|align|id|style|valign|clear|onclick|onblur|onmouseover|onchange : common HTML tag attributes
	 * 
	 */
	public function __construct($config=null)
	{
		parent::__construct($config);
		if(is_array($config))
		{
			foreach($config as $name=>$value)
			{
				if($name=='tag')
				{
					$this->setTag($value);
				}
				else if($name=='attributes')
				{
					$this->setAttributes($value);
				}
				else if(in_array($name, array('value', 'class', 'align', 'id', 'style', 'valign', 'clear', 'onclick', 'onblur', 'onmouseover', 'onchange')))
				{
					$this->setAttribute($name, $value);
				}
			}
		}
	}
	
	/**
	 * The tag name.
	 *
	 * @var string
	 */
	protected $_tag=null;

	/**
	 * Set the tag name.
	 *
	 * @param string $tag
	 * @see $_tag
	 */
	public function setTag($tag)
	{
		$this->_tag=$tag;
	}

	/**
	 * Return the tag name.
	 *
	 * @return string
	 * @see $_tag
	 */
	public function getTag()
	{
		return $this->_tag;
	}

	/**
	 * The HTML attributes of the tag.
	 *
	 * @var array(string=>string)
	 */
	protected $_attributes=array();

	/**
	 * Set attributes from an associative array.
	 *
	 * @param array(string=>string) $attributes
	 * 
	 * @see $_attributes
	 * @uses setAttribute
	 */
	public function setAttributes(array $attributes)
	{
		foreach($attributes as $name=>$value) $this->setAttribute($name, $value);
	}

	/**
	 * Set an attribute.
	 *
	 * @param string $name
	 * @param string|numeric $value
	 * 
	 * @see $_attributes
	 */
	public function setAttribute($name, $value)
	{
		$name=strtolower($name);
		$this->_attributes[$name]=$value;
	}

	public function getAttribute($name)
	{
		$name=strtolower($name);
		if(array_key_exists($name, $this->_attributes)) return $this->_attributes[$name];
		return null;
	} 
	/**
	 * Return all the attributes in an associative array.
	 *
	 * @return array(string=>string)
	 */
	public function getAttributes()
	{
		return $this->_attributes;
	}

	/**
	 * Pre render validation stuff.
	 * It's highly recommended that preRender() call parent::preRender().
	 */
	public function preRender()
	{
		parent::preRender();
		if(!$this->_tag)
		{
			/*
			 * Cannot render tag without $_tag
			 */
			throw new Ea_Layout_Element_Exception("no tag defined");
		}
	}
	
	/**
	 * Render the attribute list.
	 * 
	 */
	protected function renderAttributes()
	{
		foreach($this->_attributes as $name=>$value) echo ' '.$name.'="'.$this->escape($value).'"';
	}
}
?>
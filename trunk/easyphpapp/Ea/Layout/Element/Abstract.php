<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      berlioz [$Author$]
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Abstract.php';

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
	 * Extended class can specify some attributes not to be rendered.
	 * 
	 * @var array
	 */
	protected $_notRenderedAttributes=array();
	
	/**
	 * Attributes that can not be directly set.
	 * 
	 * @var array(attribute=>set method))
	 */
	protected $_protectedAttributes=array();
	
	/**
	 * Protect attribute from beiing directly set.
	 * 
	 * @param string $name
	 * @param string $setMethod set method of the class
	 * @param string $getMethod get method of the class
	 */
	protected function protectAttribute($name, $setMethod=null, $getMethod=null)
	{
		if(!$setMethod) $setMethod='set'.ucfirst($name);
		if(!$getMethod) $getMethod='get'.ucfirst($name);
		$this->_protectedAttributes[$name]=array('set'=>$setMethod, 'get'=>$getMethod);
	}
	
	/**
	 * Test if attribute is protected.
	 * 
	 * @param string $name
	 * @return boolean
	 */
	protected function protectedAttribute($name)
	{
		return array_key_exists($name, $this->_protectedAttributes);
	}
	
	/**
	 * Use the special set method given for this attribute.
	 * 
	 * @param string $name
	 * @param mixed $value
	 */
	protected function setProtectedAttribute($name, $value)
	{
		$method=$this->_protectedAttributes[$name]['set'];
		$this->$method($value);
	}
	
	protected function getProtectedAttribute($name)
	{
		$method=$this->_protectedAttributes[$name]['get'];
		return $this->$method();
	}
	
	/**
	 * Block the rendering of the given attribute.
	 * 
	 * @param string $name
	 * @param boolean $block
	 */
	protected function noRenderAttribute($name, $block=true)
	{
		if($block)
		{
			if(!in_array($name, $this->_notRenderedAttributes))
			{
				$this->_notRenderedAttributes[]=$name;
			}
		}
		else
		{
			if(in_array($name, $this->_notRenderedAttributes))
			{
				$new=array();
				foreach($this->_notRenderedAttributes as $attr)
				{
					if($attr!=$name) $new[]=$attr;
				}
				$this->_notRenderedAttributes=$new;
			}
		}
	}
	
	protected function notRenderedAttribute($name)
	{
		return in_array($name, $this->_notRenderedAttributes);
	}
	
	/**
	 * If attribute already exists add the value as item array.
	 * 
	 * @param string $name
	 * @param string $value
	 */
	public function addAttribute($name, $value)
	{
		$name=strtolower($name);
		if($this->protectedAttribute($name))
		{
			// if protected attribute : like setAttribute()
			$this->setProtectedAttribute($name, $value);
			return;
		}
		if(isset($this->_attributes[$name]))
		{
			if(!is_array($this->_attributes[$name]))
			{
				$this->_attributes[$name]=array($this->_attributes[$name]);
			}
		}
		else
		{
			$this->_attributes[$name]=array();
		}
		$this->_attributes[$name][]=$value;
	}
	
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
		if($this->protectedAttribute($name)) $this->setProtectedAttribute($name, $value);
		else $this->_setAttribute($name, $value);
	}

	/**
	 * Internal and filtered set an attribute.
	 *
	 * @param string $name
	 * @param string|numeric $value
	 * 
	 * @see $_attributes
	 */
	protected function _setAttribute($name, $value)
	{
		$name=strtolower($name);
		$this->_attributes[$name]=$value;
	}
	
	protected function _getAttribute($name)
	{
		$name=strtolower($name);
		if(array_key_exists($name, $this->_attributes)) return $this->_attributes[$name];
		return null;
	}
	
	public function __get($name)
	{
		return $this->getAttribute($name);
	}
	
	public function __set($name, $value)
	{
		$this->setAttribute($name, $value);
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
	 * @return boolean if not true do not render the layout
	 */
	protected function preRender()
	{
		$render=parent::preRender();
		if(!$this->_tag)
		{
			/*
			 * Cannot render tag without $_tag
			 */
		    require_once 'Ea/Layout/Element/Exception.php';
			throw new Ea_Layout_Element_Exception("no tag defined");
		}
		return $render;
	}
	
	/**
	 * Render the attribute list.
	 * 
	 */
	protected function renderAttributes()
	{
		foreach($this->_attributes as $name=>$value)
		{
			if($value!==null&&(!$this->notRenderedAttribute($name)))
			{
				$this->renderAttribute($name, $value);
			}
		}
	}
	
	protected function renderAttribute($name, $value)
	{
		if(is_array($value))
		{
		  switch ($name) {
		    case 'onclick' : $value=trim(implode('; ', $value)); break;
		    default: $value=trim(implode(' ', $value));
		  }
		}
		echo ' '.$name.'="'.$this->escape($value).'"';
	}
}
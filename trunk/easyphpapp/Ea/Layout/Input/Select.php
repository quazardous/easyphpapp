<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.2-20091218
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Input/Abstract.php';

/**
 * Select input layout class.
 */
class Ea_Layout_Input_Select extends Ea_Layout_Input_Abstract
{
	protected $_tag='select';

	/**
	 * Ea_Layout_Input_Abstract constructor.
	 *
	 * @param string|array(string) $id the id of the input
	 * @param array(string=>string) options (value=>label)
	 * @param string|numeric|array $value if array => multiple
	 * @param array $config
	 * 
	 * @see $_id
	 */
	public function __construct($id=null, array $options=array(), $value=null, $config=null)
	{
		parent::__construct($id, $value, $config);
		if(is_array($value)) $this->isMultiple(true);
		$this->noRenderAttribute('value', true);
		$this->_options=$options;
		$this->protectAttribute('multiple', 'setMultiple', 'isMultiple');
	}

	protected function render()
	{
		echo '<';
		echo $this->_tag;
		$this->renderAttributes();
		echo '>';

		foreach($this->_options as $value=>$label)
		{
			echo '<option value="'.$this->escape($value).'"';
			if($this->isSelectedValue($value))
			{
					echo ' selected="selected"';
			}
			echo '>';
			echo $this->escape($label);
			echo "</option>\n";
		}

		echo '</';
		echo $this->_tag;
		echo '>';
	}
	
	/**
	 * Select options.
	 * 
	 * @var array
	 */
	protected $_options=array();
		
	/**
	 * Add an option.
	 * 
	 * @param string $value
	 * @param string $label
	 * @param boolean $selected
	 */
	public function addOption($value, $label, $selected=false)
	{
		$this->_options[$value]=$label;
		$this->selectValue($value, $selected);
	}

	/**
	 * Select value.
	 * 
	 * @param $value
	 * @param $selected
	 */
	public function selectValue($value, $selected=true)
	{
		if($this->isMultiple())
		{
			$this->setMultiple(true);
			if(!is_array($this->_value)) $this->_value=array();
			if($selected)
			{
				if(!in_array($value, $this->_value))
				{
					$this->_value[]=$value;
				}
			}
			else
			{
				if(in_array($value, $this->_value))
				{
					$new=array();
					foreach($this->_value as $val)
					{
						if($val!=$value) $new[]=$val;
					}
					$this->_value=$new;
				}
			}
		}
		else
		{
			if($selected)$this->setValue($value);
			// else nothing
		}
	}
	
	public function isSelectedValue($value)
	{
		if($this->isMultiple())
		{
			return in_array($value, $this->_value);
		}
		else
		{
			return $this->_value==$value;
		}
	}
	
	/**
	 * Select is multiple.
	 * 
	 * @var boolean
	 */
	protected $_multiple=false;
	
	/**
	 * Manage input name for multiple PHP support.
	 * If true and multiple, add [] to the name attribute.
	 * 
	 * @var boolean
	 */
	protected $_magicName=true;
	
	/**
	 * Set Multiple.
	 * 
	 * @param boolean $multiple
	 * @param integer $size number of displayed values
	 */
	public function setMultiple($multiple=true, $size=null)
	{
		if(!is_bool($multiple)) $multiple=strtolower($multiple)=='multiple'?true:false;
		if($multiple)
		{
			if(!is_array($this->_value))
			{
				if($this->_value!==null) $this->_value=array($this->_value);
				else $this->_value=array();
			}
		}
		else
		{
			if(is_array($this->_value))
			{
				if(count($this->_value)) $this->_value=$this->_value[0];
				else $this->_value=null;
			}
		}
		$this->_multiple=$multiple?true:false;
		if($size!==null) $this->setAttribute('size', $size);
	}
	
	/**
	 * Get is multiple.
	 * 
	 * @return boolean
	 */
	public function isMultiple()
	{
		return $this->_multiple;
	}

	/**
	 * Set magic name support.
	 * @see $_magicName
	 * 
	 * @param $magic
	 */
	public function setMagicName($magic)
	{
		$this->_magicName=$magic;
	}
	
	public function isMagicName()
	{
		return $this->_magicName;
	}
	

	/**
	 * Return the input name.
	 * For multiple select name must finish with [].
	 *
	 * @return string
	 * 
	 * @see $_id
	 */
	public function getName()
	{
		$name=parent::getName();
		if($this->isMultiple()&&$this->isMagicName()) $name.='[]';
		return $name;
	}
	
	protected function preRender()
	{
		$render=parent::preRender();
		/*
		 * Display the reserved attributes...
		 */
		if($this->isMultiple())
		{
			$this->_setAttribute('multiple', 'multiple');
		}
		return $render;
	}
	
	public function __sleep()
    {
        return array_merge(parent::__sleep(), array('_multiple'));
    }
	
}


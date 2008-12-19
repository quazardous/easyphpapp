<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.3-20081219
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
	public function __construct($id, array $options=array(), $value=null, $config=null)
	{
		parent::__construct($id, $value, $config);
		if(is_array($value)) $this->isMultiple(true);
		$this->_options=$options;
	}

	public function render()
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
				if(in_array($value, $this->_value, true))
				{
					$new=array();
					foreach($this->_value as $val)
					{
						if($val!==$value) $new[]=$val;
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
			return in_array($value, $this->_value, true);
		}
		else
		{
			return $this->_value===$value;
		}
	}
	
	
	/**
	 * Select is multiple.
	 * 
	 * @var boolean
	 */
	protected $_multiple=false;
	
	/**
	 * Set checked.
	 * 
	 * @param boolean $checked
	 */
	public function setMultiple($multiple=true, $size=null)
	{
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

	public function setAttribute($name, $value)
	{
		$name=strtolower($name);
		switch($name)
		{
			/*
			 * Some reserved attributes...
			 */
			case 'multiple': $this->setMultiple(strtolower($value)=='multiple'); break;
			default:
				parent::setAttribute($name, $value);
		}
	}

	public function getAttribute($name)
	{
		$name=strtolower($name);
		switch($name)
		{
			/*
			 * Some reserved attributes...
			 */
			case 'multiple': return $this->isMultiple()?'multiple':null;
			default: return parent::getAttribute($name);
		}
	}
	
	public function preRender()
	{
		parent::preRender();
		/*
		 * Display the reserved attributes...
		 */
		if($this->isMultiple()) $this->_setAttribute('multiple', 'multiple');
	}
	
	public function __sleep()
    {
        return array_merge(parent::__sleep(), array('_multiple'));
    }
	
}

?>
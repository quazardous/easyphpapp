<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.2.5.20081021
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Input/Abstract.php';

/**
 * Radio button input layout class.
 */
class Ea_Layout_Input_Radio extends Ea_Layout_Input_Abstract
{
	protected $_type='radio';

	/**
	 * Ea_Layout_Input_Abstract constructor.
	 *
	 * @param string|array(string) $id the id of the input
	 * @param string|numeric $value
	 * @param array $config
	 * 
	 * @see $_id
	 */
	public function __construct($id, $value, $selected=false, $config=null)
	{
		$this->setRadioValue($value);
		parent::__construct($id, $selected?$value:null, $config);
	}
	
	/**
	 * The other radio buttons.
	 * 
	 * @var array(Ea_Layout_Input_Radio)
	 */
	protected $_others=array();
	
	/**
	 * The radio value. The value if you select this radio button.
	 * 
	 * @var string
	 */
	protected $_radioValue=null;
	
	/**
	 * Set radio value.
	 * 
	 * @param $value
	 */
	public function setRadioValue($value)
	{
		$this->_radioValue=$value;
	}
	
	/**
	 * Get radio value.
	 * 
	 * @return string
	 */
	public function getRadioValue()
	{
		return $this->_radioValue;
	}

	public function getValue()
	{
		if($this->_groupParent&&$this!==$this->_groupParent)
		{
			return  $this->_groupParent->getValue();
		}
		return parent::getValue();
	}
	
	public function setValue($value)
	{
		if($this->_groupParent&&$this!==$this->_groupParent)
		{
			$this->_groupParent->setValue($value);
		}
		else
		{
			parent::setValue($value);
		}
	}
	
	public function isSelected()
	{
		return $this->getValue()==$this->getRadioValue();
	}
	
	/**
	 * First radio is the group parent.
	 * 
	 * @var Ea_Layout_Input_Radio
	 */
	protected $_groupParent=null;
	
	/**
	 * Get group parent.
	 * 
	 * @return Ea_Layout_Input_Radio
	 */
	public function getGroupParent()
	{
		return $this->_groupParent;
	}
	
	/**
	 * Set group parent.
	 * 
	 * @param Ea_Layout_Input_Radio $parent
	 */
	public function setGroupParent($parent)
	{
		$this->_groupParent=$parent;
	}
	
	public function setAttribute($name, $value)
	{
		$name=strtolower($name);
		switch($name)
		{
			/*
			 * Some reserved attributes...
			 */
			case 'value': $this->setRadioValue($value); break;
			case 'checked': if(strtolower($value)=='checked') $this->setValue($this->getRadioValue()); break;
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
			//TODO : think about it
			case 'value': return $this->getRadioValue(); break;
			case 'checked': return $this->isSelected()?'checked':null; break;
			default: return parent::getAttribute($name);
		}
	}
	
	public function preRender()
	{
		parent::preRender();
		/*
		 * Display the reserved attributes...
		 */
		if($this->getRadioValue()==$this->getValue())
		{
			$this->_setAttribute('checked', 'checked');
		}
		$this->_setAttribute('value', $this->getRadioValue());
	}
	
}
?>
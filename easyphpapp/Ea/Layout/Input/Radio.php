<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      berlioz
 * @version     0.5.2-20110628
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
	protected $_labelBefore = false;

	/**
	 * Ea_Layout_Input_Abstract constructor.
	 *
	 * @param string|array(string) $id the id of the input
	 * @param string|numeric $value
	 * @param array $config
	 * 
	 * @see $_id
	 */
	public function __construct($id, $value, $label=null, $selected=false, $config=null)
	{
		$this->setRadioValue($value);
		parent::__construct($id, $selected?$value:null, $config);
		$this->protectAttribute('value', 'setRadioValue', 'getRadioValue');
		$this->protectAttribute('checked', 'setSelected', 'isSelected');
		$this->protectAttribute('id', 'setAttributeId', 'getAttributeId');
		$this->setLabel($label);
	}

	/*public function setAttributeId($value)
	{
		parent::setAttributeId($value);
	}*/
	
	public function getAttributeId()
	{
		// id attribute can be different from internal id
		$id=$this->_getAttribute('id');
		if($id) return $id;
		return $this->getName().'-'.$this->_delta;
		//TODO : think about it
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
	
	/**
	 * Test if this radio is selected.
	 * 
	 * @return boolean
	 */
	public function isSelected()
	{
		return $this->getValue()==$this->getRadioValue();
	}
	
	/**
	 * Select or unselect this radio.
	 * 
	 * @param boolean $value
	 */
	public function setSelected($value)
	{
		if(!is_bool($value)) $value=strtolower($value)=='checked'||strtolower($value)=='selected'?true:false;
		if($value)
		{
			$this->setValue($this->getRadioValue());
		}
		else
		{
			if($this->getValue()==$this->getRadioValue())
			{
				$this->setValue(null);
			}
		}
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
	
	protected $_delta=0;
	protected $_maxDelta=0;
	public function setDelta($delta=null)
	{
		if(!$this->_groupParent) return;
		if($delta===null)
		{
			$delta=$this->_groupParent->_maxDelta+1;
		}
		if($delta>$this->_groupParent->_maxDelta)
		{
			$this->_groupParent->_maxDelta=$delta;
		}
		$this->_delta=$delta;
	}
	
	protected function preRender()
	{
		if(!$this->_getAttribute('id'))
		{
			$this->_setAttribute('id', $this->getAttributeId());
		}
		$render=parent::preRender();
		/*
		 * Display the reserved attributes...
		 */
		if($this->isSelected())
		{
			$this->_setAttribute('checked', 'checked');
		}
		$this->_setAttribute('value', $this->getRadioValue());
		return $render;
	}

}
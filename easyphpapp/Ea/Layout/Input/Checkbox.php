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
 * Checkbox input layout class.
 */
class Ea_Layout_Input_Checkbox extends Ea_Layout_Input_Abstract
{
	protected $_type='checkbox';
	protected $_labelBefore = false;
	
	/**
	 * Ea_Layout_Input_Abstract constructor.
	 *
	 * @param string|array(string) $id the id of the input
	 * @param boolean $checked
	 * @param string $label
	 * @param string|numeric $value
	 * @param array $config
	 * 
	 */
	public function __construct($id=null, $label=null, $checked=null, $value='X', $config=null)
	{
		parent::__construct($id, $value, $config);
		if($checked!==null) {
		  $this->setChecked($checked);
		}
		$this->setLabel($label);
		$this->protectAttribute('checked', 'setChecked', 'isChecked');
	}
	
	/**
	 * Checkbox is checked or not.
	 * 
	 * @var boolean
	 */
	protected $_checked=null;
	
	/**
	 * Set checked.
	 * 
	 * @param boolean $checked
	 */
	public function setChecked($checked)
	{
		if(is_bool($checked)) $this->_checked=$checked;
		else $this->_checked=strtolower($checked)=='checked'?true:false;
	}
	
	public function setDefaultChecked($checked)
	{
		if($this->_checked===null)
		{
			$this->_checked=$checked;
		}
	}
	
	/**
	 * Get checked.
	 * 
	 * @return boolean
	 */
	public function isChecked()
	{
		return $this->_checked;
	}
	
	protected function preRender()
	{
		$render=parent::preRender();
		/*
		 * Display the reserved attributes...
		 */
		if($this->isChecked()) $this->_setAttribute('checked', 'checked');
		return $render;
	}
	
	/**
	 * Set the value from $_POST.
	 * 
	 * @param string|numeric $value from POST
	 * 
	 */
	public function setValueFromForm($value=null)
	{
		parent::setValueFromForm($value);
		$this->setChecked($this->getValue()!=null);
	}
	
    /**
     * Set remember Value.
     * 
     * @param string $defaultValue
     * 
     * @param boolean $remember
     */
  public function rememberValue($defaultValue=false)
  {
  	parent::rememberValue($defaultValue);
  	$input=$this->getForm()->getInputFromSession($this->getId());
  	if($input) $this->setChecked($input->isChecked());
  }
	
	public function __sleep()
  {
      return array_merge(parent::__sleep(), array('_checked'));
  }
    
}
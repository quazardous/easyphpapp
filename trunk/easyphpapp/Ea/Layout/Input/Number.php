<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Input/Abstract.php';

/**
 * Number input layout class.
 */
class Ea_Layout_Input_Number extends Ea_Layout_Input_Abstract
{
	protected $_type='text';
	
	/**
	 * Number format for rendering/submitting value.
	 * 
	 * @var string
	 * @see sprintf()
	 */
	protected $_formFormat=null; // null = no format
	protected $_formFormatDecPoint=null;
	protected $_formFormatThousandSep='';
	
	public function setFormat($format, $decPoint=null, $thousandSep=null)
	{
		$this->_formFormat=$format;
		if($decPoint!==null)$this->setDecPoint($decPoint);
		if($thousandSep!==null)$this->setThousandSep($thousandSep);
	}
	
	public function setDecPoint($decPoint)
	{
		$this->_formFormatDecPoint=$decPoint;
	}
	
	public function setThousandSep($thousandSep)
	{
		$this->_formFormatThousandSep=$thousandSep;
	}
	
	/**
	 * Number input constructor.
	 * 
	 * @param string $id
	 * @param string $value
	 * @param string $formFormat form number format
	 * @param string $decPoint decimal point
	 * @param string $thousandSep thousand separator
	 * @param array $config
	 */
	public function __construct($id=null, $value=null, $formFormat=null, $decPoint=null, $thousandSep=null, $config=null)
	{
		parent::__construct($id, $value, $config);
	  $this->addAttribute('class', 'number');
		$this->setFormat($formFormat, $decPoint, $thousandSep);
	}
	
	/**
	 * Set the value from $_POST.
	 * 
	 * @param string|numeric $value from POST
	 * 
	 */
	public function setValueFromForm($value=null)
	{
		if($value===null) $value=$this->getForm()->getValueFromPost($this->getId());
		if($value)
		{
			if($this->_formFormatThousandSep!==null)
			{
				$value=str_replace($this->_formFormatThousandSep, '', $value);
			}
			if($this->_formFormatDecPoint!==null)
			{
				$value=str_replace($this->_formFormatDecPoint, '.', $value);
			}
			$this->setValue(floatval($value));
		}
		else
		{
			$this->setValue(null);
		}
	}
	
	/**
	 * Return the value for form rendering.
	 * 
	 * @return string
	 */
	public function getValueForForm()
	{
		return $this->getNumber();
	}
	
	public function getNumber($format=null, $decPoint=null, $thousandSep=null)
	{
		if($format===null)$format=$this->_formFormat;
		if(!$decPoint)$decPoint=$this->_formFormatDecPoint;
		if($thousandSep===null)$thousandSep=$this->_formFormatThousandSep;
		$value=$this->getValue();
		if($format) $value=sprintf($format, $value);
		if(preg_match('/^([^.]+)[.]([^.]+)$/', (string)$value, $matches))
		{
			$int=$matches[1];
			$point='.';
			$dec=$matches[2];
		}
		else
		{
			$int=(string)$value;
			$point='';
			$dec='';
		}
			
		if($thousandSep!==null)
		{
			$int=strrev(implode($thousandSep, str_split(strrev($int), 3)));
		}
		
		if($decPoint) $point=$decPoint;
		
		$value=$int;
		if($dec)
		{
			$value.=$point.$dec;
		}
		return $value;
	}
	
	public function __sleep()
    {
        return array_merge(parent::__sleep(), array('_formFormat', '_formFormatDecPoint', '_formFormatThousandSep'));
    }
	
}

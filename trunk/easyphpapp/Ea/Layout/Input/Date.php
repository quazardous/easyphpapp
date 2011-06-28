<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.1-20091130
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Input/Abstract.php';

/**
 * Date input layout class.
 */
class Ea_Layout_Input_Date extends Ea_Layout_Input_Abstract
{
	protected $_type='text';
	/**
	 * Date format for getting/setting value.
	 * 
	 * @var string
	 * @see strftime()
	 */
	protected $_format='%Y-%m-%d';
	
	/**
	 * Date format for rendering/submitting value.
	 * 
	 * @var string
	 * @see strftime()
	 */
	protected $_formFormat=null; // null = same as $_format;
	
	/**
	 * Get value.
	 * @param string $format
	 * 
	 * @return string|NULL
	 * @see Ea/Layout/Input/Ea_Layout_Input_Abstract#getValue()
	 */
	public function getValue($format=null)
	{
		if($this->_value)
		{
			if(!$format) $format=$this->_format;
			return strftime($format, $this->_value);
		}
		return null;
	}
	
	public function getDate($format=null)
	{
		return $this->getValue($format);
	}

	/**
	 * Set the input value.
	 *
	 * @param string $value
	 * 
	 * @see $_value
	 */
	public function setValue($value)
	{
		if($value)
		{
			$d=strptime($value, $this->_format);
			if($d)
			{
				$this->setTimestamp(mktime(
					$d['tm_hour'],
					$d['tm_min'],
					$d['tm_sec'],
					$d['tm_mon']+1,
					$d['tm_mday'],
					$d['tm_year']+1900));
			}
			else
			{
				$this->setTimestamp(null);
			}
		}
		else
		{
			$this->setTimestamp(null);
		}
	}
	
	/**
	 * Return the timestamp for the current value.
	 * 
	 * @return NULL|timestamp
	 */
	public function getTimestamp()
	{
		return $this->_value;
	}

	/**
	 * Set the timestamp.
	 * 
	 * @param timestamp $timestamp
	 */
	public function setTimestamp($timestamp)
	{
		$this->_value=$timestamp;
	}	
	
	/**
	 * Set the formats in strftime() format.
	 * 
	 * @param string $format
	 * @param string $formFormat
	 * 
	 * @see strptime(), strftime()
	 */
	public function setFormat($format, $formFormat=false)
	{
		$this->_format=$format;
		if($formFormat!==false)
		{
			$this->setFormFormat($formFormat);
		}
	}
	
	public function setFormFormat($format)
	{
		$this->_formFormat=$format;
	}
	
	/**
	 * Date input constructor.
	 * 
	 * @param string $id
	 * @param string $value
	 * @param string $format internal date format
	 * @param unknown_type $formFormat form date format
	 * @param unknown_type $config
	 */
	public function __construct($id=null, $value=null, $format=null, $formFormat=null, $config=null)
	{
		parent::__construct($id, $value, $config);
	  $this->addAttribute('class', 'date');
		if($format) $this->setFormat($format, $formFormat);
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
			$format=$this->_formFormat;
			if(!$format) $format=$this->_format;
			$d=strptime($value, $format);
			if($d)
			{
				$this->setTimestamp(mktime(
					$d['tm_hour'],
					$d['tm_min'],
					$d['tm_sec'],
					$d['tm_mon']+1,
					$d['tm_mday'],
					$d['tm_year']+1900));
			}
			else
			{
				$this->setTimestamp(null);
			}
		}
		else
		{
			$this->setTimestamp(null);
		}
	}
	
	/**
	 * Return the value for form rendering.
	 * 
	 * @return string
	 */
	public function getValueForForm()
	{
		return $this->getDate($this->_formFormat);
	}
	
	public function __sleep()
    {
        return array_merge(parent::__sleep(), array('_format', '_formFormat'));
    }
	
}

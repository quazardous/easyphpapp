<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      berlioz [$Author$]
 * @version     0.4.6-20101007 [$Id$]
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Field/Input/Abstract.php';

/**
 * Field value date input from record (array or object).
 */
class Ea_Layout_Record_Adapter_Field_Input_Date extends Ea_Layout_Record_Adapter_Field_Input_Abstract
{
	
	/**
	 * Date format for getting/setting value.
	 * 
	 * @var string
	 */
	protected $_format;
	
	/**
	 * Date format for rendering/submitting value.
	 * 
	 * @var string
	 */
	protected $_formFormat;
	
	/**
	 * Constructor.
	 * 
	 * @param $field of record
	 * @param string $format internal date format
	 * @param string $formFormat form date format
	 * @param $baseId base to construct input name/id.
	 * @param $indexColumn if given, name of the column usable as index to construct the input name.
	 * @param $config
	 */
	public function __construct($field, $format=null, $formFormat=null, $baseId=null, $indexColumn=null, $config=null)
	{
		parent::__construct($field, $baseId, $indexColumn, $config);
		$this->_format=$format;
		$this->_formFormat=$formFormat;
	}
	
	/**
	 * This function must return content from record.
	 * 
	 * @param array $record
	 * @return string|Ea_Layout_Abstract
	 */
	public function getContent($record, $i)
	{
		if(defined('EA_RICH')&&EA_RICH)
		{
			require_once 'Ea/Layout/Input/DatePicker.php';
			return new Ea_Layout_Input_DatePicker($this->getId($record, $i), $this->getRawValue($record), $this->_format, $this->_formFormat, $this->_config);
		}
		require_once 'Ea/Layout/Input/Date.php';
		return new Ea_Layout_Input_Date($this->getId($record, $i), $this->getRawValue($record), $this->_format, $this->_formFormat, $this->_config);
	}
}

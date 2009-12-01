<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.1-20091201
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Field/Input/Abstract.php';
require_once 'Ea/Layout/Input/Date.php';

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
	 * @param unknown_type $formFormat form date format
	 * @param $baseId base to construct input name/id.
	 * @param $indexColumn if given, name of the column usable as index to construct the input name.
	 * @param $config
	 */
	public function __construct($field, $format=null, $formFormat=null, $baseId=null, $indexColumn=null, $config=null)
	{
		parent::__construct($field, $baseId, $indexColumn, $config);
		$this->_format=$format;
		$this->_formatFormat=$formFormat;
	}
	
	/**
	 * This function must return content from record.
	 * 
	 * @param array $record
	 * @return string
	 */
	public function getContent($record, $i)
	{
		return new Ea_Layout_Input_Date($this->getId($record, $i), $this->getRawValue($record), $this->_format, $this->_formatFormat, $this->_config);
	}
}

?>
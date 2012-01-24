<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      berlioz
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Field/Input/Abstract.php';

/**
 * Field value number input from record (array or object).
 */
class Ea_Layout_Record_Adapter_Field_Input_Number extends Ea_Layout_Record_Adapter_Field_Input_Abstract
{
	
	protected $_decPoint;
	protected $_thousandSep;
	protected $_formFormat;
	
	/**
	 * Constructor.
	 * 
	 * @param $field of record
	 * @param string $formFormat form number format (sprintf)
	 * @param string $decPoint decimal point
	 * @param string $thousandSep thousand separator
	 * @param $baseId base to construct input name/id.
	 * @param $indexColumn if given, name of the column usable as index to construct the input name.
	 * @param $config
	 */
	public function __construct($field, $formFormat=null, $decPoint=null, $thousandSep=null, $baseId=null, $indexColumn=null, $config=null)
	{
		parent::__construct($field, $baseId, $indexColumn, $config);
		$this->_decPoint=$decPoint;
		$this->_thousandSep=$thousandSep;
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
		require_once 'Ea/Layout/Input/Number.php';
		//($id=null, $value=null, $formFormat=null, $decPoint=null, $thousandSep=null, $config=null)
		return new Ea_Layout_Input_Number($this->getId($record, $i), $this->getRawValue($record), $this->_formFormat, $this->_decPoint, $this->_thousandSep, $this->_config);
	}
}

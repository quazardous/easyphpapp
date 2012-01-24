<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      berlioz [$Author$]
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Field/Input/Abstract.php';

/**
 * Field value text input from record (array or object).
 */
class Ea_Layout_Record_Adapter_Field_Input_Textarea extends Ea_Layout_Record_Adapter_Field_Input_Abstract
{
	protected $_rows=null;
	protected $_cols=null;
	
	public function __construct($field, $rows=null, $cols=null, $baseId=null, $indexColumn=null, $config=null)
	{
	  parent::__construct($field, $baseId, $indexColumn, $config);
		$this->_rows=$rows;
		$this->_cols=$cols;
	}
	
	/**
	 * This function must return content from record.
	 * 
	 * @param array $record
	 * @return string
	 */
	public function getContent($record, $i)
	{
		require_once 'Ea/Layout/Input/Textarea.php';
		return new Ea_Layout_Input_Textarea($this->getId($record, $i), $this->_rows, $this->_cols, $this->getRawValue($record), $this->_config);
	}
}

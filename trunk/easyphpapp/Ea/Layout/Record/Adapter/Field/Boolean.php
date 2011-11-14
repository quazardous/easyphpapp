<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      berlioz
 * @version     $Id:$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Field.php';

/**
 * Field boolean from record (array or object).
 */
class Ea_Layout_Record_Adapter_Field_Boolean extends Ea_Layout_Record_Adapter_Field
{
	
	/**
	 * @var string
	 */
	protected $_labelTrue = 'True';

	/**
	 * @var string
	 */
	protected $_labelFalse = 'False';	
	
	public function __construct($column, $labelTrue, $labelFalse)
	{
	  parent::__construct($column);
		if ($labelTrue) $this->_labelTrue=$labelTrue;
		if ($labelFalse) $this->_labelFalse=$labelFalse;
	}
		
	/**
	 * This function must return content from record.
	 * 
	 * @param array $record
	 * @param integer $i record number
	 * @return string
	 */
	public function getContent($record, $i)
	{
		if ($this->getRawValue($record)) return $this->_labelTrue;
		return $this->_labelFalse;
	}
}

<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.5-20080209
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Field.php';

/**
 * Abstract class to get column cell content from record (array or object).
 */
abstract class Ea_Layout_Record_Adapter_Field_Input_Abstract extends Ea_Layout_Record_Adapter_Field
{
	protected $_baseId=null;
	protected $_config=null;
	protected $_indexColumn=null;
	
	/**
	 * Constructor.
	 * 
	 * @param $field of record
	 * @param $baseId base to construct input name/id.
	 * @param $indexColumn if given, name of the column usable as index to construct the input name.
	 * @param $config
	 */
	public function __construct($field, $baseId=null, $indexColumn=null, $config=null)
	{
		$this->_field=$field;
		if($baseId)$this->_baseId=Ea_Layout_Input_Abstract::get_id_from_name($baseId);
		$this->_indexColumn=$indexColumn;
		$this->_config=$config;
	}
	
	/**
	 * Construct the name of the input from record index.
	 * 
	 * @param $record
	 * @param $i record index. if null : do not add index to the name.
	 * @return string
	 */
	protected function getId($record, $i)
	{
		if($this->_baseId) $id=$this->_baseId;
		else $id=array();
		if($i!==null&&$this->_baseId)
		{
			$id[]=$this->getIndex($record, $i);
		}
		$id[]=$this->_field;
		return $id;
	}
	
	/**
	 * Return the index to use in the input name.
	 * 
	 * @param $record
	 * @param $i index of the record in the rowset or null.
	 * @return string
	 * @see getId()
	 */
	protected function getIndex($record, $i)
	{
		if($this->_indexColumn) return $this->getValue($record, $this->_indexColumn);
		return $i;
	}
}

?>
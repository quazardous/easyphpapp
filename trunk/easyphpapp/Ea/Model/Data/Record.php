<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Data
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.1-20081114
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Model/Data/Abstract.php';

/**
 * Record Data model class.
 * Create a data model from a simple record (uses Iterator key).
 * 
 */
class Ea_Model_Data_Record extends Ea_Model_Data_Abstract
{
	
	/**
	 * Set the record.
	 * 
	 * @param Iterator $record
	 */
	public function setRecord($record)
	{
		$this->_record=$record;
	}
	
	/**
	 * The record.
	 * 
	 * @var Iterator
	 */
	protected $_record=null;
	
	/**
	 * Constructor.
	 * 
	 * @param array|Zend_Db_Table $config
	 * 
	 * array(
	 * 		'record' => $instance_of_Iterator,
	 * )
	 */
	public function __construct($config=null)
	{
		if($config instanceof Iterator || is_array($config))
		{
			$config=array('record'=>$config);
		}
		if(array_key_exists('record', $config))
		{
			$this->setRecord($config['record']);
		}
		$this->_analyzeRecord();
	}
	
	/**
	 * Retrieve information from record.
	 * 
	 */
	protected function _analyzeRecord()
	{
		$this->_columns=array();
		$i=0;
		foreach($this->_record as $column => $value)
		{
			$this->_columns[]=$column;
			$this->setColumnOrder($column, $i);
			$this->setColumnLabel($column, $column);
			$this->setColumnDisplay($column, true);
			$this->setColumnType($column, self::type_unknown);
			$i++;
		}
	}
	
}
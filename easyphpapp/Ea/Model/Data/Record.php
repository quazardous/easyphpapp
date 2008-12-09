<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Data
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.2-20081201
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Model/Data/Db.php';

/**
 * Record Data model class. Can or caonnot be record from db table.
 * Create a data model from a simple record (uses Iterator key).
 * 
 */
class Ea_Model_Data_Record extends Ea_Model_Data_Db
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
	 * Adapter if any.
	 * 
	 * @var Zend_Db_Adapter_Abstract
	 */
	protected $_adapter=null;

	public function setAdapter($adapter)
	{
		$this->_adapter=$adapter;
	}
	
	/**
	 * Constructor.
	 * 
	 * @param array|Zend_Db_Table $config
	 * 
	 * array(
	 * 		'record' => $instance_of_Iterator,
	 * )
	 */
	public function __construct($record, $adapter=null)
	{
		$this->setRecord($record);
		$this->setAdapter($adapter);
		$this->_analyzeRecord();
	}
	
	/**
	 * Retrieve information from record.
	 * 
	 */
	protected function _analyzeRecord()
	{
		if($this->_adapter instanceof Zend_Db_Adapter_Pdo_Oci)
		{
			$query='SELECT value FROM V$NLS_Parameters WHERE parameter =\'NLS_DATE_FORMAT\'';
			$stmt=$this->_adapter->query($query);
			$dbDateFormat=$stmt->fetchColumn(0);
			$this->_defaultDbDateFormat=self::format_date_oracle_to_php($dbDateFormat);
			$this->_defaultDbDatetimeFormat=self::format_date_oracle_to_php($dbDateFormat);
		}
		$this->_columns=array();
		$i=0;
		foreach($this->_record as $column => $value)
		{
			$this->_columns[]=$column;
			$this->_analyzeValue($i, $column, $value);
			$i++;
		}
		$this->_ordered=true;
	}
	
	protected function _analyzeValue($i, $column, $value)
	{
		$this->setColumnOrder($column, $i);
		$this->setColumnLabel($column, $column);
		if(is_string($value))
		{
			$arrd=strptime($value, $this->_defaultDbDatetimeFormat);
			if($arrd)
			{
				$this->setColumnType($column, self::type_datetime);
				$this->setColumnDateFormat($column, $this->_defaultDbDatetimeFormat);
			}
			else
			{
				$arrd=strptime($value, $this->_defaultDbDateFormat);
				if($arrd)
				{
					$this->setColumnType($column, self::type_date);
					$this->setColumnDateFormat($column, $this->_defaultDbDateFormat);
				}
				else
				{
					$this->setColumnType($column, self::type_string);
				}
			}
		}
		else if(is_float($value)||is_double($value))
		{
			$this->setColumnType($column, self::type_float);
		}
		else if(is_integer($value)||is_long($value))
		{
			$this->setColumnType($column, self::type_integer);
		}
		else if(is_resource($value))
		{
			$this->setColumnType($column, self::type_text);
			$this->setColumnLobType($column, 'stream');
			$this->setColumnLobLoad($column, $this->_defaultLobLoad);
			$this->setColumnStringLength($column, 4294967296);
		}
		else if(is_a($value, 'OCI-Lob'))
		{
			$this->setColumnType($column, self::type_text);
			$this->setColumnLobType($column, 'ocilob');
			$this->setColumnLobLoad($column, $this->_defaultLobLoad);
			$this->setColumnStringLength($column, 4294967296);
		}
		else
		{
			$this->setColumnType($column, self::type_unknown);
			trigger_error("$column : can't define type");
		}
	}
	
}
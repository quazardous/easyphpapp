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

require_once 'Ea/Model/Data/Select/Exception.php';
require_once 'Ea/Model/Data/Abstract.php';
require_once 'Zend/Db/Adapter/Abstract.php';
require_once 'Zend/Db/Adapter/Pdo/Mysql.php';
require_once 'Zend/Db/Adapter/Pdo/Oci.php';
require_once 'Zend/Db/Statement.php';
require_once 'Zend/Db/Select.php';
require_once 'Zend/Db/Statement/Pdo.php';

/**
 * Select statement Data model class.
 * Zend_Db_Table data object model.
 * 
 */
class Ea_Model_Data_Select extends Ea_Model_Data_Abstract
{

	static protected $_defaultAdapter=null;
	static public function set_default_adapter(Zend_Db_Adapter_Abstract $adapter)
	{
		$this->_defaultAdapter=$adapter;
	}
	
	/**
	 * Statement.
	 * 
	 * @var Zend_Db_Statement
	 */
	protected $_statement=null;
	
	/**
	 * Constructor.
	 * 
	 * @param Zend_Db_Select|Zend_Db_Statement|string $config
	 * 
	 * array(
	 * 		'db_table' => $instance_of_Zend_Db_Table,
	 * 		'db_table_class' => 'zend_db_table_class_name',
	 * 		'db_table_config', => array(config for zend_db_table constructor),
	 * )
	 */
	public function __construct($config=null)
	{
		if($config instanceof Zend_Db_Select)
		{
			$this->_statement=$adapter->prepare($config);
		}
		else if($config instanceof Zend_Db_Statement)
		{
			$this->_statement=$config;
		}
		else if(is_string($config))
		{
			$this->_statement=self::$_defaultAdapter->prepare($config);
		}
		if((!$this->_statement)||$this->_statement->columnCount()==0)
		{
			throw new Ea_Model_Data_Select_Exception('You must provide a select query');
		}
		$this->_analyzeDbStatement();
	}
	
	/**
	 * Retrieve information from db statement.
	 * 
	 */
	protected function _analyzeDbStatement()
	{
		if($this->_statement->getAdapter() instanceof Zend_Db_Adapter_Pdo_Mysql)
		{
			$this->_analyzeDbStatementPdoMysql();
		}
		else if($this->_statement->getAdapter() instanceof Zend_Db_Adapter_Pdo_Oci)
		{
			//$this->_analyzeDbStatementPdoOci();
			// TODO : not supported by PDO_OCI : watch it
			$this->_analyzeDbStatementUnsupported();
		}
		else
		{
			$this->_analyzeDbStatementUnsupported();
		}
	}
	
	protected function _analyzeDbStatementPdoMysql()
	{
		$n=$this->_statement->columnCount();
		$i=0;
		for($i=0;$i<$n;$i++)
		{
			$meta=$this->_statement->getColumnMeta($i);
			$this->_columns[]=$meta['name'];
			$this->_analyzeMetaPdoMysql($i, $meta['name'], $meta);
		}
		$this->_ordered=true;
	}

	protected function _analyzeDbStatementPdoOci()
	{
		throw new Ea_Model_Data_Select_Exception('Sorry PDO_OCI does not support getColumnMeta()');
		/*
		$query='SELECT value FROM V$NLS_Parameters WHERE parameter =\'NLS_DATE_FORMAT\'';
		$stmt=$this->_statement->getAdapter()->query($query);
		$dbDateFormat=$stmt->fetchColumn(0);
		$this->_defaultDateDbformat=self::format_date_oracle_to_php($dbDateFormat);
		$this->_defaultDatetimeDbformat=self::format_date_oracle_to_php($dbDateFormat);
		$n=$this->_statement->columnCount();
		
		$row=$this->_statement->fetch(Zend_Db::FETCH_ASSOC);
		$i=0;
		foreach($row as $column => $value)
		{
			$this->_columns[]=$column;
			$this->_analyzeValuePdoOci($i, $column, $value);
			$i++;
		}
		*/
		//$this->_statement->fetch(Zend_Db::FETCH_ASSOC);
		
		// getColumnMeta() is not supported for PDO_OCI
		/*
		$i=0;
		for($i=0;$i<$n;$i++)
		{
			$meta=$this->_statement->getColumnMeta($i);
			//$meta=array("name"=>"column_$i");
			$this->_columns[]=$meta['name'];
			$this->_analyzeMetaPdoOci($i, $meta['name'], $meta);
		}
		*/
		//$this->_ordered=true;
	}
	
	protected function _analyzeDbStatementUnsupported()
	{
		trigger_error("Unsupported adapter for meta analyze");
		$n=$this->_statement->columnCount();
		$i=0;
		for($i=0;$i<$n;$i++)
		{
			//$meta=$this->_statement->getColumnMeta($i);
			$meta=array("name"=>$i);
			$this->_columns[]=$meta['name'];
			$this->_analyzeMetaUnsuported($i, $meta['name'], $meta);
		}
				$this->_ordered=true;
	}

	protected function _analyzeMetaPdoMysql($i, $column, &$meta)
	{
		$this->setColumnOrder($column, $i);
		$this->setColumnLabel($column, $column);
		if(!isset($meta['native_type']))$meta['native_type']=null;
		switch($meta['native_type'])
		{
			case 'LONGLONG': case 'LONG': case 'SHORT': case 'INT24':
				$this->setColumnType($column, self::type_integer);
				break;
			case 'FLOAT': case 'DOUBLE':
				$this->setColumnType($column, self::type_float);
				break;
			case 'NEWDECIMAL':
				$this->setColumnType($column, self::type_float);
				$this->setColumnNumberDecimals($column, $meta['precision']);
				break;
			case 'STRING': case 'VAR_STRING':
				$this->setColumnType($column, self::type_string);
				$this->setColumnStringLength($column, $meta['len']);
				break;
			case 'BLOB':
				$this->setColumnType($column, self::type_text);
				$this->setColumnStringLength($column, $meta['len']);
				break;
			case 'DATE': case 'DATETIME': case 'TIMESTAMP':
				switch($meta['native_type'])
				{
					case 'DATE':
						$this->setColumnType($column, self::type_date);
						$this->setColumnDateDbformat($column, $this->_defaultDateDbformat);
						break;
					default:
						$this->setColumnType($column, self::type_datetime);
						$this->setColumnDateDbformat($column, $this->_defaultDateDbformat);
				}
					
				break;
			default:
				$this->setColumnType($column, self::type_unknown);
				trigger_error("{$meta['native_type']}: unsupported type");

		}
	}

	protected function _analyzeMetaPdoUnsupported($i, $column, &$meta)
	{
		$this->setColumnOrder($column, $i);
		$this->setColumnLabel($column, $column);
		if(!isset($meta['native_type']))$meta['native_type']=null;
		switch($meta['native_type'])
		{
			default:
				$this->setColumnType($column, self::type_unknown);
				trigger_error("{$meta['native_type']}: unsupported type");
		}
	}
	
	
}
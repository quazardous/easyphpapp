<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Data
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Model/Data/Abstract.php';

/**
 * Table Data model class.
 * Zend_Db_Table data object model.
 * 
 */
class Ea_Model_Data_Table extends Ea_Model_Data_Abstract
{

	/**
	 * Meta info on columns.
	 * array(
	 *   'column name'=>array(
	 *      'default'       => 'value'|array('type'=>'value|callback', 'value'=>'value', 'callback'=>'callback'),
	 *   ),
	 * )
	 * 
	 * @var array
	 */
	//protected $_metadata=array();
	
	
	protected $_name=null;
	
	protected $_schema=null;
	
	protected $_primary=null;

	public function getName()
	{
		return $this->_name;
	}

	public function getSchema()
	{
		return $this->_schema;
	}
	
	public function getPrimary()
	{
		return (array)$this->_primary;
	}
	
	/**
	 * The zend db table class.
	 * 
	 * @var string
	 */
	protected $_dbTableClass='Zend_Db_Table_Abstract';
	
	/**
	 * Set the zend db table class.
	 * 
	 * @param string $class
	 */
	public function setDbTableClass($class)
	{
		$this->_dbTableClass=$class;
	}
	
	/**
	 * The table object.
	 * 
	 * @var Zend_Db_Table
	 */
	protected $_dbTable=null;
	
	/**
	 * Set the table object.
	 * 
	 * @param Zend_Db_Table $dbTable
	 */
	public function setDbTable(Zend_Db_Table_Abstract $dbTable)
	{
		$this->_dbTable=$dbTable;
	}
	
	/**
	 * Constructor.
	 * 
	 * @param array|Zend_Db_Table $config
	 * 
	 * array(
	 * 		'db_table' => $instance_of_Zend_Db_Table,
	 * 		'db_table_class' => 'zend_db_table_class_name',
	 * 		'db_table_config', => array(config for zend_db_table constructor),
	 * )
	 */
	public function __construct($config=null)
	{
		if($config instanceof Zend_Db_Table_Abstract)
		{
			$config=array('db_table'=>$config);
		}
		if(array_key_exists('db_table', $config))
		{
			$this->setDbTable($config['db_table']);
		}
		if(array_key_exists('db_table_class', $config))
		{
			$this->setDbTableClass($config['db_table_class']);
		}
		if(!$this->_dbTable)
		{
			$dbTableConfig=null;
			if(array_key_exists('db_table_config', $config))
			{
				$dbTableConfig=$config['db_table_config'];
			}
			$dbTableClass=$this->_dbTableClass;
			require_once 'Zend/Loader.php';
			Zend_Loader::loadClass($dbTableClass);
			$this->setDbTable(new $dbTableClass($dbTableConfig));
		}
		$this->_analyzeDbTable();
	}
	
	/**
	 * Retrieve information from zend db table.
	 * 
	 */
	protected function _analyzeDbTable()
	{
		$info=$this->_dbTable->info();
		$this->_name=$info['name'];
		$this->_schema=$info['schema'];
		$this->_primary=$info['primary'];
		$this->_columns=$info['cols'];
		if($this->_dbTable->getAdapter() instanceof Zend_Db_Adapter_Pdo_Mysql)
		{
			$this->_analyzeDbTablePdoMysql($info);
		}
		else if($this->_dbTable->getAdapter() instanceof Zend_Db_Adapter_Pdo_Oci)
		{
			$this->_analyzeDbTablePdoOci($info);
		}
		else if($this->_dbTable->getAdapter() instanceof Zend_Db_Adapter_Oracle)
		{
			$this->_analyzeDbTableOracle($info);
		}
		else
		{
			$this->_analyzeDbTableUnsupported($info);
		}
	}
	
	protected function _analyzeDbTablePdoMysql(&$info)
	{
		$i=0;
		foreach($this->_columns as $column)
		{
			$this->_analyzeMetaPdoMysql($i, $column, $info['metadata'][$column]);
			$i++;
		}
		$this->_ordered=true;
	}

	protected function _analyzeDbTablePdoOci(&$info)
	{
		$this->initOracleDbDateFormat();
		$i=0;
		foreach($this->_columns as $column)
		{
			$this->_analyzeMetaPdoOci($i, $column, $info['metadata'][$column]);
			$i++;
		}
		$this->_ordered=true;
	}

	protected function _analyzeDbTableOracle(&$info)
	{
		$this->initOracleDbDateFormat();
		$i=0;
		foreach($this->_columns as $column)
		{
			$this->_analyzeMetaOracle($i, $column, $info['metadata'][$column]);
			$i++;
		}
		$this->_ordered=true;
	}
	
	protected function _analyzeDbTableUnsupported(&$info)
	{
		trigger_error("Unsupported adapter for meta analyze");
		$i=0;
		foreach($this->_columns as $column)
		{
			$this->_analyzeMetaPdoUnsupported($i, $column, $info['metadata'][$column]);
			$i++;
		}
		$this->_ordered=true;
	}

	protected function _analyzeMetaPdoMysql($i, $column, &$meta)
	{
		$this->setColumnOrder($column, $i);
		if($meta['DEFAULT'])
		{
			$this->setColumnDefault($column, $meta['DEFAULT']);
		}
		switch($meta['DATA_TYPE'])
		{
			case 'int': case 'tinyint': case 'smallint': case 'mediumint': case 'bigint':
				$this->setColumnType($column, self::type_integer);
				break;
			case 'float': case 'double':
				$this->setColumnType($column, self::type_float);
				break;
			case 'decimal':
				$this->setColumnType($column, self::type_float);
				$this->setColumnNumberDecimals($column, $meta['SCALE']);
				break;
			case 'varchar': case 'char':
				$this->setColumnType($column, self::type_string);
				$this->setColumnStringLength($column, $meta['LENGTH']);
				break;
			case 'text': case 'tinytext': case 'mediumtext': case 'longtext':
				$this->setColumnType($column, self::type_text);
				switch($meta['DATA_TYPE'])
				{
					case 'tinytext':
						$this->setColumnStringLength($column, 256);
						break;
					case 'text':
						$this->setColumnStringLength($column, 65536);
						break;
					case 'mediumtext':
						$this->setColumnStringLength($column, 16777216);
						break;
					case 'longtext':
						$this->setColumnStringLength($column, 4294967296);
						break;
				}
				break;
			case 'blob': case 'tinyblob': case 'mediumblob': case 'longblob':
				$this->setColumnType($column, self::type_binary);
				switch($meta['DATA_TYPE'])
				{
					case 'tinyblob':
						$this->setColumnLobLength($column, 256);
						break;
					case 'blob':
						$this->setColumnLobLength($column, 65536);
						break;
					case 'mediumblob':
						$this->setColumnLobLength($column, 16777216);
						break;
					case 'longblob':
						$this->setColumnLobLength($column, 4294967296);
						break;
				}
				break;
			case 'date': case 'datetime': case 'timestamp':
				switch($meta['DATA_TYPE'])
				{
					case 'date':
						$this->setColumnType($column, self::type_date);
						$this->setColumnDateDbformat($column, $this->_defaultDateDbformat);
						break;
					default:
						$this->setColumnType($column, self::type_datetime);
						$this->setColumnDateDbformat($column, $this->_defaultDatetimeDbformat);
				}
				if($meta['DEFAULT']=='CURRENT_TIMESTAMP')
				{
					//TODO : todo
					$this->setColumnDefault($column, array('type'=>'callback', 'callback'=>array(__CLASS__, 'default_date_now')));
				}
					
				break;
			default:
				if(substr($meta['DATA_TYPE'],0,4)=='enum')
				{
					$this->setColumnType($column, self::type_enum);
					preg_match('/enum\((.+)\)/i', $meta['DATA_TYPE'], $matches);
					$list=array();
					foreach(explode(',', $matches[1]) as $item)
					{
						$item=eval("return $item;");
						$list[$item]=$item;
					}
					$this->setColumnEnum($column, $list);
				}
				else
				{
					$this->setColumnType($column, self::type_unknown);
					trigger_error("{$meta['DATA_TYPE']}: unsupported type for column $column");
				}
		}
	}

	protected function _analyzeMetaPdoOci($i, $column, &$meta)
	{
		$this->setColumnOrder($column, $i);
		if($meta['DEFAULT'])
		{
			$this->setColumnDefaulta($column, $meta['DEFAULT']);
		}
		switch($meta['DATA_TYPE'])
		{
			case 'INT': case 'INTEGER': case 'SMALLINT':
				$this->setColumnType($column, self::type_integer);
				break;
			case 'FLOAT': case 'NUMBER': case 'DOUBLE PRECISION':
				$this->setColumnType($column, self::type_float);
				break;
			case 'DECIMAL': case 'DEC':
				$this->setColumnType($column, self::type_float);
				$this->setColumnNumberDecimals($column, $meta['SCALE']);
				break;
			case 'VARCHAR': case 'VARCHAR2': case 'CHAR':
				$this->setColumnType($column, self::type_string);
				$this->setColumnStringLength($column, $meta['LENGTH']);
				break;
			case 'CLOB': case 'LONG':
				$this->setColumnType($column, self::type_text);
				$this->setColumnLobType($column, 'stream');
				switch($meta['DATA_TYPE'])
				{
					case 'LONG':
						$this->setColumnStringLength($column4294967296/2);
						break;
					case 'CLOB':
						$this->setColumnStringLength($column, 4294967296);
						break;
				}
				break;
			case 'DATE': case 'DATETIME': case 'TIMESTAMP':
				switch($meta['DATA_TYPE'])
				{
					case 'DATE':
						$this->setColumnType($column, self::type_date);
						$this->setColumnDateDbformat($column, $this->_defaultDateDbformat);
						break;
					default:
						$this->setColumnType($column, self::type_datetime);
						$this->setColumnDateDbformat($column, $this->_defaultDatetimeDbformat);
				}
					
				break;
			case 'BLOB':
				$this->setColumnType($column, self::type_binary);
				$this->setColumnLobType($column, 'stream');
				break;
			default:
				$this->setColumnType($column, self::type_unknown);
				trigger_error("{$meta['DATA_TYPE']}: unsupported type for column $column");
		}
	}
	
	protected function _analyzeMetaOracle($i, $column, &$meta)
	{
		$this->setColumnOrder($column, $i);
		if($meta['DEFAULT'])
		{
			$this->setColumnDefault($column, $meta['DEFAULT']);
		}
		switch($meta['DATA_TYPE'])
		{
			case 'INT': case 'INTEGER': case 'SMALLINT':
				$this->setColumnType($column, self::type_integer);
				break;
			case 'FLOAT': case 'NUMBER': case 'DOUBLE PRECISION':
				$this->setColumnType($column, self::type_float);
				break;
			case 'DECIMAL': case 'DEC':
				$this->setColumnType($column, self::type_float);
				$this->setColumnNumberDecimals($column, $meta['SCALE']);
				break;
			case 'VARCHAR': case 'VARCHAR2': case 'CHAR':
				$this->setColumnType($column, self::type_string);
				$this->setColumnStringLength($column, $meta['LENGTH']);
				break;
			case 'CLOB': case 'LONG':
				$this->setColumnType($column, self::type_text);
				$this->setColumnLobType($column, 'ocilob');
				$this->setColumnLobLoad($column, $this->_defaultLobLoad);
				switch($meta['DATA_TYPE'])
				{
					case 'LONG':
						$this->setColumnStringLength($column, 4294967296/2);
						break;
					case 'CLOB':
						$this->setColumnStringLength($column, 4294967296);
						break;
				}
				break;
			case 'DATE': case 'DATETIME': case 'TIMESTAMP':
				switch($meta['DATA_TYPE'])
				{
					case 'DATE':
						$this->setColumnType($column, self::type_date);
						$this->setColumnDateDbformat($column, $this->_defaultDateDbformat);
						break;
					default:
						$this->setColumnType($column, self::type_datetime);
						$this->setColumnDateDbformat($column, $this->_defaultDatetimeDbformat);
				}
					
				break;
			case 'BLOB':
				$this->setColumnType($column, self::type_binary);
				$this->setColumnLobType($column, 'ocilob');
				$this->setColumnLobLoad($column, $this->_defaultLobLoad);
				break;
			default:
				$this->setColumnType($column, self::type_unknown);
				trigger_error("{$meta['DATA_TYPE']}: unsupported type for column $column");
		}
	}
	
	protected function _analyzeMetaPdoUnsupported($i, $column, &$meta)
	{
		$this->setColumnOrder($column, $i);
		$this->setColumnDisplay($column, true);
		if($meta['DEFAULT'])
		{
			$this->setColumnDefault($column, $meta['DEFAULT']);
		}
		switch($meta['DATA_TYPE'])
		{
			default:
				$this->setColumnType($column, self::type_unknown);
				trigger_error("{$meta['DATA_TYPE']}: unsupported type for column $column");
		}
	}
}
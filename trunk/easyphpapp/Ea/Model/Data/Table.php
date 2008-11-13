<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Data
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.0.20081105
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Model/Data/Table/Exception.php';
require_once 'Ea/Model/Data/Abstract.php';
require_once 'Zend/Db/Table/Abstract.php';
require_once 'Zend/Db/Adapter/Pdo/Mysql.php';

/**
 * Table Data model class.
 * Zend_Db_Table data object model.
 * 
 */
class Ea_Model_Data_Table extends Ea_Model_Data_Abstract
{
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
			$i=0;
			foreach($info['cols'] as $column)
			{
				$meta=$info['metadata'][$column];
				$this->setColumnOrder($column, $i);
				$this->setColumnLabel($column, ucfirst($column));
				$this->setColumnDisplay($column, true);
				if($meta['DEFAULT'])
				{
					$this->setColumnMeta($column, 'default', $meta['DEFAULT']);
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
						$this->setColumnMeta($column, 'number', array('decimals' => $meta['SCALE']));
						break;
					case 'varchar': case 'char':
						$this->setColumnType($column, self::type_string);
						$this->setColumnMeta($column, 'string', array('length' => $meta['LENGTH']));
						break;
					case 'text': case 'tinytext': case 'mediumtext': case 'longtext':
						$this->setColumnType($column, self::type_text);
						switch($meta['DATA_TYPE'])
						{
							case 'tinytext':
								$this->setColumnMeta($column, 'string', array('length' => 256));
								break;
							case 'text':
								$this->setColumnMeta($column, 'string', array('length' => 65536));
								break;
							case 'mediumtext':
								$this->setColumnMeta($column, 'string', array('length' => 16777216));
								break;
							case 'longtext':
								$this->setColumnMeta($column, 'string', array('length' => 4294967296));
								break;
						}
						break;
					case 'date': case 'datetime': case 'timestamp':
						switch($meta['DATA_TYPE'])
						{
							case 'date':
								$this->setColumnMeta($column, 'date', array('format' => '%Y-%m-%d'));
								break;
							default:
								$this->setColumnMeta($column, 'date', array('format' => '%Y-%m-%d %H:%M:%S'));
						}
						if($meta['DEFAULT']=='CURRENT_TIMESTAMP')
						{
							$this->setColumnMeta($column, 'default', array('type'=>'callback', 'callback'=>array(__CLASS__, 'default_date_now')));
						}
						$this->setColumnType($column, self::type_date);
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
							$this->setColumnMeta($column, 'enum', $list);
						}
						else
						{
							$this->setColumnType($column, self::type_unknown);
							trigger_error("{$meta['DATA_TYPE']}: unsupported type");
						}
				}
				$i++;
			}
			$this->_ordered=true;
		}
		else
		{
			trigger_error("Unsupported adapter for meta analyze");
		}
	}
	
	public static function default_date_now()
	{
		return time();
	}
}
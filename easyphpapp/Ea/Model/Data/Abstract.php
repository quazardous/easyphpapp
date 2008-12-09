<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Data
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.2-20081209
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Model/Abstract.php';
require_once 'Zend/Db/Adapter/Pdo/Mysql.php';
require_once 'Zend/Db/Adapter/Pdo/Oci.php';

/**
 * Data model class.
 * The base class to represent/access data object model.
 * 
 */
abstract class Ea_Model_Data_Abstract extends Ea_Model_Abstract
{

	const type_string      = 'string';
	const type_text        = 'text';
	const type_integer     = 'integer';
	const type_float       = 'float';
	const type_date        = 'date';
	const type_datetime    = 'datetime';
	const type_boolean     = 'boolean';
	const type_enum        = 'enum';
	const type_binary      = 'binary';
	const type_unknown     = 'unknown';
	
	/**
	 * Meta info on columns.
	 * array(
	 *   'column name'=>array(
	 *   	'type'          => 'string|text|integer|float|date|datetime|boolean|enum|binary',
	 *   	'date'          => array('format'=>'strptime() format to read from base', 'outformat'=>'strftime() format to read/write from base'),
	 *      'boolean'       => array('value true'=>true, 'value false'=>false),
	 *      'number'        => array('decimals'=>2, 'dec_point'=>',', 'thousands_sep'=>' '),
	 *      'string'        => array('length'=>32), // for string or text
	 *      'lob'		    => array( //for text and binary
	 *      					'type'=>'string|stream|ocilob', // physical type
	 *      					'load'=>true|false, // model can try to get value
	 *      					'lentgh'=>$maxlength,
	 *      					) 
	 *      'enum'          => array('value'=>'option', ...),
	 *      'order'		    => $i, // display order
	 *      'label'		    => 'nice label',
	 *      'filter'		=> $callback, // filter callback used in Ea_Model_Layout::filterRecordValue()
	 *   ),
	 * )
	 * 
	 * @var array
	 */
	//protected $_metadata=array();
	
	public function getColumnType($name)
	{
		return $this->getMetaData($name, 'type');
	}
	
	public function setColumnType($name, $type)
	{
		$this->setColumnMeta($name, 'type', $type);
	}

	public function getColumnStringLength($name)
	{
		$length=$this->getMetaData($name, 'string', 'length');
		if(!$length) $length=32;
		return $length;
	}
	
	public function setColumnStringLength($name, $length)
	{
		$this->setColumnMetaPart($name, 'string', 'length', $length);
	}

	public function getColumnLobType($name)
	{
		return $this->getMetaData($name, 'lob','type');
	}
	
	public function setColumnLobType($name, $type)
	{
		$this->setColumnMetaPart($name, 'lob', 'type', $type);
	}

	public function getColumnLobLength($name)
	{
		$length=$this->getMetaData($name, 'lob', 'length');
		if(!$length) $length=32;
		return $length;
	}
	
	public function setColumnLobLength($name, $length)
	{
		$this->setColumnMetaPart($name, 'lob', 'length', $length);
	}
		
	public function getColumnLobLoad($name)
	{
		return $this->getMetaData($name, 'lob','load');
	}
	
	public function setColumnLobLoad($name, $load)
	{
		$this->setColumnMetaPart($name, 'lob', 'load', $load);
	}
	
	public function getColumnNumberDecimals($name)
	{
		return $this->getMetaData($name, 'number', 'decimals');
	}
	
	public function setColumnNumberDecimals($name, $decimals)
	{
		$this->setColumnMetaPart($name, 'number', 'decimals', $decimals);
	}

	public function getColumnEnum($name)
	{
		$enum=$this->getMetaData($name, 'enum');
		if(!$enum)$enum=array();
		return $enum;
	}
	
	public function setColumnEnum($name, $enum)
	{
		$this->setColumnMeta($name, 'enum', $enum);
	}

	public function setColumnDateFormat($name, $format, $outformat=null)
	{
		if($outformat===null) $outformat=$format;
		$this->setColumnMetaPart($name, 'date', 'format', $format);
		$this->setColumnMetaPart($name, 'date', 'outformat', $outformat);
	}

	public function getColumnDateFormat($name)
	{
		return $this->getMetaData($name, 'date', 'format');
	} 

	public function getColumnDateOutformat($name)
	{
		return $this->getMetaData($name, 'date', 'outformat');
	} 
	
	/**
	 * Return the list of columns with given type.
	 * 
	 * @param string|array(string) $type
	 * @return array
	 */
	public function getColumnsOfType($type)
	{
		if(!is_array($type)) $type=array($type);
		$res=array();
		foreach($this->getColumns() as $column)
		{
			if(in_array($this->getColumnType($column),$type))
			{
				$res[]=$column;
			}
		}
		return $res;
	}
	
	protected $_defaultLobLoad=true;
	protected $_defaultDbDateFormat='%Y-%m-%d';
	protected $_defaultDbDatetimeFormat='%Y-%m-%d %H:%M:%S';
	
	protected function initOracleDbDateFormat()
	{
		$query='SELECT value FROM V$NLS_Parameters WHERE parameter =\'NLS_DATE_FORMAT\'';
		$stmt=$this->_dbTable->getAdapter()->query($query);
		$dbDateFormat=$stmt->fetchColumn(0);
		$this->_defaultDbDateFormat=self::format_date_oracle_to_php($dbDateFormat);
		$this->_defaultDbDatetimeFormat=self::format_date_oracle_to_php($dbDateFormat);
	}
	
	public static function default_date_now()
	{
		return time();
	}

	static protected function format_date_oracle_to_php($format)
	{
		return strtr($format,
			array(
				'YYYY' => '%Y',
				'RR'   => '%y',
				'DD'   => '%d',
				'MON'  => '%b',
				'MM'   => '%m',
				'HH24' => '%H',
				'MI'   => '%M',
				'SS'   => '%S',
			)
		);
	}	

	/**
	 * Apply special filter on record values.
	 * 
	 * @param string $column
	 * @param string $value
	 * @return string
	 * 
	 * @see Ea_Layout_Record_Adapter_Field::getValue()
	 */
	public function filterRecordValue($column, $value)
	{
		$filter=$this->getMetaData($column, 'filter');
		if($filter)
		{
			return call_user_func($filter, $value);
		}
		else
		{
			switch($this->getColumnLobType($column))
			{
				case 'stream':
					if(is_resource($value)&&$this->getColumnLobLoad($column))
					{
						return stream_get_contents($value);
					}
					return $value;
				case 'ocilob':
					if(is_a($value, 'OCI-Lob')&&$this->getColumnLobLoad($column))
					{
						return $value->load();
					}
					return $value;
				default: return $value;
			}
		}
	}
	
}
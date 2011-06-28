<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Data
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.1-20091201
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
	 *   	'date'          => array('dbformat'=>'strptime() format to read from base', 'format'=>'strftime() format to read/write from base'),
	 *      'boolean'       => array('value'=>'value for true'),
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
	 *      'mandatory'		=> true|false,
	 *   ),
	 * )
	 * 
	 * @var array
	 */
	//protected $_metadata=array();
	
	/*
	public function getColumnStringLength($column)
	{
		$length=$this->getMetaData($column, 'string', 'length');
		if(!$length) $length=32;
		return $length;
	}

	public function getColumnLobLength($column)
	{
		$length=$this->getMetaData($name, 'lob', 'length');
		if(!$length) $length=32;
		return $length;
	}
	*/
	
	public function getColumnEnum($column)
	{
		$enum=$this->getMetaData($column, 'enum');
		if(!$enum)$enum=array();
		return $enum;
	}
	
	public function setColumnDateDbformat($column, $dbformat, $format=null)
	{
		if($format===null) $format=$dbformat;
		$this->setColumnMetaPart($column, 'date', 'dbformat', $dbformat);
		$this->setColumnMetaPart($column, 'date', 'format', $format);
	}
		
	protected $_defaultLobLoad=true;
	protected $_defaultDateDbformat='%Y-%m-%d';
	protected $_defaultDatetimeDbformat='%Y-%m-%d %H:%M:%S';
	
	// TODO : qu'est ça fout là ?
	protected function initOracleDbDateFormat()
	{
		$query='SELECT value FROM V$NLS_Parameters WHERE parameter =\'NLS_DATE_FORMAT\'';
		$stmt=$this->_dbTable->getAdapter()->query($query);
		$dbDateFormat=$stmt->fetchColumn(0);
		$this->_defaultDateDbformat=self::format_date_oracle_to_php($dbDateFormat);
		$this->_defaultDatetimeDbformat=self::format_date_oracle_to_php($dbDateFormat);
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
	 * @param mixed $data
	 * @return string
	 * 
	 * @see Ea_Layout_Record_Adapter_Field::getRawValue()
	 */
	public function filterRecordValue($column, $value, $data=null)
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

	/*
	protected function setColumnMetaFromXml($column, Ea_Xml_Element $xml_metadata)
	{
		switch(strtolower($xml_metadata['name']->toString()))
		{
			case 'lob/load':
				$this->setColumnLobLoad($column, strtolower($xml_metadata->toString())=='true'?true:false);
			break;
			case 'enum':
				$options=array();
				foreach($xml_metadata->option as $xml_option)
				{
					if(isset($xml_option['value']))
					{
						$options[$xml_option['value']->toString()]=$xml_option->toString();
					}
					else
					{
						$options[$xml_option->toString()]=$xml_option->toString();
					}
				}
				$this->setColumnEnum($column, $options);
			break;
			default: parent::setColumnMetaFromXml($column, $xml_metadata);
		}
	}
	*/
	
}
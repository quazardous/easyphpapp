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

require_once 'Ea/Model/Data/Abstract.php';
require_once 'Zend/Db/Adapter/Pdo/Mysql.php';
require_once 'Zend/Db/Adapter/Pdo/Oci.php';

/**
 * Abstract db data model class.
 * 
 */
abstract class Ea_Model_Data_Db extends Ea_Model_Data_Abstract
{

	/**
	 * Meta info on columns.
	 * array(
	 *   'column name'=>array(
	 *   	'type'          => 'string|text|integer|float|date|datetime|boolean|enum|binary',
	 *      ...
	 *   ),
	 * )
	 * 
	 * @var array
	 */
	//protected $_metadata=array();
	
	
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
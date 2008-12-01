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
	 *      'filter'		    => $callback,
	 *   ),
	 * )
	 * 
	 * @var array
	 * @see Ea_Model_Abstract::$_metadata
	 */
	//protected $_metadata=null;
	
	protected $_defaultDbDateFormat='%Y-%m-%d';
	protected $_defaultDbDatetimeFormat='%Y-%m-%d %H:%M:%S';
	
	/*
	public function setColumnType($name, $type)
	{
		parent::setColumnType($name, $type);
		switch($type)
		{
			case self::type_date:
				if(!$this->getMetaData($name, 'date', 'format'))
				{
					$this->setColumnMetaPart($name, 'date', 'format', $this->_defaultDbDateFormat);
				}
				if(!$this->getMetaData($name, 'date', 'outformat'))
				{
					$this->setColumnMetaPart($name, 'date', 'outformat', $this->_defaultDbDateFormat);
				}
				break;
			case self::type_datetime:
				if(!$this->getMetaData($name, 'date', 'format'))
				{
					$this->setColumnMetaPart($name, 'date', 'format', $this->_defaultDbDatetimeFormat);
				}
				if(!$this->getMetaData($name, 'date', 'outformat'))
				{
					$this->setColumnMetaPart($name, 'date', 'outformat', $this->_defaultDbDatetimeFormat);
				}
				break;
		}
	}
	*/
	
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
	
}
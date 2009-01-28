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

require_once 'Ea/Model/Data/Table.php';
require_once 'Ea/Model/Data/Select.php';
require_once 'Ea/Model/Data/Record.php';
require_once 'Zend/Db/Statement.php';
require_once 'Zend/Db/Select.php';

/**
 * Factory data model class.
 * 
 */
class Ea_Model_Data
{
	
	/**
	 * Factory.
	 * Can handle known data model.
	 * 
	 * @param $config
	 * @return Ea_Model_Data_Abstract
	 */
	static public function factory($config=null)
	{
		if($config instanceof Ea_Model_Data_Abstract)
		{
			return $config;
		}
		if($config instanceof Zend_Db_Select||$config instanceof Zend_Db_Statement||is_string($config))
		{
			return new Ea_Model_Data_Select($config);
		}
		if($config instanceof Zend_Db_Table_Abstract)
		{
			return new Ea_Model_Data_Table($config);
		}
		if($config instanceof Iterator||is_array($config))
		{
			return new Ea_Model_Data_Record($config);
		}
		return null;
	}
		
}
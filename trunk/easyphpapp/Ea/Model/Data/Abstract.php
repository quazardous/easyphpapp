<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Data
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.0.20081106
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Model/Abstract.php';

/**
 * Data model class.
 * The base class to represent/access data object model.
 * 
 */
abstract class Ea_Model_Data_Abstract extends Ea_Model_Abstract
{
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
	
}
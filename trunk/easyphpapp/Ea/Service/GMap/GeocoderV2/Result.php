<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Service
 * @subpackage  GMap
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.2-20091223
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Service/GMap/Point.php';
require_once 'Ea/Service/GMap/GeocoderV2/ResultSet.php';

class Ea_Service_GMap_GeocoderV2_Result extends Ea_Service_GMap_Point
{
	/**
	 * The parent result set.
	 * 
	 * @var Ea_Service_GMap_GeocoderV2_ResultSet
	 */
	protected $_resultSet=null;
	protected $_i=null;
	
	public function __construct(Ea_Service_GMap_GeocoderV2_ResultSet $resultSet, $i)
	{
		$this->_resultSet=$resultSet;
		$this->_i=$i;
		parent::__construct();
	}

	public function getPlacemark()
	{
		return $this->_resultSet->getPlacemark($this->_i);
	}
	
	public function getLat()
	{
		return (float)$this->getPlacemark()->Point->coordinates[1];
	}
	
	public function getLng()
	{
		return (float)$this->getPlacemark()->Point->coordinates[0];
	}	
	
	public function setLat($lat)
	{
		throw new Ea_Service_GMap_GeocoderV2_Exception("setLat() is not allowed");
	}
	
	public function setLng($lng)
	{
		throw new Ea_Service_GMap_GeocoderV2_Exception("setLng() is not allowed");
	}
	
	public function getId()
	{
		//FIXME I don't remember what todo here ...
	}
}
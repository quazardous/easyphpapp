<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Service
 * @subpackage  GMap
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Service/GMap/Point.php';

class Ea_Service_GMap_GeocoderV3_Result extends Ea_Service_GMap_Point
{
	/**
	 * The parent result set.
	 * 
	 * @var Ea_Service_GMap_GeocoderV3_ResultSet
	 */
	protected $_resultSet=null;
	protected $_i=null;
	
	public function __construct(Ea_Service_GMap_GeocoderV3_ResultSet $resultSet, $i)
	{
		$this->_resultSet=$resultSet;
		$this->_i=$i;
		parent::__construct();
	}

	public function getResult()
	{
		return $this->_resultSet->getResult($this->_i);
	}
	
	public function getLat()
	{
		return (float)$this->getResult()->geometry->location->lat;
	}
	
	public function getLng()
	{
		return (float)$this->getResult()->geometry->location->lng;
	}	
	
	public function setLat($lat)
	{
		require_once 'Ea/Service/GMap/GeocoderV3/Exception.php';
		throw new Ea_Service_GMap_GeocoderV3_Exception("setLat() is not allowed");
	}
	
	public function setLng($lng)
	{
		require_once 'Ea/Service/GMap/GeocoderV3/Exception.php';
		throw new Ea_Service_GMap_GeocoderV3_Exception("setLng() is not allowed");
	}
	
	/*
	public function getId()
	{
		//FIXME I don't remember what todo here ...
	}*/
}
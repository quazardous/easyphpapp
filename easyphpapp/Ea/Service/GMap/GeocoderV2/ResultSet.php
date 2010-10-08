<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Service
 * @subpackage  GMap
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.6-20101007
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

class Ea_Service_GMap_GeocoderV2_ResultSet implements ArrayAccess, Iterator, Countable
{
	protected $_json=null;
	protected $_points=array();
	
	public function __construct($data=null)
	{
		$this->setFromJSON($data);
	}
	
	/**
	 * Set from Google Geocoder JSON format.
	 * 
	 * @param json $json
	 */
	public function setFromJSON($json)
	{
		$this->_json=$json;
		if($this->isSuccess())
		{
			require_once 'Ea/Service/GMap/GeocoderV2/Result.php';
			$this->_points=array();
			foreach($this->_json->Placemark as $i=>$placemark)
			{
				$this->_points[]=new Ea_Service_GMap_GeocoderV2_Result($this, $i);
			}
		}
	}
	
	/**
	 * Return the Google Map Geocoder Status code.
	 * @see http://code.google.com/intl/fr-FR/apis/maps/documentation/reference.html#GGeoStatusCode
	 * 
	 * @return int
	 */
	public function getStatusCode()
	{
		if($this->_json) return $this->_json->Status->code;
		return null;
	}
	
	/**
	 * Return true if request is successfull.
	 * 
	 * @return boolean
	 */
	public function isSuccess()
	{
		return $this->getStatusCode()==200;
	}
	
	/**
	 * Return placemark info.
	 * 
	 * @param int $i index of result il multiple
	 * @return object info
	 */
	public function getPlacemark($i=0)
	{
		if(!isset($this->_json->Placemark[$i])) return null;
		return $this->_json->Placemark[$i];
	}
	
	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->_points);
	}
	
 	public function offsetGet($offset)
 	{
 		if(!array_key_exists($offset, $this->_points))
 		{
 			require_once 'Ea/Service/GMap/GeocoderV2/Exception.php';
 			throw new Ea_Service_GMap_GeocoderV2_Exception("'$offset' offset does not exist");
 		}
 		return $this->_points[$offset];
 	}
 	
 	public function offsetSet($offset, $value)
 	{
 		require_once 'Ea/Service/GMap/GeocoderV2/Exception.php';
		throw new Ea_Service_GMap_GeocoderV2_Exception("offsetSet() is not allowed");
 	}
 	
 	public function offsetUnset($offset)
 	{
 		unset($this->_points[$offset]);
 	}
 	
 	public function current()
 	{
 		return current($this->_points);
  	}
 	
 	public function key()
 	{
 		return key($this->_points);
 	}
 	
 	public function next()
 	{
 		return next($this->_points);
 	}
 	
 	public function rewind()
 	{
 		return reset($this->_points);
 	}
 	
 	public function valid()
 	{
 		return $this->current()!==false;
 	}
 	
 	public function count()
 	{
 		return count($this->_points);
 	}
 	
	
}
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

/**
 * Google map geocoder v3 service.
 * @see http://code.google.com/intl/fr/apis/maps/documentation/geocoding/
 *
 */
class Ea_Service_GMap_GeocoderV3_ResultSet implements ArrayAccess, Iterator, Countable
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
			$this->_points=array();
			require_once 'Ea/Service/GMap/GeocoderV3/Result.php';
			foreach($this->_json->results as $i=>$result)
			{
				$this->_points[]=new Ea_Service_GMap_GeocoderV3_Result($this, $i);
			}
		}
	}
	
	/**
	 * Return the Google Map Geocoder Status code.
	 * @see http://code.google.com/intl/fr/apis/maps/documentation/geocoding/#StatusCodes
	 * 
	 * @return int
	 */
	public function getStatus()
	{
		if($this->_json) return $this->_json->status;
		return null;
	}
	
	/**
	 * Return true if request is successfull.
	 * 
	 * @return boolean
	 */
	public function isSuccess()
	{
		return $this->getStatus()=='OK';
	}
	
	/**
	 * Return results info.
	 * 
	 * @param int $i index of result (if multiple)
	 * @return object info
	 */
	public function getResult($i=0)
	{
		if(!isset($this->_json->results[$i])) return null;
		return $this->_json->results[$i];
	}
	
	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->_points);
	}
	
 	public function offsetGet($offset)
 	{
 		if(!array_key_exists($offset, $this->_points))
 		{
 			require_once 'Ea/Service/GMap/GeocoderV3/Exception.php';
 			throw new Ea_Service_GMap_GeocoderV3_Exception("'$offset' offset does not exist");
 		}
 		return $this->_points[$offset];
 	}
 	
 	public function offsetSet($offset, $value)
 	{
 		require_once 'Ea/Service/GMap/GeocoderV3/Exception.php';
		throw new Ea_Service_GMap_GeocoderV3_Exception("offsetSet() is not allowed");
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
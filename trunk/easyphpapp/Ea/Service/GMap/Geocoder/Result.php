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

class Ea_Service_GMap_Geocoder_Result extends Ea_Service_GMap_Point
{
	protected $_json=null;
	
	public function __construct($data=null)
	{
		parent::__construct();
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
		$this->setLat($json->Placemark[0]->Point->coordinates[1]);
		$this->setLng($json->Placemark[0]->Point->coordinates[0]);
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
	
	/**
	 * Return the number of matching results.
	 * 
	 * @return boolean|number
	 */
	public function getNbMatchingResults()
	{
		if(!isset($this->_json)) return false;
		return count($this->_json->Placemark);
	}
	
}
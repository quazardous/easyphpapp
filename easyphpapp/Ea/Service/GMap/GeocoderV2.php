<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Service
 * @subpackage  GMap
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.2-20091222
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Service/Abstract.php';
require_once 'Ea/Service/GMap/GeocoderV2/ResultSet.php';

/**
 * Google Map Geocoder service.
 * You will need a Google Map Api Key to use it.
 * 
 */
class Ea_Service_GMap_GeocoderV2 extends Ea_Service_Abstract
{
	static protected $_baseUri='http://maps.google.com/maps/geo';
	
	static protected $_apiKey=null;
	
	static public function setApiKey($key)
	{
		self::$_apiKey=$key;
	}
	
	/**
	 * Geocode given address with Google Geocoder.
	 * 
	 * @param string $string
	 * @return Ea_Service_GMap_GeocoderV2_Result
	 */
	public function geocode($string)
	{
		$ret=$this->request(self::$_baseUri, array(
			'output'=>'json',
			'sensor'=>'false',
			'key'=>self::$_apiKey,
			'q'=>$string,
		));
		if(!$ret) return false;
		return new Ea_Service_GMap_GeocoderV2_ResultSet($this->getHttpResponseJSON());
	}
}
<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Service
 * @subpackage  GMap
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id:$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Service/Abstract.php';

/**
 * Google Map Geocoder service.
 * You will need a Google Map Api Key to use it.
 * @deprecated
 * @see Ea_Service_GMap_GeocoderV3
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
		require_once 'Ea/Service/GMap/GeocoderV2/ResultSet.php';
		return new Ea_Service_GMap_GeocoderV2_ResultSet($this->getHttpResponseJSON());
	}
}
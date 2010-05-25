<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Service
 * @subpackage  GMap
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.5-20100524
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Service/Abstract.php';
require_once 'Ea/Service/GMap/Point.php';
require_once 'Ea/Service/GMap/GeocoderV3/ResultSet.php';

/**
 * Google Map Geocoder service.
 * You will need a Google Map Api Key to use it.
 * 
 */
class Ea_Service_GMap_GeocoderV3 extends Ea_Service_Abstract
{
	public function __construct($language=null, $region=null)
	{
		$this->setLanguage($language);
		$this->setRegion($region);
	}
	
	static protected $_baseUri='http://maps.google.com/maps/api/geocode/json';
	
	/**
	 * The bounding box of the viewport within which to bias geocode results more prominently.
	 * @var unknown_type
	 * @see http://code.google.com/intl/fr/apis/maps/documentation/geocoding/#Viewports
	 */
	protected $_bounds=null;
	
	/**
	 * The language in which to return results.
	 * @var string
	 */
	protected $_language=null;
	
	public function setLanguage($language)
	{
		$this->_language=$language;
	}

	/**
	 * The region codes.
	 * @var string
	 */
	protected $_region=null;
	
	public function setRegion($region)
	{
		$this->_region=$region;
	}
	
	/**
	 * Geocode given address with Google Geocoder.
	 * 
	 * @param string $address
	 * @return Ea_Service_GMap_GeocoderV3_ResultSet
	 */
	public function geocode($address)
	{
		$ret=$this->request(self::$_baseUri, array(
			'output'=>'json',
			'sensor'=>'false',
			'language'=>$this->_language,
			'region'=>$this->_region,
			'address'=>$address,
		));
		if(!$ret) return false;
		return new Ea_Service_GMap_GeocoderV3_ResultSet($this->getHttpResponseJSON());
	}
	
	/**
	 * Reverse geocode the given point with Google Geocoder.
	 * 
	 * @param float|array|Ea_Service_GMap_Point $pointOrLat
	 * @param float $lng
	 * @return Ea_Service_GMap_GeocoderV3_ResultSet
	 * http://code.google.com/intl/fr/apis/maps/documentation/geocoding/#ReverseGeocoding
	 */
	public function reverseGeocode($pointOrLat, $lng=null)
	{
		if($pointOrLat instanceof Ea_Service_GMap_Point)
		{
			$point=$pointOrLat;
		}
		else
		{
			$point=new Ea_Service_GMap_Point($pointOrLat, $lng);
		}
		$lat=$point->getLat();
		$lng=$point->getLng();

		$ret=$this->request(self::$_baseUri, array(
			'output'=>'json',
			'sensor'=>'false',
			'language'=>$this->_language,
			'region'=>$this->_region,
			'latlng'=>sprintf("%f,%f", $lat, $lng),
		));
		if(!$ret) return false;
		return new Ea_Service_GMap_GeocoderV3_ResultSet($this->getHttpResponseJSON());
	}
	
}
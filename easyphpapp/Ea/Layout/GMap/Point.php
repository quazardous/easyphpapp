<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  GMap
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.2-20091222
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

/**
 * GMap for Gmap.
 * 
 * @author berlioz
 *
 */
class Ea_Layout_GMap_Point
{
	protected $_lat=0;
	protected $_lng=0;
	
	public function __construct($lat=0, $lng=0)
	{
		$this->setLat($lat);
		$this->setLng($lng);
	}
	
	public function setLat($lat)
	{
		$this->_lat=$lat;
	}
	
	public function getLat()
	{
		return $this->_lat;
	}

	public function setLng($lng)
	{
		$this->_lng=$lng;
	}
	
	public function getLng()
	{
		return $this->_lng;
	}
	
	public function getJS()
	{
		return 'new google.maps.LatLng('.$this->getLat().','.$this->getLng().')';
	}
}

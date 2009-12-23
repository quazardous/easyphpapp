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

class Ea_Service_GMap_Point
{
	protected $_lat=null;
	protected $_lng=null;
	
	/**
	 * Point constructor.
	 * 
	 * @param float|Ea_Service_GMap_Point $lat if it is a point copy it
	 * @param float $lng
	 */
	public function __construct($lat=null, $lng=null)
	{
		if($lat instanceof self)
		{
			$this->copy($lat);
		}
		else
		{
			if($lat!==null)$this->setLat($lat);
			if($lng!==null)$this->setLng($lng);
		}
	}
	
	public function copy(self $point)
	{
		$this->setLat($point->getLat());
		$this->setLng($point->getLng());
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
}
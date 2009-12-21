<?php

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

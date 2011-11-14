<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  GMap
 * @author      berlioz [$Author$]
 * @version     $Id:$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/GMap/Point.php';
require_once 'Ea/Layout/GMap.php';

/**
 * Marker for GMap.
 * 
 * @author berlioz
 *
 */
class Ea_Layout_GMap_Marker extends Ea_Layout_GMap_Point
{
	
	protected $_title=null;
	
	/**
	 * Get  Title.
	 *
	 * @return type title
	 */
	public function getTitle() 
	{
		return $this->_title;
	}
	
	/**
	 * Set Title.
	 * 
	 * @param type title
	 */
	public function setTitle($title)
	{
		$this->_title = $title;
	}
	
	public function __construct($lat=null, $lng=null, $title=null)
	{
		parent::__construct($lat, $lng);
		if($title!==null)$this->setTitle($title);
	}
	
	/**
	 * Map object.
	 * 
	 * @var Ea_Layout_GMap
	 */
	protected $_map=null;
	
	/**
	 * Set Map object.
	 * 
	 * @param Ea_Layout_GMap $map
	 */
	public function setMap(Ea_Layout_GMap $map)
	{
		$this->_map=$map;
	}
	
	/**
	 * Get Map object.
	 * 
	 * @return Ea_Layout_GMap
	 */
	public function getMap()
	{
		return $this->_map;
	}
	
	public function getJS()
	{
		$script='new google.maps.Marker({
      		position: '.parent::getJS().', 
      		map: '.$this->getMap()->getJSVar().', 
      		title: "'.addcslashes($this->getTitle(), '"').'"';
		foreach($this->_options as $option)
		{
			$value=$option->value;
			if($option->quote)
			{
				$value='"'.addcslashes($value, '"').'"';
			}
			$script.=', '.$option->name.': '.$value;
		}
  		$script.='});';
  		return $script;
	}
	
	protected $_options=array();
	
	public function setOption($name, $value, $quote=false)
	{
		switch((strtolower($name)))
		{
			case 'title': $this->setTitle($name); break;
			case 'position': case 'map':
			    require_once 'Ea/Layout/GMap/Exception.php';
				throw new Ea_Layout_GMap_Exception("$name : option not allowed !");
			default:
				$this->_options[]=(object)array(
					'name'=>$name,
					'value'=>$value,
					'quote'=>$quote,
				);
		}
	}
	
} 
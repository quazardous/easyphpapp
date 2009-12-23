<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.2-20091221
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Container.php';
require_once 'Ea/Layout/GMap/Exception.php';
require_once 'Ea/Layout/GMap/Point.php';
require_once 'Ea/Layout/GMap/Marker.php';

/**
 * Google map layout class.
 * Based on Google Map v3 beta api.
 */
class Ea_Layout_GMap extends Ea_Layout_Container
{
	protected $_tag='div';
	static protected $_autoId=0;

	/**
	 * Center of the map.
	 * 
	 * @var Ea_Layout_GMap_Point
	 */
	protected $_center;
	
	/**
	 * Set the center of the map.
	 * 
	 * @param Ea_Layout_GMap_Point|float $lat
	 * @param float $lng
	 */
	public function setCenter($point)
	{
		if($point instanceof Ea_Layout_GMap_Point)
		{
			$this->_center=clone $point;
		}
		else if($point instanceof Ea_Service_GMap_Point)
		{
			$this->_center=new Ea_Layout_GMap_Point($point->getLat(), $point->getLng());
		}
		else if(is_array($point))
		{
			$this->_center=new Ea_Layout_GMap_Point($point[0], $point[1]);
		}
		else if(is_object($point))
		{
			$this->_center=new Ea_Layout_GMap_Point($point->lat, $point->lng);
		}
	}
	
	/**
	 * Get the center of the map.
	 * 
	 * @return Ea_Layout_GMap_Point
	 */
	public function getCenter()
	{
		return $this->_center;
	}
	
	public function getJSCenter()
	{
		return $this->_center->getJS();
	}
	
	protected $_zoom=8;
	
	public function getZoom()
	{
		return $this->_zoom;
	}
	
	public function setZoom($zoom)
	{
		$this->_zoom = $zoom;
	}

	public function getJSZoom()
	{
		return $this->_zoom;
	}
	
	const mapType_ROADMAP   = 'ROADMAP';
	const mapType_SATELLITE = 'SATELLITE';
	const mapType_HYBRID    = 'HYBRID';
	const mapType_TERRAIN   = 'TERRAIN';
	protected $_mapType = self::mapType_ROADMAP;
	
	/**
	 * Get  MapType.
	 *
	 * @return string mapType
	 */
	public function getMapType() 
	{
		return $this->_mapType;
	}
	
	/**
	 * Set MapType.
	 * 
	 * @param string mapType
	 */
	public function setMapType($mapType)
	{
		$this->_mapType = $mapType;
	}
	
	public function getJSMapType()
	{
		return 'google.maps.MapTypeId.'.$this->getMapType();
	}
	
	/**
	 * Display navigation control.
	 * 
	 * @var boolean
	 */
	protected $_navigationControl=null;
	
	/**
	 * Get  NavigationControl.
	 *
	 * @return type navigationControl
	 */
	public function getNavigationControl() 
	{
		return $this->_navigationControl;
	}
	
	/**
	 * Set NavigationControl.
	 * 
	 * @param type navigationControl
	 */
	public function setNavigationControl($navigationControl)
	{
		$this->_navigationControl = $navigationControl;
	}
	
	public function getJSNavigationControl()
	{
		return $this->getNavigationControl()?'true':'false';
	}
	
	/**
	 * Display map type control.
	 * 
	 * @var unknown_type
	 */
	protected $_mapTypeControl=null;
	
	/**
	 * Get  MapTypeControl.
	 *
	 * @return type mapTypeControl
	 */
	public function getMapTypeControl() 
	{
		return $this->_mapTypeControl;
	}
	
	/**
	 * Set MapTypeControl.
	 * 
	 * @param type mapTypeControl
	 */
	public function setMapTypeControl($mapTypeControl)
	{
		$this->_mapTypeControl = $mapTypeControl;
	}

	public function getJSMapTypeControl()
	{
		return $this->getMapTypeControl()?'true':'false';
	}
	
	/**
	 * Display scale control.
	 * 
	 * @var unknown_type
	 */
	protected $_scaleControl=null;
	
	/**
	 * Get  ScaleControl.
	 *
	 * @return type scaleControl
	 */
	public function getScaleControl() 
	{
		return $this->_scaleControl;
	}
	
	/**
	 * Set ScaleControl.
	 * 
	 * @param type scaleControl
	 */
	public function setScaleControl($scaleControl)
	{
		$this->_scaleControl = $scaleControl;
	}

	public function getJSScaleControl()
	{
		return $this->getScaleControl()?'true':'false';
	}
	
	/**
	 * Disable default UI.
	 * 
	 * @var boolean
	 */
	protected $_disableDefaultUI=null;
	
	/**
	 * Get  DisableDefaultUI.
	 *
	 * @return type disableDefaultUI
	 */
	public function getDisableDefaultUI() 
	{
		return $this->_disableDefaultUI;
	}
	
	/**
	 * Set DisableDefaultUI.
	 * 
	 * @param type disableDefaultUI
	 */
	public function setDisableDefaultUI($disableDefaultUI)
	{
		$this->_disableDefaultUI = $disableDefaultUI;
	}
	
	public function getJSDisableDefaultUI()
	{
		return $this->getDisableDefaultUI()?'true':'false';
	}
	
	const navigationControlStyle_SMALL = 'SMALL';
	const navigationControlStyle_ZOOM_PAN = 'ZOOM_PAN';
	const navigationControlStyle_ANDROID = 'ANDROID';
	const navigationControlStyle_DEFAULT  = 'DEFAULT ';
	protected $_navigationControlStyle=self::navigationControlStyle_DEFAULT;
	
	/**
	 * Get  NavigationControlStyle.
	 *
	 * @return type navigationControlStyle
	 */
	public function getNavigationControlStyle() 
	{
		return $this->_navigationControlStyle;
	}
	
	/**
	 * Set NavigationControlStyle.
	 * 
	 * @param type navigationControlStyle
	 */
	public function setNavigationControlStyle($navigationControlStyle)
	{
		$this->_navigationControlStyle = $navigationControlStyle;
	}
	
	public function getJSNavigationControlStyle()
	{
		return 'google.maps.NavigationControlStyle.'.$this->getNavigationControlStyle();
	}
	
	const mapTypeControlStyle_HORIZONTAL_BAR='HORIZONTAL_BAR';
	const mapTypeControlStyle_DROPDOWN_MENU='DROPDOWN_MENU';
	const mapTypeControlStyle_DEFAULT ='DEFAULT ';
	protected $_mapTypeControlStyle=self::mapTypeControlStyle_DEFAULT;

	/**
	 * Get  MapTypeControlStyle.
	 *
	 * @return type mapTypeControlStyle
	 */
	public function getMapTypeControlStyle() 
	{
		return $this->_mapTypeControlStyle;
	}
	
	/**
	 * Set MapTypeControlStyle.
	 * 
	 * @param type mapTypeControlStyle
	 */
	public function setMapTypeControlStyle($mapTypeControlStyle)
	{
		$this->_mapTypeControlStyle = $mapTypeControlStyle;
	}

	public function getJSMapTypeControlStyle()
	{
		return 'google.maps.MapTypeControlStyle.'.$this->getMapTypeControlStyle();
	}
	
	const controlPosition_TOP          ='TOP';
	const controlPosition_TOP_LEFT     ='TOP_LEFT';
	const controlPosition_TOP_RIGHT    ='TOP_RIGHT';
	const controlPosition_BOTTOM       ='BOTTOM';
	const controlPosition_BOTTOM_LEFT  ='BOTTOM_LEFT';
	const controlPosition_BOTTOM_RIGHT ='BOTTOM_RIGHT';
	const controlPosition_LEFT         ='LEFT';
	const controlPosition_RIGHT        ='RIGHT';
	
	protected $_mapTypeControlPosition=null;
	
	/**
	 * Get  MapTypeControlPosition.
	 *
	 * @return type $position
	 */
	public function getMapTypeControlPosition() 
	{
		return $this->_mapTypeControlPosition;
	}
	
	/**
	 * Set MapTypeControlPosition.
	 * 
	 * @param type $position
	 */
	public function setMapTypeControlPosition($position)
	{
		$this->_mapTypeControlPosition = $position;
	}
	
	public function getJSMapTypeControlPosition()
	{
		return 'google.maps.ControlPosition.'.$this->getMapTypeControlPosition();
	}

	protected $_navigationControlPosition=null;
	
	/**
	 * Get  NavigationControlPosition.
	 *
	 * @return type $position
	 */
	public function getNavigationControlPosition() 
	{
		return $this->_navigationControlPosition;
	}
	
	/**
	 * Set NavigationControlPosition.
	 * 
	 * @param type $position
	 */
	public function setNavigationControlPosition($position)
	{
		$this->_navigationControlPosition = $position;
	}
	
	public function getJSNavigationControlPosition()
	{
		return 'google.maps.ControlPosition.'.$this->getNavigationControlPosition();
	}

	protected $_scaleControlPosition=null;
	
	/**
	 * Get  ScaleControlPosition.
	 *
	 * @return type $position
	 */
	public function getScaleControlPosition() 
	{
		return $this->_scaleControlPosition;
	}
	
	/**
	 * Set ScaleControlPosition.
	 * 
	 * @param type $position
	 */
	public function setScaleControlPosition($position)
	{
		$this->_scaleControlPosition = $position;
	}
	
	public function getJSScaleControlPosition()
	{
		return 'google.maps.ControlPosition.'.$this->getScaleControlPosition();
	}
	
	/**
	 * Google Map Layout constructor.
	 * 
	 * @param integer $width
	 * @param integer $height
	 * @param string $id
	 * @param array $config
	 */
	public function __construct($width, $height, $id=null, $config=null)
	{
		if($id===null)
		{
			$id='gmap_'.self::$_autoId;
			self::$_autoId++;
		}
		$this->setId($id);
		parent::__construct(null, $config);
		$this->setWidth($width);
		$this->setHeight($height);
		$this->protectedAttribute('width');
		$this->protectedAttribute('height');
		$this->protectedAttribute('id');
		$this->setCenter(array(0 ,0));
	} 
	
	protected $_id=null;
	/**
	 * Set the gmap id.
	 * 
	 * @param string $id
	 */
	public function setId($id)
	{
		$this->_id=$id;
	}

	public function getJSVar()
	{
		return 'eagm_'.preg_replace('/[^a-z0-9_]/i','_',$this->getId());		
	}
	
	public function getId()
	{
		return $this->_id;
	}

	protected $_width=null;
	public function setWidth($width)
	{
		$this->_width=$width;
	}
	
	public function getWidth()
	{
		return $this->_width;
	}
	
	protected $_height=null;
	public function setHeight($height)
	{
		$this->_height=$height;
	}
	
	public function getHeight()
	{
		return $this->_height;
	}
	
	protected function preRender()
	{
		$render=parent::preRender();
		$this->_setAttribute('id', $this->getId());
		$width=$this->getWidth();
		if($width==intval($width)) $width.='px';
		$height=$this->getHeight();
		if($height==intval($height)) $height.='px';
		$this->addAttribute('style', 'width: '.$width.';');
		$this->addAttribute('style', 'height: '.$height.';');
		return $render;
	}	
	
	public function add($content, $append=true)
	{
		throw new Ea_Layout_GMap_Exception('Adding sub layout is not allowed');
	}
	
	public function setPage($page)
	{
		parent::setPage($page);
		if($this->getPage() instanceof Ea_Page)
		{
			// add the google map api v3
			$this->getPage()->addScript('http://maps.google.com/maps/api/js?sensor=false');
			$this->getPage()->setOnloadSupport(true);
		}
	}
	
	protected function render()
	{
		parent::render();
		$script=$this->_initScript;
		$script.='var gmap;';
		$script.='var '.$this->getJSVar().'=gmap=new google.maps.Map(document.getElementById("'.$this->getId().'"), 
{
  zoom: '.$this->getJSZoom().',
  center: '.$this->getJSCenter().',
  mapTypeId: '.$this->getJSMapType();
  
		if($this->getNavigationControl()!==null)$script.=', navigationControl: '.$this->getJSNavigationControl();
  
  		$script.=',
  navigationControlOptions: {
  	style: '.$this->getJSNavigationControlStyle();
		if($this->getNavigationControlPosition())
		{
			$script.=', position: '.$this->getJSNavigationControlPosition();
		}
		$script.='
	}';
	
		if($this->getMapTypeControl()!==null)$script.=', mapTypeControl: '.$this->getJSMapTypeControl();
		
		$script.=',
  mapTypeControlOptions: {
  	style: '.$this->getJSMapTypeControlStyle();
		if($this->getMapTypeControlPosition())
		{
			$script.=', position: '.$this->getJSMapTypeControlPosition();
		}
		$script.='
	}';
		if($this->getScaleControl()!==null)$script.=', scaleControl: '.$this->getJSScaleControl();
		$script.=',
  scaleControlOptions: {';
		if($this->getScaleControlPosition())
		{
			$script.=', position: '.$this->getJSScaleControlPosition();
		}
		$script.='
	}';
		if($this->getDisableDefaultUI()!==null)$script.=', disableDefaultUI: '.$this->getJSDisableDefaultUI();
		$script.='
});'."\n";
		
		foreach($this->_markers as $marker)
		{
			$script.=$marker->getJS();
		}
		
		$script.=$this->_postInitScript;
		// TODO : think about it vs add()
		$script=new Ea_Layout_Script($script, true);
		$script->display();
	}
	
	protected $_markers=array();
	
	/**
	 * Add a marker.
	 * 
	 * @param Ea_Layout_GMap_Marker|Ea_Layout_GMap_Point|float $lat
	 * @param float $lng
	 * @return Ea_Layout_GMap_Marker
	 */
	public function addMarker($point, $title=null)
	{
		if($point instanceof Ea_Layout_GMap_Marker)
		{
			$marker=$point;
		}
		else if($point instanceof Ea_Service_GMap_Point)
		{
			$marker=new Ea_Layout_GMap_Marker($point->getLat(), $point->getLng(), $title);
		}
		else if(is_array($point))
		{
			$marker=new Ea_Layout_GMap_Marker($point[0], $point[1], $title);
		}
		else if(is_object($point))
		{
			$marker=new Ea_Layout_GMap_Marker($point->lat, $point->lng, $title);
		}
		$marker->setMap($this);
		$this->_markers[]=$marker;
		return $marker;
	}
	
	protected $_initScript=null;
	protected $_postInitScript=null;
	
	/**
	 * Set user init script.
	 * 
	 * @param string $init put before the google map script
	 * @param string $postInit put after the google map script
	 */
	public function initScript($init, $postInit=null)
	{
		// you can refer to the javascript google map object with 'gmap' variable.
		$this->_initScript=$init;
		$this->_postInitScript=$postInit;
	}
}
?>
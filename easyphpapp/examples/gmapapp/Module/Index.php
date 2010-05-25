<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  basicapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.3-20100122
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

require_once 'Ea/Module/Abstract.php';
require_once 'Ea/Layout/GMap.php';
require_once 'Ea/Layout/Script.php';
require_once 'Ea/Service/GMap/GeocoderV3.php';

/**
 * My basic Google Map module.
 *
 */
class Module_Index extends Ea_Module_Abstract
{
	public function init()
	{
		// set the page title
		$this->getPage()->setTitle('Google Map V3');
	}
	
	public function actionIndex()
	{
		$this->add($gmap=new Ea_Layout_GMap(500, 400, 'gmap_test'));

		$geo=new Ea_Service_GMap_GeocoderV3;
		
		$address='31, av Brancolar, 06100, Nice, France';
		
		$res=$geo->geocode($address);
		
		$gmap->setCenter($res[0]);
		
		for($i=-5;$i<5;$i++)
		{
			$marker=$gmap->addMarker(array($res[0]->getLat()+$i*0.1, $res[0]->getLng()+$i*0.1+0.1), "Hello World $i");
			$marker->setOption('icon', 'icon');
		}
		$gmap->addMarker($res, $address);
		
		$gmap->initScript('var icon="http://www.google.com/uds/samples/places/temp_marker.png";');
		
	}
}
?>
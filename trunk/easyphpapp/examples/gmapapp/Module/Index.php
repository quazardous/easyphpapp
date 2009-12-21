<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  basicapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

require_once 'Ea/Module/Abstract.php';
require_once 'Ea/Layout/GMap.php';
require_once 'Ea/Layout/Script.php';

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
		// simple gmap layout
		$this->add($gmap=new Ea_Layout_GMap(500, 400, 'gmap_test'));
		
		$gmap->setCenter(-34.397, 150.644);
		
		for($i=0;$i<10;$i++)
		{
			$marker=$gmap->addMarker(-34.397+$i*0.1, 150.644+$i*0.1, "Hello World $i");
			$marker->addOption('icon', 'http://www.google.com/uds/samples/places/temp_marker.png', true);
		}

	}
}
?>
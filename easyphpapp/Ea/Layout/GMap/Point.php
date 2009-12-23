<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  GMap
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.2-20091223
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Service/GMap/Point.php';

/**
 * GMap for Gmap.
 * 
 * @author berlioz
 *
 */
class Ea_Layout_GMap_Point extends Ea_Service_GMap_Point
{
	public function getJS()
	{
		return 'new google.maps.LatLng('.$this->getLat().','.$this->getLng().')';
	}
}

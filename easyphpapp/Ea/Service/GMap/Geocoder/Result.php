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

require_once 'Ea/Service/GMap/Point.php';

class Ea_Service_GMap_Geocoder_Result extends Ea_Service_GMap_Point
{
	protected $_json=null;
	
	public function __construct($data=null)
	{
		parent::__construct();
		$this->setFromJSON($data);
	}
	
	/**
	 * Set from Google Geocoder JSON format.
	 * 
	 * @param json $json
	 */
	public function setFromJSON($json)
	{
		$this->_json=$json;
		$this->setLat($json->Placemark[0]->Point->coordinates[1]);
		$this->setLng($json->Placemark[0]->Point->coordinates[0]);
	}
}
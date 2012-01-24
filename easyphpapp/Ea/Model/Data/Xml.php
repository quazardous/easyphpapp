<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Data
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Model/Data/Abstract.php';

/**
 * From Xml Data model class.
 * 
 */
class Ea_Model_Data_Xml extends Ea_Model_Data_Abstract
{
		
	/**
	 * Constructor.
	 * 
	 * @param $filename xml model
	 * 
	 */
	public function __construct($filename)
	{
		$this->loadFromXmlFile($filename);
	}	
}
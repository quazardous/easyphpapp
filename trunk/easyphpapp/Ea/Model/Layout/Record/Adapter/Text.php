<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.0.20081106
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Field.php';
require_once 'Ea/Model/Layout/Record/Adapter/Interface.php';'

/**
 * Text layout for field.
 */
class Ea_Model_Layout_Record_Adapter_Text extends Ea_Layout_Record_Adapter_Field implements Ea_Model_Layout_Record_Adapter_Interface
{
	function __construct($field, $meta, $config)
	{
		parent::__construct($field, $config);
	}	
}

?>
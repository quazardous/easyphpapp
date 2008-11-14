<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Layout
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.1-20081114
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Model/Layout.php';

/**
 * Interface to get header cell content from field.
 * 
 * @see Ea_Model_Layout
 */
interface Ea_Model_Layout_Header_Adapter_Interface
{
	/**
	 * Return the header content.
	 * 
	 * @param string $column
	 * @param Ea_Model_Layout $model
	 * @return Ea_Layout_Abstract|string
	 */
	function getHeader($column, Ea_Model_Layout $model);
}
?>
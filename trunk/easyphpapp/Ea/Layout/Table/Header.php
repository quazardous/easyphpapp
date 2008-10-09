<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Table/Cell.php';

/**
 * Table header cell layout class.
 * 
 * @see Ea_Layout_Table
 */
class Ea_Layout_Table_Header extends Ea_Layout_Table_Cell
{
	protected $_tag='th';
}

?>
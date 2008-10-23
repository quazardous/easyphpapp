<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Page
 * @subpackage  Page
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.2.7.20081023
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

/**
 * Layouts need a page.
 * If you want to use the ZF MVC engine you can implements a page on top of Zend_View.
 * @see Ea_Layout_Abstract::setPage()
 * In fact if you use setPage(), your class only have to "mimic" the Ea_Page_Interface methods without physically implements Ea_Page_Interface.
 *
 */
interface Ea_Page_Interface
{
	function escape($string);
}

?>
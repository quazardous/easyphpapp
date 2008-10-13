<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  config
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

set_include_path(
'/path/to/your/include'.PATH_SEPARATOR. // where to search your classes
'/path/to/Zend/Framework/library/'.PATH_SEPARATOR. // where to search for Zend Framework
'/path/to/easyphpapp'.PATH_SEPARATOR. // where to search for EasyPhpApp
get_include_path()
);


?>
<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  config
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.0.20081105
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

// just to be sure you wont use magic quotes....
set_magic_quotes_runtime(false);

set_include_path(
'/path/to/your/include'.PATH_SEPARATOR. // where to search your classes
'/path/to/Zend/Framework/library/'.PATH_SEPARATOR. // where to search for Zend Framework
'/path/to/easyphpapp'.PATH_SEPARATOR. // where to search for EasyPhpApp
get_include_path()
);

// db config
define('DB_HOST',       	 'localhost');
define('DB_NAME',     		 'test');
define('DB_USER_NAME',       'user');
define('DB_USER_PASSWORD',   'xxxxxxxxx');

error_reporting(E_ALL|E_STRICT);

?>
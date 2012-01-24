<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  config
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

if(!defined('PHP_VERSION_ID'))
{
    $version = PHP_VERSION;
    define('PHP_VERSION_ID', ($version{0} * 10000 + $version{2} * 100 + $version{4}));
    unset($version);
}

if(PHP_VERSION_ID<50300)
{
	// just to be sure you wont use magic quotes....
	set_magic_quotes_runtime(false);
}

set_include_path(
'/path/to/your/include'.PATH_SEPARATOR. // where to search your classes
'/path/to/Zend/Framework/library/'.PATH_SEPARATOR. // where to search for Zend Framework
'/path/to/easyphpapp'.PATH_SEPARATOR. // where to search for EasyPhpApp
get_include_path()
);

require_once '../conf/strptime.php';

// db config
define('DB_HOST',       	 'localhost');
define('DB_NAME',     		 'test');
define('DB_USER_NAME',       'user');
define('DB_USER_PASSWORD',   'xxxxxxxxx');

define('PROXY_HOST',         'my.proxy.com');
define('PROXY_PORT',         8080);
define('PROXY_USER',         'user');
define('PROXY_PASSWORD',     'xxxxxxxxx');

// google map api key
define('GMAP_API_KEY', 'xxxxxxxxx');


error_reporting(E_ALL|E_STRICT);

date_default_timezone_set('Europe/Paris');

// You can define this if you want "modern" behaviours (jQuery UI, etc)
define('EA_RICH', true);
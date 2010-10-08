<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  basicapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.8-20091009
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

// This app will display 'Hello World'

// don't forget to set your include path
require '../conf/config.php';

require_once 'Ea/App.php';

// instance of application with application Name
$app=Ea_App::singleton('gmapapp');
// on 2nd parameter you can specify a version

// the appname and the version can be retrieve from a APPNAME and a VERSION file or constant.

// set the module class prefix, so with Zend_Loader style, we search the class in 'Module/' directory.
// you are responsible to configure your include path so that it can be aware of that directory
$app->setModuleClassPrefix('Module');

// show a simple box with version info
$app->showVersion();

require_once 'Ea/Service/GMap/Geocoder.php';
Ea_Service_GMap_Geocoder::setApiKey(GMAP_API_KEY);
if(defined('PROXY_HOST')&&PROXY_HOST)
{
	Ea_Service_GMap_Geocoder::setDefaultHttpAdapter('Zend_Http_Client_Adapter_Proxy', array(
		'proxy_host' => PROXY_HOST,
    	'proxy_port' => PROXY_PORT,
    	'proxy_user' => PROXY_USER,
    	'proxy_pass' => PROXY_PASSWORD,
	));
}


// call the dispath()
$app->dispatch();

// so calling url index.php will tell the application to target module 'index' and action 'index' (the default).
// application will try to call Module_Index::actionIndex().


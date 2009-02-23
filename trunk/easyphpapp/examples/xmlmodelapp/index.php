<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  modelapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.5-20090209
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

// This app will demonstrate data model support from XML

// don't forget to set your include path and db config
require '../conf/config.php';

require_once 'Ea/Router.php';
// instance of router
$router=Ea_Router::singleton('xmlmodelapp');

// set the module class prefix, so with Zend_Loader style, we search the class in 'Module/' directory.
// you are responsible to configure your include path so that it can be aware of that directory
$router->setModuleClassPrefix('Module');

// call the dispath()
$router->dispatch();

// so calling url index.php will tell the router to target module 'index' and action 'index' (the default).
// router will try to call Module_Index::actionIndex().

?>
<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  formapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

// This app will demonstrate submit callbacks

// don't forget to set your include path
require '../conf/config.php';

require_once 'Ea/App.php';

// instance of application
$app=Ea_App::singleton('callbackformapp');

// set the module class prefix, so with Zend_Loader style, we search the class in 'Module/' directory.
// you are responsible to configure your include path so that it can be aware of that directory
$app->setModuleClassPrefix('Module');

$app->showVersion();

// call the dispath()
$app->dispatch();

// so calling url index.php will tell the application to target module 'index' and action 'index' (the default).
// application will try to call Module_Index::actionIndex().

?>
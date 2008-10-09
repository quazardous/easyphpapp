<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  basicapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

// This app will display 'Hello World'

// don't forget to set your include path
set_include_path(
MY_INC_DIR.PATH_SEPARATOR. // where to search your classes
ZEND_FRAMEWORK_DIR.PATH_SEPARATOR. // where to search for Zend Framework
EA_DIR.PATH_SEPARATOR. // where to search for EasyPhpApp
get_include_path()
);

require_once 'Ea/Router.php';

// set the module class prefix, so with Zend_Loader style, we search the class in 'Module/' directory.
// you are responsible to add this module in the include path
Ea_Router::singleton()->setModuleClassPrefix('Module');

// call the dispath()
Ea_Router::singleton()->dispatch();

// so calling url index.php will tell the router to target module 'index' and action 'index' (the default).
// router will try to call Module_Index::actionIndex().

?>
<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  securityapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

// This app will demonstrate the auth capability of the security layer.
// Auth is only responsible to allow general access to a known user.

// don't forget to set your include path
require '../conf/config.php';

require_once 'Ea/Router.php';
$router=Ea_Router::singleton();

// set the module class prefix, so with Zend_Loader style, we search the class in 'Module/' directory.
// you are responsible to configure your include path so that it can be aware of that directory
$router->setModuleClassPrefix('Module');

require_once 'Ea/Security.php';
$security=Ea_Security::singleton();

// initialize security layer and specify 'my-security' (Module_MySecurity) as security module.
$router->initSecurity($security, 'my-security');

// says that admin is a user
$security->addRole('admin', 'user');

// says that user can access module 'index'
$router->allow('user', 'index');

// says that only admin can access module 'index' action admin 'admin'
$router->deny('user', 'index', 'admin');
$router->allow('admin', 'index', 'admin');

// NB : if you do not specifically allow or deny, all authenticated users will be granted to access everything

// call the dispath()
$router->dispatch();

// so calling url index.php will tell the router to target module 'index' and action 'index' (the default).
// router will try to call Module_Index::actionIndex().

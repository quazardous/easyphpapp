<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  securityapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

// This app will demonstrate the auth and acl capability of the security layer.
// Auth is only responsible to allow general access to a known user.
// ACL is responsible to grant access to modules and actions.

// don't forget to set your include path
require '../conf/config.php';

require_once 'Ea/App.php';
$app=Ea_App::singleton('securityapp');

// set the module class prefix, so with Zend_Loader style, we search the class in 'Module/' directory.
// you are responsible to configure your include path so that it can be aware of that directory
$app->setModuleClassPrefix('Module');

require_once 'Ea/Security.php';
$security=Ea_Security::singleton();

$app->showVersion();

// initialize security layer and specify 'my-security' (Module_MySecurity) as security module.
$app->initSecurity($security, 'my-security');

// says that admin is a user
$security->addRole('admin', 'user');

// says that 'public' can access module 'index', action 'index'
// 'public' is a meta role for "all people"
$app->allow('public', 'index', 'index');

// you can use 'anonymous' meta role to target unconnected/anonymous acces

// says that 'user' can access all actions of module 'index'
$app->allow('user', 'index');

// says that only admin can access module 'index' action admin 'admin'
$app->deny('user', 'index', 'admin');
$app->allow('admin', 'index', 'admin');

// NB : if you do not specifically allow or deny, all people will be granted to access everything

// call the dispath()
$app->dispatch();

// so calling url index.php will tell the application to target module 'index' and action 'index' (the default).
// application will try to call Module_Index::actionIndex().

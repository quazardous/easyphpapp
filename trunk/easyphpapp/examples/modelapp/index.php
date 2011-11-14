<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  modelapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id:$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

// This app will demonstrate data model support

// don't forget to set your include path and db config
require '../conf/config.php';

// some field type are not supported...
// error_reporting((E_ALL|E_STRICT)^E_USER_NOTICE);

require_once 'Zend/Db.php';

$db = Zend_Db::factory('Pdo_Mysql', array(
					'host'              => DB_HOST,
					'username'          => DB_USER_NAME,
					'password'          => DB_USER_PASSWORD,
					'dbname'            => DB_NAME,
));

$db->getConnection();

require_once 'Zend/Db/Table.php';
Zend_Db_Table::setDefaultAdapter($db);

require_once 'Ea/App.php';

// instance of application
$app=Ea_App::singleton('modelapp');

// set the module class prefix, so with Zend_Loader style, we search the class in 'Module/' directory.
// you are responsible to configure your include path so that it can be aware of that directory
$app->setModuleClassPrefix('Module');

// call the dispath()
$app->dispatch();

// so calling url index.php will tell the application to target module 'index' and action 'index' (the default).
// application will try to call Module_Index::actionIndex().

?>
<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  basicapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.6-20100903
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

// This app demonstrate jQuery integartion.

// don't forget to set your include path
require '../conf/config.php';

require_once 'Ea/App.php';

// instance of application with application Name
$app=Ea_App::singleton('jqueryapp');
// on 2nd parameter you can specify a version

// the appname and the version can be retrieve from a APPNAME and a VERSION file or constant.

// set the module class prefix, so with Zend_Loader style, we search the class in 'Module/' directory.
// you are responsible to configure your include path so that it can be aware of that directory
$app->setModuleClassPrefix('Module');

// show a simple box with version info
$app->showVersion();

$app->applyDefaultStyle();

// set jQuery
$app->jQuery('jquery-ui/jquery-XXX.js');

// set jQuery UI
$app->jQueryUi(
		array(
			'jquery-ui/ui/jquery.ui.js',
			'jquery-ui/ui/jquery.ui.core.js',
			'jquery-ui/ui/jquery.ui.widget.js',
			'jquery-ui/ui/jquery.ui.tabs.js',
			'jquery-ui/ui/jquery.ui.datepicker.js',
			'jquery-ui/ui/i18n/jquery-ui-i18n.js',
			'jquery-ui/ui/i18n/jquery.ui.datepicker-fr.js',
		),
		array(
			'jquery-ui/themes/base/jquery-ui.css',
			'jquery-ui/themes/base/jquery.ui.all.css',
			'jquery-ui/themes/base/jquery.ui.tabs.css',
		)
	);

// call the dispath()
$app->dispatch();

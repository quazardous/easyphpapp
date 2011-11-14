<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  basicapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id:$
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
			'contrib/jquery-ui/js/jquery-1.4.2.min.js',
			//'contrib/jquery-ui/js/jquery-ui-1.8.5.custom.min.js',
			'contrib/jquery-ui/development-bundle/ui/jquery.ui.core.js',
			'contrib/jquery-ui/development-bundle/ui/jquery.ui.widget.js',
			'contrib/jquery-ui/development-bundle/ui/jquery.ui.tabs.js',
			'contrib/jquery-ui/development-bundle/ui/jquery.ui.datepicker.js',
			'contrib/jquery-ui/development-bundle/ui/i18n/jquery-ui-i18n.js',
			'contrib/jquery-ui/development-bundle/ui/i18n/jquery.ui.datepicker-fr.js',
		),
		array(
			'contrib/jquery-ui/development-bundle/themes/base/jquery.ui.all.css',
		)
	);

// call the dispath()
$app->dispatch();

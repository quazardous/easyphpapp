<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Autoloader
 * @subpackage  Autoloader
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

/**
 * Autoloader utility class.
 * @see Zend_Loader_Autoloader
 * 
 */
class Ea_Autoloader
{
	/**
	 * ZF autoloader.
	 * @var Zend_Loader_Autoloader
	 */
	protected static $_autoloader=NULL;
	
	protected static $_autoloaded=NULL;
	
	/**
	 * Enable or disable autoload.
	 * @param TRUE|string|array $autoload
	 */
	public static function autoload($autoload=TRUE)
	{
		require_once 'Zend/Loader/Autoloader.php';

		if($autoload&&(!self::$_autoloader)) {
			if ($autoload === TRUE) {
  		  $autoload = array();
  		}
  		elseif (is_string($autoload)) {
  		  $autoload = array($autoload);
  		}
		  if (!in_array('Ea_', $autoload)) {
		    $autoload[] = 'Ea_';
		  }
		  self::$_autoloaded = $autoload;
			self::$_autoloader = Zend_Loader_Autoloader::getInstance();
			self::$_autoloader->registerNamespace($autoload);
		}
		if((!$autoload)&&self::$_autoloader) {
			self::$_autoloader->unregisterNamespace(self::$_autoloaded);
			self::$_autoloader = NULL;
			self::$_autoloaded = NULL;
		}
	}
}

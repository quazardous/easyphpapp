<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Autoloader
 * @subpackage  Autoloader
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.6-20101008
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
	protected static $_autoloader=null;
	
	public static function autoload($autoload=true)
	{
		require_once 'Zend/Loader/Autoloader.php';
		if($autoload&&(!self::$_autoloader))
		{
			self::$_autoloader = Zend_Loader_Autoloader::getInstance();
			self::$_autoloader->registerNamespace('Ea_');
		}
		if((!$autoload)&&self::$_autoloader)
		{
			self::$_autoloader->unregisterNamespace('Ea_');
			self::$_autoloader=null;
		}
	}
}

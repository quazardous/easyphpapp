<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Application
 * @subpackage  Module
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

/**
 * Interface used by security module.
 * For example, a security module can display login form to authenticate a user.
 * @see Ea_Security
 */
interface Ea_Module_Security_Interface
{
	/**
	 * Return security user : first methode called.
	 * @see Ea_Security_User_Abstract
	 *
	 * @return Ea_Security_User_Abstract|null : return user or null if not ready (ie : no input)
	 */
	public function getSecurityUser();
	
	/**
	 * Action executed when getUser() returns null.
	 *
	 */
	public function securityChallenge();
	
	/**
	 * Action executed when getUser return invalid user.
	 *
	 */
	public function securityDeny();
	
}

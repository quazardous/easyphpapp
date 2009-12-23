<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.3-20091223
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Single.php';

/**
 * Script layout class.
 */
class Ea_Layout_Script extends Ea_Layout_Single
{
	protected $_tag='script';

	/**
	 * Script is called with document.onload.
	 * 
	 * @var boolean
	 */
	protected $_onload=false;
	
	/**
	 * Get  Onload.
	 *
	 * @return type onload
	 */
	public function getOnload() 
	{
		return $this->_onload;
	}
	
	/**
	 * Set Onload.
	 * 
	 * @param type onload
	 */
	public function setOnload($onload=true)
	{
		$this->_onload = $onload;
		if($this->getPage() instanceof Ea_Page)
		{
			if($onload)
			{
				$this->getPage()->setOnloadSupport(true);
			}
		}
	}
	
	/**
	 * Script constructor.
	 * 
	 * @param string $src
	 * @param string $script
	 * @param string $type
	 * @param array $config
	 */
	public function __construct($script=null, $onload=false, $type='text/javascript', $config=null)
	{
		parent::__construct(null, $config);
		$this->setScript($script);
		$this->setAttribute('type', $type);
		$this->setOnload($onload);
	} 
	
	public function setSrc($src)
	{
		$this->setAttribute('src', $src);
	}
	
	protected $_script=null;
	public function setScript($script)
	{
		$this->_script=$script;
	}
	public function getScript()
	{
		return $this->_script;
	}
		
	protected function render()
	{
		echo '<';
		echo $this->_tag;
		$this->renderAttributes();
		echo '>';
	
		if($this->getOnload())
		{
			echo '
			ea.addOnload(function(){
			';
		}
		
		echo $this->getScript();

		if($this->getOnload())
		{
			echo ' });';
		
		}		
		
		echo '</';
		echo $this->_tag;
		echo '>';
	}
	
	public function setPage($page)
	{
		parent::setPage($page);
		if($this->getPage() instanceof Ea_Page)
		{
			if($this->getOnload())
			{
				$this->getPage()->setOnloadSupport(true);
			}
		}
	}
}
?>
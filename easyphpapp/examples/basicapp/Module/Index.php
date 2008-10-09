<?php
require_once 'Ea/Module/Abstract.php';
class Module_Index extends Ea_Module_Abstract
{
	//protected $_pageClass='Page_EasyOptin';
	
	protected function init()
	{
		// set the page title
		$this->getPage()->setTitle('Hello World');
	}
	
	public function actionIndex()
	{
		// add the text 'Hello World' to the page
		$this->getPage()->add('Hello World');
	}
}
?>
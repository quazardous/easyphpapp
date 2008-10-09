<?php
require_once 'Ea/Router.php';

// set the module class prefix
Ea_Router::singleton()->setModuleClassPrefix('Module');

// call the dispath()
Ea_Router::singleton()->dispatch();

// so index.php will target module 'index' and action 'index' by default
// router will try to call Module_Index::actionIndex()

?>
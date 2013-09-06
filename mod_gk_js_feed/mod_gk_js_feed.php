<?php

/**
* GK JS Feed - main PHP file
* @Copyright (C) 2013 Gavick.com
* @ All rights reserved
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @ version $Revision: 1.0 $
**/

// no direct access
defined('_JEXEC') or die;
if(!defined('DS')){ define('DS',DIRECTORY_SEPARATOR); }
ini_set('display_errors', 1);
// helper loading
require_once (dirname(__FILE__).DS.'helper.php');
// create class instance with params
$helper = new GKJSFeedHelper($module, $params); 
// creating HTML code	
$helper->render();

// EOF
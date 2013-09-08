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

if(is_file(JPATH_BASE . '/components/com_community/libraries/core.php')) {
	// helper loading
	require_once (dirname(__FILE__).DS.'helper.php');
	// create class instance with params
	$helper = new GKJSFeedHelper($module, $params); 
	// creating HTML code	
	$helper->render();
} else {
	echo 'Please install JomSocial first!';
}

// EOF
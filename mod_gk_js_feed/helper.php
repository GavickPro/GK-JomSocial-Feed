<?php

/**
* Helper class for GK JS Feed module
*
* GK JS Feed
* @Copyright (C) 2013 Gavick.com
* @ All rights reserved
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @ version $Revision: GK5 1.0 $
**/

// access restriction
defined('_JEXEC') or die('Restricted access');
// import JString class for UTF-8 problems
jimport('joomla.utilities.string'); 
//
include_once(JPATH_BASE . '/components/com_community/defines.community.php');
require_once(JPATH_BASE . '/components/com_community/libraries/core.php');
// Main GK Tab class
class GKJSFeedHelper {
	private $config; // configuration array
	private $tabs; // array of tabs content
	private $mod_getter; // object to get the modules
	private $active_tab; // number of the active tab
	// constructor
	public function __construct($module, $params) {
		// put the module params to the $config variable
		$this->config = $params->toArray();
        // getting module ID
		$this->config['module_id'] = ($this->config['module_id'] == '') ? 'gk-jsfeed-' . $module->id : $params->get('module_id', '');
	}
	// function to get the feed data
	public function getData() {
		$db = JFactory::getDBO();
		$query = '
		SELECT 
			id,
			title, 
			actor 
		FROM 
			#__community_activities 
		WHERE 
			like_type = "profile.status" 
		ORDER BY 
			created DESC 
		LIMIT 
			'.$this->config['offset'].', 1;';
		$db->setQuery($query);
		// check if some statuses was detected
		$text = '';
		$avatar = '';
		$url = '';
		$username = '';
		
		if($statuses = $db->loadObjectList()) {
			foreach($statuses as $status) {
				$user_id = $status->actor;
				$user = CFactory::getUser($user_id);
				$username = CStringHelper::escape($user->getDisplayName());
				
				if($this->config['show_avatar'] == 1) {
					$avatar = $user->getAvatar();
				}
				$status = CStringHelper::escape($status->title);
				$text = (strlen($status) > $this->config['text_limit']) ? substr($status, 0, $this->config['text_limit']) . '&hellip;' : $status;
				$url = CRoute::_('index.php?option=com_community&view=profile&userid='.$user_id );
			}
		}
		// return the data array
		return array(
						"text" => $text,
						"avatar" => $avatar,
						"url" => $url,
						"username" => $username
					);
	}
	// function to render module code
	public function render() {
		$status = $this->getData();
		// create necessary instances of the Joomla! classes 
		$document = JFactory::getDocument();
		$uri = JURI::getInstance();
		// add stylesheets to document header
		if($this->config["useCSS"] == 1) {
			$document->addStyleSheet( $uri->root().'modules/mod_gk_js_feed/styles/style.css', 'text/css' );
		}
		// include main module view
		require(JModuleHelper::getLayoutPath('mod_gk_js_feed', 'default'));
	}
}

// EOF
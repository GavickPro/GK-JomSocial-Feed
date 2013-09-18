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
		$actor_condition = '';
		     
		if(trim($this->config['user_id']) != '' && is_numeric($this->config['user_id'])) {
			$actor_condition = ' AND a.actor = ' . $this->config['user_id'] . ' ';
		}
		
		if($this->config['content_type'] == 'status') {
			$query = '
			SELECT 
				id,
				title, 
				actor 
			FROM 
				#__community_activities AS a
			WHERE 
				a.like_type = "profile.status" 
				'.$actor_condition.'
			ORDER BY 
				a.created DESC 
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
					$status_text = CStringHelper::escape($status->title);
					$text = (strlen($status_text) > $this->config['text_limit']) ? substr($status_text, 0, $this->config['text_limit']) . '&hellip;' : $status_text;
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
		} else {
			$query = '
			SELECT 
				a.id AS id,
				a.title AS title, 
				a.actor AS actor,
				a.params AS params,
				p.image AS image,
				p.original AS original,
				p.thumbnail AS thumbnail
			FROM 
				#__community_activities as a
			LEFT JOIN
				#__community_photos as p
				ON
				a.like_id = p.id
			WHERE 
				(
					a.like_type = "photo"
					OR
					a.like_type = "albums" 
				)
				AND
				p.id IS NOT NULL 
				'.$actor_condition.'
			ORDER BY 
				a.created DESC 
			LIMIT 
				'.$this->config['offset'].', 1;';
			$db->setQuery($query);
			// check if some statuses was detected
			$text = '';
			$photo = '';
			$url = '';
			
			if($statuses = $db->loadObjectList()) {
				foreach($statuses as $status) {
					if($this->config['show_text'] == 1) {
						$status_text = CStringHelper::escape($status->title);
						$text = (strlen($status_text) > $this->config['photo_text_limit']) ? substr($status_text, 0, $this->config['photo_text_limit']) . '&hellip;' : $status_text;
					}
					
					if($this->config['image_type'] == 'image') {
						$photo = $status->image;
					} elseif($this->config['image_type'] == 'thumbnail') {
						$photo = $status->thumbnail;
					} else {
						$photo = $status->original;
					}
					// parse the photo params
					$params = json_decode($status->params);
					$url = CRoute::_(substr($params->photo_url, stripos($params->photo_url, 'index.php')));
				}
			}
			// return the data array
			return array(
							"text" => $text,
							"photo" => $photo,
							"url" => $url
						);
		}
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

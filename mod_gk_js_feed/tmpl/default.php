<?php

/**
* GK JS Feed - content template
* @Copyright (C) 2013 Gavick.com
* @ All rights reserved
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0 $
**/

// access restriction
defined('_JEXEC') or die('Restricted access');

?>

<div id="<?php echo $this->config['module_id'];?>" class="gkJSFeed gkJSFeedType-<?php echo $this->config['content_type']; ?>">
	
	<?php if($this->config['content_type'] == 'status') : ?>
		<?php if($status['text'] != '') : ?>
			<?php if($status['avatar'] != '') : ?>
			<a href="<?php echo $status['url']; ?>" class="gkAvatar gkAvatar-<?php echo $this->config['avatar_position']; ?>">
				<img src="<?php echo $status['avatar']; ?>" alt="<?php echo $status['username']; ?>" />
			</a>
			<?php endif; ?>
			
			<div class="gkStatus">
				<?php if($this->config['show_username'] == 1) : ?>
				<a href="<?php echo $status['url']; ?>" class="gkUsername"><?php echo $status['username']; ?></a>
				<?php endif; ?>
				
				<?php if($this->config['show_readon'] == 1) : ?>
				<p><?php echo $status['text']; ?></p>
				<?php else : ?>
				<p><a href="<?php echo $status['url']; ?>"><?php echo $status['text']; ?></a></p>
				<?php endif; ?>
				
				<?php if($this->config['show_readon'] == 1) : ?>
				<a href="<?php echo $status['url']; ?>" class="gkReadon"><?php echo ($this->config['readon_text'] != '') ? $this->config['readon_text'] : JText::_('MOD_JS_FEED_READ_MORE'); ?></a>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<?php echo JText::_('MOD_JS_FEED_NO_STATUS'); ?>
		<?php endif; ?>
	<?php else : ?>
		<?php if($status['url'] != '') : ?>
			<?php
				$uri = JURI::getInstance();
			?>
			<?php if($status['text'] == '') : ?>
				<a href="<?php echo $status['url']; ?>" style="background-image: url('<?php echo $uri->root() . $status['photo']; ?>');"></a>
			<?php else : ?>
				<a href="<?php echo $status['url']; ?>" style="background-image: url('<?php echo $uri->root() . $status['photo']; ?>');"></a>
				<h3 class="gkStatusText-<?php echo $this->config['text_position']; ?> gkStatusText-<?php echo $this->config['text_color']; ?>"><a href="<?php echo $status['url']; ?>"><?php echo $status['text']; ?></a></h3>
			<?php endif; ?>
		<?php else : ?>
			<?php echo JText::_('MOD_JS_FEED_NO_STATUS'); ?>
		<?php endif; ?>
	<?php endif; ?>
</div>
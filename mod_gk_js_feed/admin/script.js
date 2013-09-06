jQuery(document).ready(function() {	
	// remove unnecessary labels 
	jQuery('#jform_params_about_us-lbl').parent().css('display', 'none');
	jQuery('#jform_params_about_us-lbl').parents().eq(2).find('.controls').css('margin-left', '15px');
	
	// input-append
	jQuery('.input-chars').each(function(i, el){jQuery(el).parent().html("<div class=\"input-prepend\">" + jQuery(el).parent().html() + "<span class=\"add-on\">chars</span></div>")});
});
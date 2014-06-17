jQuery(document).ready(function() {
	var selectedElement = jQuery('#theme').find(":selected").val();
	social_stickers_change_theme(selectedElement);	

	jQuery('#theme').change(function () {
		var optionSelected = jQuery(this).find("option:selected");
		var valueSelected  = optionSelected.val();
		social_stickers_change_theme(valueSelected);
	});
});

function social_stickers_change_theme(valueSelected) {
	jQuery.ajax({
	    url: ajax_object.ajaxurl,
	    type: 'POST',
	    data: { 
			action: 'social_stickers_reload_admin_theme',
			theme: valueSelected
	    },
	    success: function( data ){
	    	var obj = jQuery.parseJSON(data);
			jQuery("#current_theme").hide().html(obj.template_string).fadeIn('slow');
			jQuery("#theme_description").hide().html(obj.theme_string).fadeIn('slow');
			jQuery("#sortable").sortable({
				stop: function(event, ui) {
					jQuery("#social_stickers_order").val(jQuery(this).sortable('serialize'));
				}
			});		    
		}
	});
}
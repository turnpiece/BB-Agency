// JavaScript Document
jQuery(document).ready(function($){

	var debugging = false;
	
	$('input.button-primary').change(function() {
		var $this = $(this);
		var image = $this.attr('value');
		var profile = $this.attr('data-profile');

		debug( image + ' ' + profile );

		$.ajax({
			url: page.ajaxurl,
			type: 'post',
			data: {
				action: 'set_primary_image',
				image: image,
				profile: profile
			}
		}).done(function() {
			$this.closest('.gallery').find('.profileimage.primary-picture').removeClass('primary-picture');
			$this.closest('.profileimage').addClass('primary-picture');
		})
	})

	/*
	 *	
	 */
	$("#ProfileGender").change(function() {

		if($("#ProfileGender option:selected").text() == 'Male'){

			$(".male_filter").show();
			$(".female_filter").hide();
			clear_filter(".female_filter");

		} else if (jQuery("#ProfileGender option:selected").text() == 'Female'){

			$(".male_filter").hide();
			$(".female_filter").show();
			clear_filter(".male_filter");

		} else {

			$(".male_filter").show();
			$(".female_filter").show();

		}

	});
	
	/*
	 *	Clear fields to set value to null
	 */
	 function clear_filter(filter){

	 	$(filter).each(function(){
			
				$(this).val('');

		});	

	 }

	 function debug( message ) {
	 	if (debugging)
	 		console.log( 'jquery-page.js '+message );
	 }

});
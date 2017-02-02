// JavaScript Document
jQuery(document).ready(function($){
	
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

});
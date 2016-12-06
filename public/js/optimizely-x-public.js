(function( $ ) {
	'use strict';

	var optimizelyError = function(error){
		$('#optimizely_error_message').html(error);
		$('#optimizely_error_box').show();
	}

	$("#optimizely_create").click(function(){
		var variations = [];
		var error = [];
	  $(".optimizely_variation").each(function(i,e){
			var value = $(e).val();
			if(value === ''){
				error.push("Variation #" + (i + 1) + " doesn't have a title set.")
			}
	    variations.push($(e).val());
	  });
		if(error.length > 0){
			optimizelyError(error.join('<br>'));
		} else {
			createExperiment(variations);
		}
	});



	var changeStatus = function(){
		$(".optimizely_loading").removeClass('hidden');
		$('.optimizely_running_experiment').addClass('hidden');

		var data = {
			'action': 'change_status',
			'status': window.optimizelyIntegration.status,
			'entitiy_id': window.optimizelyIntegration.entitiy_id
		};
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.ajax({
			url: ajaxurl,
			data: data,
			method: 'POST'
		}).done(function(data,b,xhr) {
			try {
				data = JSON.parse(data);
			} catch (ex) {

			}
			if(xhr.status === 200 && data.status === 'SUCCESS'){
				window.optimizelyIntegration.status = data.json.status;
				$("#optimizely_experiment_status_text").html(window.optimizelyIntegration.status);
				if(data.json.status == 'paused') {
				  $("#optimizely_toggle_running").text('Start Experiment');
				} else {
					$("#optimizely_toggle_running").text('Pause Experiment');
				}
			} else {
				$(".optimizely_loading").addClass('hidden');
				$(".optimizely_new_experiment").removeClass('hidden');
				console.error("An error occurred during the pausing of the Optimizely experiment:", data)
			}
			$(".optimizely_loading").addClass('hidden');
			$('.optimizely_running_experiment').removeClass('hidden');
		});
	}

	$('#optimizely_toggle_running').click(changeStatus);

	var updateRunning = function(data, variations){
		$('#optimizely_view').attr('href', data.json.link);
		$(".optimizely_loading").addClass('hidden');
		$('.optimizely_running_experiment').removeClass('hidden');
		$("#optimizely_experiment_id").html(data.json.id);
		window.optimizelyIntegration.status = 'paused';
		$("#optimizely_experiment_status_text").html(window.optimizelyIntegration.status);

		$(".optimizely_variation_title").each(function(i,e){
			$(e).text(variations[i]);
		});


	};

	var createExperiment = function(variations){
		$(".optimizely_loading").removeClass('hidden');
		$(".optimizely_new_experiment").addClass('hidden');

		var data = {
	  	'action': 'create_experiment',
	  	'variations': JSON.stringify(variations),
			'entitiy_id': window.optimizelyIntegration.entitiy_id
		};
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.ajax({
		  url: ajaxurl,
		  data: data,
		  method: 'POST'
		}).done(function(data, b, xhr) {
			try {
				data = JSON.parse(data);
			} catch (ex) {

			}
			if(xhr.status === 200 && data.status === 'SUCCESS'){
				updateRunning(data, variations);
			} else {
				optimizelyError("An error occurred during the creation of the Optimizely experiment.");
				$(".optimizely_loading").addClass('hidden');
				$(".optimizely_new_experiment").removeClass('hidden');
				console.error("An error occurred during the creation of the Optimizely experiment:", data)
			}
		});
	};

})( jQuery );

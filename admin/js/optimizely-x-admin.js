(function( $ ) {
	'use strict';

	var fixIndentingForCode = function() {
		$("#conditional_activation_code").text(js_beautify($("#conditional_activation_code").text()));
		$("#variation_template").text(js_beautify($("#variation_template").text()));

	}

	var setProjectOptions = function(projects){
		for(var name in projects){
			var id = projects[name];
			var selector = "[value='" + id + "']";
			if($("#project_id > " + selector).length === 0) {
				$("#project_id").append('<option value="' + id + '">' + name + '</option>');
			}
		}
	}

  var loadPage = function(){
		var data = {
		  'action': 'optimizely_x_get_projects'
		};
		$.ajax({
		  url: ajaxurl,
		  data: data,
		  dataType: 'json'
		}).done(function(data){
			if(data && data.status && data.status === 'SUCCESS'){
				$('.authenticated').removeClass('hidden');
				setProjectOptions(data.projects);
				createSnippet();
			} else if (data && data.status && data.status === 'NOTOKEN') {
				$('.authenticated').removeClass('hidden');
			} else {
				$('.unauthenticated .optimizely_authentication .retry-button').removeClass('hidden');
				$('.invalid_token').removeClass('hidden');
				$('.unauthenticated').removeClass('hidden');
			}
			$(".loading").addClass('hidden');
		});
	}

	var createSnippet = function(){
		var id = $( '#project_id' ).val();
		var name = $( '#project_id option:selected' ).text();
		if(id){
			// For display purposes only!
			var project_code = '<script src="//cdn.optimizely.com/js/' + parseInt( id ) + '.js"></script>';

			$( '#project_code' ).text( project_code );
			$( '#project_name' ).val( name );
		} else {
			$( '#project_code' ).text('');
		}
	}

	// Javascript for plugin settings page
	var optimizelyConfigPage = function() {
			/*
			CHOOSING A PROJECT
			When the user selects a project from the dropdown,
			we populate the project code box with the Optimizely snippet for that project ID.
			*/
			$( '#project_id' ).change(function() {
				createSnippet();
			});


	}

	$( document ).ready(function() {
		fixIndentingForCode();
		$( '#optimizely-tabs .authenticated' ).tabs();
		loadPage();
		optimizelyConfigPage();

		$( 'input[name="optimizely_activation_mode"]' ).click(function(){
			if( $( this ).val() == 'conditional' ){
				$( '#optimizely_conditional_activation_code_block' ).show();
			}else{
				$( '#optimizely_conditional_activation_code_block' ).hide();
			}
		});

	});

})( jQuery );

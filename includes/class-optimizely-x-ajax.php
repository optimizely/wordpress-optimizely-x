<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.optimizely.com
 * @since      1.0.0
 *
 * @package    Optimizely_X
 * @subpackage Optimizely_X/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Optimizely_X
 * @subpackage Optimizely_X/admin
 * @author     Your Name <email@example.com>
 */
class Optimizely_X_Api {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $token       The Optimizely API Personal Token
	 */
	public function __construct( ) {

	}

	function get_request($url) {
		$token = get_option( 'optimizely_token' );
		$response = wp_remote_get($url, array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $token
			)
		));
		if(is_array($response) && wp_remote_retrieve_response_code($response) == 200){
			$response['data'] = json_decode($response["body"], true);
		}
		return response;
	}

	function list_request($url){

		return $response;
	}

	function get_projects($response) {
		$result = array();
		$response_body = $response["data"];

		foreach ($response_body as $project) {
			if(!$project['is_classic']) {
				$result[$project['name']] = $project['id'];
			}
		};
		return $result;
	}

	/**
	* Ajax handlers
	*/
	function is_authenticated() {

		$return_value = array();


	  if($token == '') {
			$return_value['authorized'] = "NOTOKEN";
			die(json_encode($return_value));
	  }
	  $response = wp_remote_get('https://api.optimizely.com/v2/projects', array(
		  'headers' => array(
		 	  'Authorization' => 'Bearer ' . $token
		  )
	  ));

	  if( is_array($response) && wp_remote_retrieve_response_code($response) == 200) {
			$return_value['authorized'] = "AUTHENTICATED";
			$return_value['projects'] = $this->get_projects($response);
		  die(json_encode($return_value));
	  } else {
			$return_value['authorized'] = "UNAUTHENCICATED";
	  	die(json_encode($return_value));
		}
	}
}

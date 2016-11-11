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
class Optimizely_X_Ajax {

	private $api;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $token       The Optimizely API Personal Token
	 */
	public function __construct( ) {
		$this->api = new Optimizely_X_Api();
	}

	function get_projects() {
		$result = array();

		$response_body = $this->api->list_request('https://api.optimizely.com/v2/projects', array(), true);

		if(array_key_exists('json', $response_body)){
			foreach ($response_body['json'] as $project) {
				if(!$project['is_classic'] && $project['status'] == 'active') {

					$result[$project['name']] = $project['id'];
				}
			}
		}
		unset($response_body['json']);
		unset($response_body['body']);

		$response_body['projects'] = $result;
		die(json_encode($response_body));
	}

	function replace_placeholder_post_id($condition_code, $post_id){
		return preg_replace('/\$POST_ID/', $post_id, $condition_code);
	}

	function replace_placeholder_new_title($condition_code, $new_title){
		return preg_replace('/\$NEW_TITLE/', $new_title, $condition_code);
	}

	function replace_placeholder_old_title($condition_code, $old_title){
		return preg_replace('/\$OLD_TITLE/', $old_title, $condition_code);
	}

	function generate_uuid(){
		if (function_exists('com_create_guid')){
			return trim(com_create_guid(), '{}');
		} else {
			mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid = substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12);
				return $uuid;
		}
	}

	function get_editor_link($experiment_id){
	  $project_id = get_option('optimizely_project_id');
	  return 'https://app.optimizely.com/v2/projects/'.$project_id.'/experiments/'.$experiment_id;
	}

	function generate_variation($num, $title, $weight, $targeting_id, $post_id){
		$variation_template = get_option('optimizely_variation_template');
		$original_title = $post = get_post( $post_id  )->post_title;

		$code = $this->replace_placeholder_post_id($variation_template, $post_id);
		$code = $this->replace_placeholder_new_title($code, $title);
		$code = $this->replace_placeholder_old_title($code, $original_title);

		$variation = array();
		$variation['name'] = $title;
		$variation['weight'] = $weight;
		$variation['actions'] = array();
		$variation['actions'][] = array();
		$variation['actions'][0]['page_id'] = $targeting_id;
		$variation['actions'][0]['changes'] = array();
		$variation['actions'][0]['changes'][] = array();
		$variation['actions'][0]['changes'][0]['type'] = 'custom_code';
		$variation['actions'][0]['changes'][0]['dependencies'] = array();
		$variation['actions'][0]['changes'][0]['async'] = False;
		$variation['actions'][0]['changes'][0]['value'] = $code;

		return $variation;
	}

	function generate_variations($variation_values, $targeting_id, $post_id){
		$num_variations = count($variation_values) + 1;
		$variation_weight = floor( 10000 / $num_variations );
		$leftover_weight = 10000 - ( $variation_weight * $num_variations );

		$variation_weights = array();
		for($i = 0; $i < $num_variations; $i++){
			if($i < $num_variations - 1){
				$variation_weights[] = $variation_weight;
			} else {
				$variation_weights[] = ($variation_weight + $leftover_weight);
			}
		}

		// Original variation
		$original = array();
		$original['name'] = 'Original';
		$original['weight'] = $variation_weight;
		$original['actions'] = array();
		$original['actions'][] = array();
		$original['actions'][0]['changes'] = array();
		$original['actions'][0]['page_id'] = $targeting_id;

		$variation_data = array();
		$variation_data[] = $original;
		for($i = 0; $i < count($variation_values); $i++){
			$var = $this->generate_variation($i + 1, $variation_values[$i], $variation_weights[$i + 1], $targeting_id, $post_id);
			$variation_data[] = $var;
		}
		return $variation_data;
	}

	/**
	* Ajax handlers
	*/
	function create_experiment() {
		if( !(array_key_exists('entitiy_id', $_POST) && array_key_exists('variations', $_POST)) ){
			die('{"status":"ERROR","code":403}');
		}
		$variations_values = json_decode( stripslashes( $_POST['variations'] ), true);

		$experiment = array();
		$targeting_page = array();
		$event_page = array();

		$post_id = $_POST['entitiy_id'];
		$post = get_post( $post_id  );

		$experiment['name'] = 'Wordpress [' . $post_id . ']: ' . $post->post_title;
		$targeting_page['name'] = 'Wordpress [' . $post_id . ']: ' . $post->post_title . ' targeting page';
		$event_page['name'] = 'Wordpress [' . $post_id . ']: ' . $post->post_title . ' event page';

		$activation_mode = get_option('optimizely_activation_mode');
		if($activation_mode == 'conditional') {
			$targeting_page['activation_type'] = 'polling';
			$targeting_page['activation_code'] = $this->replace_placeholder_post_id(get_option('optimizely_conditional_activation_code'), $post_id);
		} else {
			$targeting_page['activation_type'] = 'immediate';
		}
		$event_page['activation_type'] = 'immediate';
		$targeting_page['edit_url'] = get_permalink( $post->ID );
		$event_page['edit_url'] = get_permalink( $post->ID );

		$url_target_domain = get_site_url();
		$url_target_type = 'substring';
		if ( "" != get_option('optimizely_url_targeting') &&  "" != get_option('optimizely_url_targeting_type') ){
			$url_target_domain = get_option('optimizely_url_targeting');
			$url_target_type = get_option('optimizely_url_targeting_type');
		}
		$targeting_page['page_type'] = 'url_set';
		$targeting_page['conditions'] = '["and",["or",{"match_type":"'.$url_target_type.'","type":"url","value":"'.$url_target_domain.'"}]]';
		$event_page['page_type'] = 'url_set';
		$event_page['conditions'] = '["and",["or",{"match_type":"substring","type":"url","value":"'.get_permalink( $post->ID ).'"}]]';

		$project_id = intval(get_option('optimizely_project_id'));
		$targeting_page['project_id'] = $project_id;
		$event_page['project_id'] = $project_id;

		$targeting_page_response = $this->api->request('POST', 'https://api.optimizely.com/v2/pages', $targeting_page, true);
		if($targeting_page_response['status'] != 'SUCCESS'){
			$targeting_page_response['error'][] = "An error occured during the creation of a targeting page.";
			die(json_encode($targeting_page_response));
		}

		$event_page_response = $this->api->request('POST', 'https://api.optimizely.com/v2/pages', $event_page, true);
		if($event_page_response['status'] != 'SUCCESS'){
			$event_page_response['error'][] = "An error occured during the creation of a event page.";
			die(json_encode($event_page_response));
		}

		$targeting_id = $targeting_page_response['json']['id'];
		$event_id = $event_page_response['json']['id'];
		$variations = $this->generate_variations($variations_values, $targeting_id, $post_id);

		$experiment['project_id'] = $project_id;
		$experiment['status'] = 'paused';
		$experiment['metrics'] = array();
		$experiment['metrics'][] = array();
		$experiment['metrics'][0]['event_id'] = intval($event_id);
		$experiment['metrics'][0]['scope'] = 'visitor';
		$experiment['metrics'][0]['aggregator'] = 'unique';
		$experiment['variations'] = $variations;

		$experiment_response = $this->api->request('POST', 'https://api.optimizely.com/v2/experiments', $experiment, true);
		if($experiment_response['status'] != 'SUCCESS'){
			$result['error'][] = "An error occured during the creation of the experiment.";
			die(json_encode($experiment_response));
		}

		// Cleanup when archived pages work again

		// $delete_targeting_response = $this->api->request('DELETE', 'https://api.optimizely.com/v2/pages/'.$targeting_id, array(), true);
		// if($delete_targeting_response['status'] != 'SUCCESS'){
		// 	$delete_targeting_response['error'][] = "An error occured during the deletion of the targeting page.";
		// 	die(json_encode($delete_targeting_response));
		// }
		// $delete_event_response = $this->api->request('DELETE', 'https://api.optimizely.com/v2/pages/'.$event_id, array(), true);
		// if($delete_event_response['status'] != 'SUCCESS'){
		// 	$delete_event_response['error'][] = "An error occured during the deletion of the event page.";
		// 	die(json_encode($delete_event_response));
		// }

		add_post_meta($post->ID, 'optimizely_experiment_id', $experiment_response['json']['id']);
		add_post_meta($post->ID, 'optimizely_experiment_status', 'paused');

		add_post_meta($post->ID, 'optimizely_variations_num', count($variations_values));

		add_post_meta($post->ID, 'optimizely_editor_link', $this->get_editor_link($experiment_response['json']['id']));


		for($i = 0; $i < count($variations_values); $i++) {
			add_post_meta($post->ID, 'optimizely_variations_'.($i), $variations_values[$i]);
		}
		$experiment_response['json']['link'] = $this->get_editor_link($experiment_response['json']['id']);

		die(json_encode($experiment_response));
	}


	function change_status(){
		if( !(array_key_exists('entitiy_id', $_POST) && array_key_exists('status', $_POST)) ){
			die('{"status":"ERROR","code":403}');
		}
		$post_id = $_POST['entitiy_id'];
		$status = $_POST['status'];

		$experiment_id = get_post_meta($post_id, 'optimizely_experiment_id');
		if(isset($experiment_id) && count($experiment_id) > 0){
		  $experiment_id = $experiment_id[0];
			if($status == 'paused'){
				$experiment_response = $this->api->request('PATCH', 'https://api.optimizely.com/v2/experiments/'.$experiment_id.'?action=publish_start', array(), true);
			} else {
				$experiment_response = $this->api->request('PATCH', 'https://api.optimizely.com/v2/experiments/'.$experiment_id.'?action=pause', array(), true);
			}
			die(json_encode($experiment_response));
		}



	}

}

<?php
/**
 * Optimizely X: API class
 *
 * @package Optimizely_X
 * @since 1.0.0
 */

namespace Optimizely_X;

/**
 * A class to handle communication with the Optimizely REST API.
 *
 * @since 1.0.0
 */
class API {

	/**
	 * The base URL for the Optimizely API, used whenever requests are made.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const BASE_URL = 'https://api.optimizely.com/v2';

	// TODO: Refactor from here down.

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $token       The Optimizely API Personal Token
	 */
	public function __construct( ) {

	}

	public function request($method, $url, $data, $wait_for_rate_limit) {

		// Convert the relative partial URL to a full URL and sanitize it.
		$url = esc_url( self::BASE_URL . $url );
		$result = array();
		$token = get_option( 'optimizely_token' );
		$result['error'] = array();
		if($token == '') {
			$result['status'] = "NOTOKEN";
			$result['error'][] = esc_attr__( 'You have not filled in a token.', 'optimizely-x' );
			$result['code'] = 401;
			return $result;
	  }

		if($method == "POST") {
			$response = wp_remote_post($url, array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $token
				),
				'body' => json_encode($data)
			));
		} else if ($method == "PATCH") {
			$body = '{}';
			if(count($data) != 0) {
				$body = json_encode($data);
			}

			$response = wp_remote_request($url, array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $token
				),
				'body' => $body,
				'method' => 'PATCH'
			));
		} else if ($method == "DELETE") {
			$response = wp_remote_request($url, array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $token
				),
				'body' => json_encode($data),
				'method' => 'DELETE'
			));
		} else {
			$response = wp_remote_get($url, array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $token
				),
				'body' => $data
			));
		}

		if(is_array($response) && wp_remote_retrieve_response_code($response) >= 200 && wp_remote_retrieve_response_code($response) <= 203){
			$result['json'] = json_decode(wp_remote_retrieve_body($response), true);
			$result['status'] = "SUCCESS";
		} else if(is_array($response) && wp_remote_retrieve_response_code($response) == 204) {
			$result['status'] = "SUCCESS";
		} else {
			$result['status'] = "ERROR";
		}
		$result['code'] = wp_remote_retrieve_response_code($response);
		$result['headers'] = wp_remote_retrieve_headers($response);
		$result['body'] = wp_remote_retrieve_body($response);

		if($wait_for_rate_limit && $result['code'] == 429){
			$wait_time = wp_remote_retrieve_header( $response, 'X-RATELIMIT-RESET' );
			usleep($wait_time);
			return $this->get_request($url, $data, $wait_for_rate_limit);
		} else {
			return $result;
		}
	}

	public function list_request( $url, $data = array(), $wait_for_rate_limit = true ) {
		if($wait_for_rate_limit && !array_key_exists('per_page', $data)) {
			$data['per_page'] = 100;
		}
		$response = $this->request("GET", $url, $data, $wait_for_rate_limit);
		if($response['headers'] && $response['headers']['LINK']) {
			$url = $this->get_next_link($response['headers']['LINK']);
			while ($url != ''){
				$additional_response = $this->request("GET", $url, $data, $wait_for_rate_limit);
				$response['code'] = $additional_response['code'];
				$response['headers'] = $additional_response['headers'];
				$response['status'] = $additional_response['status'];
				if(array_key_exists('json', $additional_response)) {
					$response['json'] = array_merge($response['json'], $additional_response['json']);
				}
				$url = $this->get_next_link($response['headers']['LINK']);
			}
		}
		return $response;
	}

  function get_next_link($string) {
    $regex = '/<(https:\/\/[^>]*)>; rel=(\w+)/';
    $links = explode(", ", $string);
    for ($i = 0; $i < count($links); $i++) {
      preg_match($regex, $links[$i], $matches, PREG_OFFSET_CAPTURE);
      if(count($matches) >= 3 && count($matches[2]) != 0 && $matches[2][0] == 'next'){
        return $matches[1][0];
      }
    }
    return '';
  }
}


?>

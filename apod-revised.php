<?php
/*
Plugin Name: Astronomy Picture of the Day Revised
Plugin URI: https://dandulaney.com
Description: A modern Astronomy Picture of the Day plugin using NASA's APOD api, offering both shortcode and Gutenblock implimentations. Shortcode: [apod_revised] Gutenblock: Widgets -> Apod Display
Version: 1.1
Author: Dan Dulaney
Author URI: https://dandulaney.com
License: GPLv2
License URI: 
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Checks to see if Gutenberg is set up on the site before attempting to load blocks (throws a fatal error otherwise)
function apod_setup_blocks() {

	if(function_exists('register_block_type')) {

		include( plugin_dir_path( __FILE__ ) . 'blocks/apod-extended.php');
	}
}
add_action( 'plugins_loaded', 'apod_setup_blocks' );


//Sets / Retrieves data from NASA at most every 3 hours, and passes back to our display function
function apod_revised_check_and_set_transient() {

	// Do we have this information in our transients already?
	$transient = get_transient( 'apod_revised_temp_storage' );
  
	// Yep!  Just return it and we're done.
	if( ! empty( $transient ) ) {

		return $transient;

	// Nope!  We gotta make a call.
	} else {
   
		$response = wp_remote_get('https://api.nasa.gov/planetary/apod?api_key=DEMO_KEY');
		$apod_data = json_decode(wp_remote_retrieve_body($response));
		// Save the API response so we don't have to call again until 3 hours later.
		set_transient(  'apod_revised_temp_storage', $apod_data, DAY_IN_SECONDS/8 );
		return $apod_data;   
	}

}

//Uses the data from the transient / API to display it on the front end. (same function for shortcode AND block)
function apod_revised_display( $atts ) {

	$apod_data = apod_revised_check_and_set_transient();

	if(empty($apod_data)) {

		return 'No APOD data found.';

	}


	$hd_img_url = $apod_data->hdurl;

	$img_url = $apod_data->url;
	
	$description = $apod_data->explanation;

	$date = $apod_data->date;

	$title = $apod_data->title;

	$to_return = "<div>
	<h3>$title ($date)</h3>
	<p>$description</p>
	<a href='$hd_img_url' target='_blank'><img class='apod_revised_img' src='$img_url'></a>
	</div>";

	return $to_return;

}

//Sets up shortcode [apod_revised] to display the above function, [apod_revised_display]
add_shortcode('apod_revised','apod_revised_display');

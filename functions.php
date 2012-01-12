<?php

include __DIR__ . '/plugins/its-shortcodes.php';

function p2mis_favicon() {
	echo '<link rel="shortcut icon" href="', bloginfo('stylesheet_directory'), '/i/favicon.ico" />';
}
add_action( 'wp_head', 'p2mis_favicon' );

function p2mis_js() {
	wp_register_script( 'p2mis_js', get_stylesheet_directory_uri() . '/p2mis.js', false, 1, true );
	wp_enqueue_script( 'p2mis_js' );
}
add_action( 'wp_enqueue_scripts', 'p2mis_js' );

function p2mis_js_early() {
	// The WordPress 3.3.1 version of jquery.color.js substitues 'white' for 'transparent'
	// during CSS animation. Use a newer, smarter release. https://github.com/jquery/jquery-color
	wp_deregister_script( 'jquery-color' );
	wp_register_script( 'jquery-color', get_stylesheet_directory_uri() . '/jquery.color.min.js', 'jquery', '2f7b194334' );
}
add_action( 'wp_enqueue_scripts', 'p2mis_js_early', 1 );

function p2mis_stats() {
	$codereview = get_term_by( 'slug', 'codereview', 'post_tag' );

	$response = array(
		'codereview' => $codereview->count,
	);

	die( json_encode( $response ) );
}
add_action( 'wp_ajax_nopriv_p2mis_stats', 'p2mis_stats' );

function p2mis_skype() {
	static $url = 'http://mystatus.skype.com/smallclassic/%s';

	if ( ! isset( $_GET['p2mis-skype'] ) ) {
		return;
	}

	$username = $_GET['p2mis-skype'];
	$remote = wp_remote_get( sprintf( $url, $username ) );

	if ( ! is_wp_error( $remote ) ) {
		header( "Content-Type: {$remote['content-type']}" );
		die( wp_remote_retrieve_body( $remote ) );
	}

	die();
}
add_action( 'init', 'p2mis_skype', 1 );

/**
 * Format posts/comments with Markdown. Only process the text
 * if it begins with the string ^md and any amount of whitespace.
 **/
function p2mis_comment_markdown( $text ) {
	if( ! function_exists('Markdown') ) {
		return $text;
	}

	if( preg_match( '/^\^md\s+/i', $text ) ) {
		$text = preg_replace( '/^\^md\s+/i', '', $text );
		$text = Markdown($text);
	}

	return $text;
}
add_filter( 'comment_text', 'p2mis_comment_markdown', 1 );
add_filter( 'the_content', 'p2mis_comment_markdown', 1 );

add_filter( 'p2_add_component_post-list-creator', '__return_false' );
add_filter( 'p2_add_component_comment-list-creator', '__return_false' );

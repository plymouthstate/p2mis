<?php
/*
 * Plugin Name: ITS Shortcodes
 * Plugin URI: http://www.plymouth.edu/
 * Description: Some general purpose its shortcodes.
 * Version: 1.0 
 * Author: Matthew Batchelder
 * Author URI: http://borkweb.com/
 * License: GPL2
 */

function markup_wpits_links( $content ) {
	$find = array(
		'/(\ |^)t(\d{3,6})(\b|$)/i', // ticket number
		'/\bpidm:(\d+)\b/i', // pidm
		'/\ba:([0-9a-z_\.]+)\b/i', // ape
	);

	$replace = array(
		'<a href="http://go.plymouth.edu/log/$2" target="_blank">$0</a>', // ticket
		'<a href="http://go.plymouth.edu/ape/$1" target="_blank">$0</a>', // pidm
		'<a href="http://go.plymouth.edu/ape/$1" target="_blank" title="$0">$1</a>', // generic ape
	);

	preg_match_all( '#[^>]+(?=<[^/]*[^a])|[^>]+$#', $content, $matches, PREG_SET_ORDER );

	foreach ( $matches as $val )
		$content = str_replace( $val[0], preg_replace( $find, $replace, $val[0] ), $content );

	return $content;
}
add_filter( 'the_content', 'markup_wpits_links' );
add_filter( 'comment_text', 'markup_wpits_links' );

// [ticket id=123]
function its_shortcodes_ticket($atts) {
	extract($atts);

	return "<a href=\"http://go.plymouth.edu/log/$id\" target=\"_blank\">[$id]</a>";
}
add_shortcode('ticket', 'its_shortcodes_ticket');

// [ape id=123]
function its_shortcodes_ape($atts) {
	extract($atts);

	return "<a href=\"http://go.plymouth.edu/ape/$id\" target=\"_blank\">[$id]</a>";
}
add_shortcode('ape', 'its_shortcodes_ape');

add_filter( 'comment_text', 'do_shortcode', 11 );

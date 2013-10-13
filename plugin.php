<?php
/**
 * Plugin Name: Inline Comments
 * Description: Comment inline!
 * Author: Ryan McCue
 *
 * Inspired by Medium
 */

namespace InlineComments;

require __DIR__ . '/parser.php';
require __DIR__ . '/replacements.php';

add_filter( 'the_content', __NAMESPACE__ . '\\test_content', 1000, 2 );
function test_content($content, $id = null) {
	if ( empty( $id ) ) {
		$id = get_the_ID();
	}

	$parser = new Parser($content, $id);
	return $parser->parse();
}

apply_filters( 'comment_class', __NAMESPACE__ . '\\comment_class', 10, 4 );

function comment_class($classes, $class, $comment_id, $post_id) {
	$attached_paragraph = get_comment_meta( $comment_id, '_inlinecomments_paragraphkey', true );
	if ( empty( $attached_paragraph ) ) {
		return $classes;
	}

	$classes[] = 'inlinecomments-comment';
	$classes[] = sprintf( 'inlinecomments-%s', esc_attr( $attached_paragraph ) );
	return $classes;
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_style' );
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_script' );

function enqueue_style() {
	wp_register_style( 'inlinecomments-genericons', plugins_url( 'static/css/genericons.css', __FILE__ ) );
	wp_enqueue_style( 'inlinecomments', plugins_url( 'static/css/comments.css', __FILE__ ), array( 'inlinecomments-genericons' ) );
}

function enqueue_script() {
	wp_enqueue_script( 'inlinecomments', plugins_url( 'static/js/comments.js', __FILE__ ), array( 'jquery' ) );
}

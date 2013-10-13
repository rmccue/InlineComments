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

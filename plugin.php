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
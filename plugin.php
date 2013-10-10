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

add_filter( 'the_content', __NAMESPACE__ . '\\test_content' );
function test_content($content) {
	$parser = new Parser($content);
	$parser->parse();

	return $content;
}
<?php

namespace InlineComments;

use DOMDocument;
use DOMElement;

/**
 * Content parser
 *
 * Based on Emphasis by NY Times
 * @link https://github.com/NYTimes/Emphasis
 */
class Parser {
	protected $data;
	protected $document;

	protected $paragraphs = array();

	/**
	 * Fake '.' character
	 *
	 * Replaces the real character for non-sentence-defining periods (such as
	 * after abbreviations)
	 */
	const FAKEDOT = '__DOT__';

	/**
	 * Length of the paragraph ID
	 */
	const IDLENGTH = 6;

	public function __construct($data) {
		$this->data = $data;
	}

	public function parse() {
		if ( empty($this->data) ) return;
		$this->document = new DOMDocument();
		$this->document->loadHTML($this->data);

		// Parse the paragraphs out
		$this->get_paragraphs();
	}

	/**
	 * Find the closest paragraph key
	 *
	 * If the paragraph key can be found directly
	 * @param [type] $key [description]
	 * @return [type] [description]
	 */
	public function find($key) {
	/*  From a list of Keys, locate the Key and corresponding Paragraph */
		$paragraphs = $this->get_paragraphs();

		$best_key = false;
		$best_distance = 3;
		$length = self::IDLENGTH / 2;

		// Check for exact match
		if ( ! empty( $paragraphs[ $key ] ) )
			return $key;

		// No exact found, let's see what we can find...
		foreach ($paragraphs as $pkey => $paragraph) {
			$lev_start = levenshtein( substr($key, 0, $length), substr($pkey, 0, $length) );
			$lev_end   = levenshtein( substr($key, -$length),   substr($pkey, -$length) );

			if ( ($lev_start + $lev_end) < $best_distance ) {
				$best_key = $pkey;
				$best_distance = $lev_start + $lev_end;
			}
		}
		return $best_key;
	}

	/**
	 * Get a map of paragraph key to paragraph
	 *
	 * @return array
	 */
	protected function get_paragraphs() {
		if ( ! empty( $this->paragraphs ) ) {
		  return $this->paragraphs;
		}

		$paragraph_elements = $this->document->getElementsByTagName( 'p' );

		foreach ($paragraph_elements as $paragraph) {
			if ( empty( $paragraph->textContent ) ) {
				continue;
			}

			$key = $this->get_key( $paragraph );
			$this->paragraphs[ $key ] = $paragraph;
		}

		return $this->paragraphs;
	}

	/**
	 * Generate a key for a paragraph
	 *
	 * @param string|DOMElement $paragraph
	 * @return string Unique ID
	 */
	public function get_key($paragraph) {
		if ( $paragraph instanceof DOMElement ) {
			$paragraph = $paragraph->textContent;
		}

		// Strip non-alphabetical (and dot) characters
		$text = preg_replace( '/[^A-Z\. ]+/i', '', $paragraph );
		
		if ( empty( $text ) ) {
			return '';
		}

		// Convert text into sentences
		$sentences = $this->get_sentences($text);

		if ( empty( $sentences ) ) {
			return '';
		}

		// Get the first IDLENGTH/2 words from the first and last sentences
		//
		// This is intentionally the same if there's only one sentence, as per
		// Emphasis' behaviour
		$first = self::words_from_sentence( $sentences[0] );
		$last = self::words_from_sentence( array_pop( $sentences ) );

		// Combine words into a single array to pick from
		$k = array_merge( $first, $last );

		// Reduce words to a single key
		$key = array_reduce( $k, __CLASS__ . '::reduce_words' );

		// Check key length
		if (strlen($key) > self::IDLENGTH) {
			$key = substr($key, 0, self::IDLENGTH);
		}

		return $key;
	}

	/**
	 * Reduce a list of words to their first letters in a string
	 *
	 * Use this on an array of words with array_reduce()
	 *
	 * @param string $string
	 * @param string $word
	 * @return string
	 */
	protected static function reduce_words($string, $word) {
		if ( ! is_string( $string ) ) {
			$string = '';
		}
		$string .= $word[0];
		return $string;
	}

	/**
	 * Convert a sentence to a list of words
	 *
	 * Gets the words from a sentence, with a maximum of IDLENGTH / 2 words
	 *
	 * @param string $line
	 * @return array First IDLENGTH / 2 words from the sentence
	 */
	protected static function words_from_sentence($line) {
		// Replace double spaces (and more) with single spaces
		$line = preg_replace('/[\s\s]+/i', ' ', $line);
		return array_slice(explode(' ', $line), 0, self::IDLENGTH / 2);
	}

	/**
	 * Split a paragraph into sentences
	 * 
	 * Exploding by "." is not the definitive way to find sentences, so sanitise
	 * for common other usages of "." first
	 *
	 * @param string $html Paragraph text
	 * @return array Sentences from the paragraph (with extraneous dots replaced by FAKEDOT)
	 */
	protected function get_sentences($text) {
		$numbers = range(0, 9);
		$d       = "__DOT__";

		// Replace " [PREFIX]." with " __DOT__[PREFIX]"
		$spaced_prefixes = array_merge(DotReplacements\get_abbreviations(), $numbers);
		foreach ($spaced_prefixes as $item) {
			$text = str_replace(
				' ' . $item . '.',
				' ' . $item . self::FAKEDOT,
				$text
			);
		}

		// Replace [PREFIX]. with __DOT__[PREFIX]
		$prefixes = array_merge(DotReplacements\get_prefixes(), $numbers);
		foreach ($prefixes as $item) {
			$text = str_replace(
				$item . '.',
				$item . self::FAKEDOT,
				$text
			);
		}

		// Replace .[SUFFIX] with __DOT__[SUFFIX]
		$suffixes = DotReplacements\get_domain_suffixes();
		foreach ($suffixes as $item) {
			$text = str_replace(
				'.' . $item,
				self::FAKEDOT . $item,
				$text
			);
		}

		$lines = array_filter( explode( '. ', $text ) );
		return $lines;
	}
}

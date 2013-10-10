<?php

namespace InlineComments\DotReplacements;

/**
 * Get common abbreviations (e.g. "[abbr].")
 *
 * @return array
 */
function get_abbreviations() {
	$abbreviations = [
		'A',
		'B',
		'C',
		'D',
		'E',
		'F',
		'G',
		'H',
		'I',
		'J',
		'K',
		'L',
		'M',
		'm',
		'N',
		'O',
		'P',
		'Q',
		'R',
		'S',
		'T',
		'U',
		'V',
		'W',
		'X',
		'Y',
		'Z',
		'etc',
		'oz',
		'cf',
		'viz',
		'sc',
		'ca',
		'Ave',
		'St',
	];

	$place_abbrevs = [
		'Calif',
		'Mass',
		'Penn',
		'AK',
		'AL',
		'AR',
		'AS',
		'AZ',
		'CA',
		'CO',
		'CT',
		'DC',
		'DE',
		'FL',
		'FM',
		'GA',
		'GU',
		'HI',
		'IA',
		'ID',
		'IL',
		'IN',
		'KS',
		'KY',
		'LA',
		'MA',
		'MD',
		'ME',
		'MH',
		'MI',
		'MN',
		'MO',
		'MP',
		'MS',
		'MT',
		'NC',
		'ND',
		'NE',
		'NH',
		'NJ',
		'NM',
		'NV',
		'NY',
		'OH',
		'OK',
		'OR',
		'PA',
		'PR',
		'PW',
		'RI',
		'SC',
		'SD',
		'TN',
		'TX',
		'UT',
		'VA',
		'VI',
		'VT',
		'WA',
		'WI',
		'WV',
		'WY',
		'AE',
		'AA',
		'AP',
		'NYC',
		'GB',
		'IRL',
		'IE',
		'UK',
		'GB',
		'FR',
	];

	$domain_prefixes = [
		'www',
	];

	return array_merge( $abbreviations, $place_abbrevs, $domain_prefixes );
}

/**
 * Get common prefixes (e.g. "[abbr].")
 *
 * @return array
 */
function get_prefixes() {
	return [
		'Mr',
		'Ms',
		'Mrs',
		'Miss',
		'Msr',
		'Dr',
		'Gov',
		'Pres',
		'Sen',
		'Prof',
		'Gen',
		'Rep',
		'St',
		'Messrs',
		'Col',
		'Sr',
		'Jf',
		'Ph',
		'Sgt',
		'Mgr',
		'Fr',
		'Rev',
		'No',
		'Jr',
		'Snr',
	];
}

/**
 * Get common suffixes (e.g. ".[abbr]")
 *
 * @return array
 */
function get_domain_suffixes() {
	return [
		'aero',
		'asia',
		'biz',
		'cat',
		'com',
		'coop',
		'edu',
		'gov',
		'info',
		'int',
		'jobs',
		'mil',
		'mobi',
		'museum',
		'name',
		'net',
		'org',
		'pro',
		'tel',
		'travel',
		'xxx',
	];
}

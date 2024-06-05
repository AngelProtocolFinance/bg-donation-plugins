<?php
/**
 * Plugin Name:  Better Giving Donation Form
 * Plugin URI:   https://better.giving
 * Description:  Nonprofits on Better Giving can easily accept donations through our all-in-one embeddable WP Donation Form. Supports credit card, crypto, DAFs, and more!
 * Version:      0.2
 * Author:       BetterGiving
 * Author URI:   https://better.giving
 * Text Domain:  bg-donation-form
 *
 * Better Giving Donation Form is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free 
 * Software Foundation, either version 3 of the License, or any later version.
 *
 * Better Giving Donation Form is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY 
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for 
 * more details.
 */

/**
 * The [bg_donation_form] shortcode.
 *
 * Provides a shortcode to allow nonprofits a simple way to display the Better Giving donation form.
 * Shortcode takes user-defined attributes, allowing users the ability to customize the form's look and feel.
 *
 * @param array  $atts    Shortcode attributes. Default empty.
 * @return string Shortcode output.
 */
function bg_donation_form_shortcode( $atts = [] ) {
	// normalize attribute keys, lowercase
	$atts = array_change_key_case( (array) $atts, CASE_LOWER );

	// override the default attributes with user-passed attributes (if keys match)
	$bg_atts = shortcode_atts(array(
		'id' => 0, // prevents widget from rendering if is not provided
		'currentsplitpct' => 50, // marketplace default
		'splitdisabled' => 0,
		'showdescription' => 1,
		'showtitle' => 1,
		'description' => null,
		'title' => null,
		'methods' => null,
		'accentprimary' => '',
		'accentsecondary' => '',
		'env' => ''
	), $atts);

	/*
	* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	* Set all the user-definable parameters
	* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	*/
	// allow staging environment to be accessed for testing purposes
	// not something most users will need
	if (trim($bg_atts['env']) === "staging") {
		$bg_atts['env'] = 'staging.';
	} else {
		$bg_atts['env'] = '';
	}

	// starting portion of final output string
	$output_head = '<iframe id="bg_donation_form" src="';

	// query string URL
	$q = 'https://' . trim($bg_atts['env']) . 'better.giving/donate-widget/';

	/*
	* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	* Required Fields
	* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	*/
	$q .= $bg_atts['id'] . '?';
	if (is_int($bg_atts['currentsplitpct'])) {
		$q .= 'liquidSplitPct=' . $bg_atts['currentsplitpct'];
	} else {
		$q .= 'liquidSplitPct=50'; // marketplace default
	}
	if ($bg_atts['splitdisabled'] === 1) {
		$q .= '&splitDisabled=true';
	} else {
		$q .= '&splitDisabled=false';
	}
	if ($bg_atts['showdescription'] === 1) {
		$q .= '&isDescriptionTextShown=true';
	} else {
		$q .= '&isDescriptionTextShown=false';
	}

	/*
	* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	* Optional Fields
	* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	*/
	if ($bg_atts['showtitle'] === 1) {
		$q .= '&isTitleShown=true';
	}
	if (!empty($bg_atts['title'])) {
		$q .= '&title=' . esc_attr(trim($bg_atts['title']));
	}
	if (!empty($bg_atts['description'])) {
		$q .= '&description=' . esc_attr(trim($bg_atts['description']));
	}

	// check user passed donation methods for validity
	if (!empty(trim($bg_atts['methods']))) {
		// All possible BG donation method IDs (as of v2.3)
		$donation_methods = array("stripe", "crypto", "daf", "stocks");
		$u_methods = explode(',', trim($bg_atts['methods']));
		$u_methods_final = array_intersect($donation_methods, $u_methods);
		if (!empty($u_methods_final)) {
			$q .= '&methods=' . esc_attr(implode(",", $u_methods_final));
		}
	}

	// Use RegEx to check if valid HEX values passed by user for accent colors
	// NOTE: preg_match returns 1 if match, 0 if no match, when no output array is passed along
	$hex_regex = '/^#(?:(?:[\da-fA-F]{3,6}))$/';
	if (preg_match($hex_regex, trim($bg_atts['accentprimary'])) == 1) {
		$q .= '&accentPrimary=' . esc_attr(trim($bg_atts['accentprimary']));
	} else {
		$q .= '&accentPrimary=' . esc_attr('#2D89C8'); // default BG color
	}
	if (preg_match($hex_regex, trim($bg_atts['accentsecondary'])) == 1) {
		$q .= '&accentSecondary=' . esc_attr(trim($bg_atts['accentsecondary']));
	} else {
		$q .= '&accentSecondary=' . esc_attr('#E6F1F9'); // default BG color
	}

	// close off the query string
	$q .= '"';

	// Set the fixed parameters
	$output_tail = ' width="700"';
	$output_tail .= ' height="900"';
	$output_tail .= ' style="border: 0px;"';
	// close off the iframe
	$output_tail .= '></iframe>';

	return $output_head . $q . $output_tail;
}

function shortcodes_init() {
	add_shortcode('bg_donation_form', 'bg_donation_form_shortcode');
}

add_action( 'init', 'shortcodes_init' );
?>

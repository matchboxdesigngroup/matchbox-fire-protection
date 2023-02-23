<?php
/**
 * Header customizations
 *
 * @package  matchbox-fire-protection
 */

namespace MatchboxFireProtection\Headers;

use MatchboxFireProtection\Singleton;

/**
 * Headers class
 */
class Headers {

	use Singleton;

	/**
	 * Setup module
	 */
	public function setup() {
		add_action( 'wp_headers', [ $this, 'maybe_set_frame_option_header' ], 99, 1 );
	}

	/**
	 * Set the X-Frame-Options header to 'SAMEORIGIN' to prevent clickjacking attacks
	 *
	 * @param string $headers Headers
	 */
	public function maybe_set_frame_option_header( $headers ) {

		// Allow omission of this header
		if ( true === apply_filters( 'matchbox_fire_protection_disable_x_frame_options', false ) ) {
			return $headers;
		}

		// Valid header values are `SAMEORIGIN` (allow iframe on same domain) | `DENY` (do not allow anywhere)
		$header_value               = apply_filters( 'matchbox_fire_protection_x_frame_options', 'SAMEORIGIN' );
		$headers['X-Frame-Options'] = $header_value;
		return $headers;
	}
}

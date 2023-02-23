<?php
/**
 * Author customizations
 *
 * @package  matchbox-fire-protection
 */

namespace MatchboxFireProtection\Authors;

use MatchboxFireProtection\Singleton;

/**
 * Authors class
 */
class Authors {

	use Singleton;

	/**
	 * Setup module
	 *
	 * @since 1.0
	 */
	public function setup() {
		add_action( 'wp', [ $this, 'maybe_disable_author_archive' ] );
	}

	/**
	 * Check to see if author archive page should be disabled for Matchbox user accounts
	 */
	public function maybe_disable_author_archive() {

		if ( ! is_author() || is_admin() ) {
			return;
		}

		$is_author_disabled = false;
		$author             = get_queried_object();
		$current_domain     = wp_parse_url( get_site_url(), PHP_URL_HOST );

		// Domain names that are whitelisted allowed to index Matchbox users to be indexed
		$whitelisted_domains = [
			'matchboxdesigngroup.com',
		];

		// Perform partial match on domains to catch subdomains or variation of domain name
		$filtered_domains = array_filter(
			$whitelisted_domains,
			function( $domain ) use ( $current_domain ) {
				return false !== stripos( $current_domain, $domain );
			}
		);

		// If the query object doesn't have a user e-mail address or the filter is allowing Matchbox authors, bail
		if ( ! empty( $filtered_domains ) ||
			empty( $author->data->user_email ) ||
			true === apply_filters( 'matchbox_fire_protection_allow_matchboxauthor_pages', false ) ) {

			return;

		}

		// E-mail addresses containing matchboxdesigngroup.com.com will be filtered out on the front-end
		if ( false !== stripos( $author->data->user_email, 'matchboxdesigngroup.com' ) ) {
			$is_author_disabled = true;
		}

		if ( true === $is_author_disabled ) {
			\wp_safe_redirect( home_url(), '301', 'Matchbox Fire Protection' );
			exit();
		}
	}
}

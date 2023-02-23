<?php
/**
 * Welcome notification
 *
 * @package  matchbox-fire-protection
 */

namespace MatchboxFireProtection\Notifications;

use MatchboxFireProtection\Singleton;

/**
 * Welcome notification class
 */
class Welcome {

	use Singleton;

	/**
	 * Setup module
	 *
	 * @since 1.0
	 */
	public function setup() {

		if ( ! MATCHBOX_FIRE_PROTECTION_IS_NETWORK ) {
			add_action( 'admin_notices', [ $this, 'notice' ] );
		} else {
			add_action( 'network_admin_notices', [ $this, 'notice' ] );
		}
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_ajax_matchbox_dismiss_welcome', [ $this, 'ajax_dismiss' ] );
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'matchbox-notices', plugins_url( '/dist/js/notices.js', MATCHBOX_FIRE_PROTECTION_FILE ), [ 'jquery' ], MATCHBOX_FIRE_PROTECTION_VERSION, true );

		wp_localize_script(
			'matchbox-notices',
			'matchboxWelcome',
			[
				'nonce' => wp_create_nonce( 'matchbox_welcome_dismiss' ),
			]
		);
	}

	/**
	 * Dismiss welcome message
	 */
	public function ajax_dismiss() {
		if ( ! check_ajax_referer( 'matchbox_welcome_dismiss', 'nonce', false ) ) {
			wp_send_json_error();
			exit;
		}

		if ( MATCHBOX_FIRE_PROTECTION_IS_NETWORK ) {
			update_site_option( 'matchbox_welcome_dismiss', true, false );
		} else {
			update_option( 'matchbox_welcome_dismiss', true, false );
		}

		wp_send_json_success();
	}

	/**
	 * Output dismissable admin welcome notice
	 *
	 * @return void
	 */
	public function notice() {
		$dismissed = MATCHBOX_FIRE_PROTECTION_IS_NETWORK ? get_site_option( 'matchbox_welcome_dismiss', false ) : get_option( 'matchbox_welcome_dismiss', false );
		if ( ! empty( $dismissed ) ) {
			return;
		}

		?>
		<div class="notice notice-info notice-matchbox-fire-protection-welcome is-dismissible">
			<p>
				<?php esc_html_e( 'Thank you for installing the Matchbox Fire Protection plugin.', 'matchbox' ); ?>
			</p>

			<p>
				<?php echo wp_kses_post( __( '<strong>This plugin changes some WordPress default functionality</strong> e.g. requiring authentication for the REST API users endpoint. Make sure to look at the <a href="https://github.com/matchboxdesigngroup/matchbox-fire-protection">readme</a> to understand all the changes it makes.', 'matchbox' ) ); ?>
			</p>
		</div>
		<?php
	}
}

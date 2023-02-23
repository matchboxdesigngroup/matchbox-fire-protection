<?php
/**
 * The Matchbox suppport monitor debugger. This can be enabled by setting the following in wp-config.php:
 * define( 'SUPPORT_MONITOR_DEBUG', true );
 *
 * @since  1.7
 * @package matchbox-fire-protection
 */

namespace MatchboxFireProtection\SupportMonitor;

use MatchboxFireProtection\Singleton;

/**
 * Gutenberg class
 */
class Debug {

	use Singleton;

	/**
	 * Setup module
	 *
	 * @since 1.0
	 */
	public function setup() {

		// No need to run setup functions if debug is disabled
		if ( ! $this->is_debug_enabled() ) {
			return;
		}

		if ( MATCHBOX_FIRE_PROTECTION_IS_NETWORK ) {
			add_action( 'network_admin_menu', [ $this, 'register_network_menu' ] );
		} else {
			add_action( 'admin_menu', [ $this, 'register_menu' ] );
		}

		add_action( 'admin_init', [ $this, 'empty_log' ] );
		add_action( 'admin_init', [ $this, 'test_message' ] );
	}


	/**
	 * Regisers the Support Monitor log link under the 'Tools' menu
	 *
	 * @since 1.0
	 */
	public function register_menu() {

		add_submenu_page(
			'tools.php',
			esc_html__( 'Matchbox Support Monitor Debug', 'matchbox' ),
			esc_html__( 'Matchbox Support Monitor Debug', 'matchbox' ),
			'manage_options',
			'matchbox_support_monitor',
			[ $this, 'debug_screen' ]
		);
	}

	/**
	 * Regisers the Support Monitor log link under the network settings
	 *
	 * @since 1.0
	 */
	public function register_network_menu() {

		add_submenu_page(
			'settings.php',
			esc_html__( 'Matchbox Support Monitor Debug', 'matchbox' ),
			esc_html__( 'Matchbox Support Monitor Debug', 'matchbox' ),
			'manage_network_options',
			'matchbox_support_monitor',
			[ $this, 'debug_screen' ]
		);
	}

	/**
	 * Empty message log
	 *
	 * @since 1.0
	 */
	public function empty_log() {
		if ( empty( $_GET['matchbox_support_monitor_nonce'] ) || ! wp_verify_nonce( $_GET['matchbox_support_monitor_nonce'], 'matchbox_sm_empty_action' ) ) {
			return;
		}

		if ( MATCHBOX_FIRE_PROTECTION_IS_NETWORK ) {
			delete_site_option( 'matchbox_support_monitor_log' );

			wp_safe_redirect( network_admin_url( 'settings.php?page=matchbox_support_monitor' ) );
		} else {
			delete_option( 'matchbox_support_monitor_log' );

			wp_safe_redirect( admin_url( 'tools.php?page=matchbox_support_monitor' ) );
		}
	}

	/**
	 * Send test message
	 *
	 * @since 1.0
	 */
	public function test_message() {
		if ( empty( $_GET['matchbox_support_monitor_nonce'] ) || ! wp_verify_nonce( $_GET['matchbox_support_monitor_nonce'], 'matchbox_sm_test_message_action' ) ) {
			return;
		}

		Monitor::instance()->send_daily_report();

		if ( MATCHBOX_FIRE_PROTECTION_IS_NETWORK ) {
			wp_safe_redirect( network_admin_url( 'settings.php?page=matchbox_support_monitor' ) );
		} else {
			wp_safe_redirect( admin_url( 'tools.php?page=matchbox_support_monitor' ) );
		}
	}

	/**
	 * Output debug screen
	 *
	 * @since 1.0
	 */
	public function debug_screen() {
		if ( MATCHBOX_FIRE_PROTECTION_IS_NETWORK ) {
			$log = get_site_option( 'matchbox_support_monitor_log' );
		} else {
			$log = get_option( 'matchbox_support_monitor_log' );
		}
		?>

		<div class="wrap">
			<h2><?php esc_html_e( 'Support Monitor Message Log', 'matchbox' ); ?></h2>

			<p>
				<a href="<?php echo esc_url( add_query_arg( 'matchbox_support_monitor_nonce', wp_create_nonce( 'matchbox_sm_empty_action' ) ) ); ?>" class="button"><?php esc_html_e( 'Empty Log', 'matchbox' ); ?></a>
				<a href="<?php echo esc_url( add_query_arg( 'matchbox_support_monitor_nonce', wp_create_nonce( 'matchbox_sm_test_message_action' ) ) ); ?>" class="button"><?php esc_html_e( 'Send Test Message', 'matchbox' ); ?></a>
			</p>

			<?php if ( ! empty( $log ) ) : ?>
				<?php foreach ( $log as $message_array ) : ?>
					<?php foreach ( $message_array['messages'] as $message ) : ?>
						<div>
							<strong><?php echo esc_html( gmdate( 'F j, Y, g:i a', $message['time'] ) ); ?>:</strong><br>
							<strong><?php esc_html_e( 'API URL:', 'matchbox' ); ?></strong> <?php echo esc_html( $message_array['url'] ); ?><br>
							<strong><?php esc_html_e( 'Response Code:', 'matchbox' ); ?></strong> <?php echo esc_html( $message_array['messages_response'] ); ?><br>
							<strong><?php esc_html_e( 'Type:', 'matchbox' ); ?></strong> <?php echo esc_html( $message['type'] ); ?><br>
							<strong><?php esc_html_e( 'Group:', 'matchbox' ); ?></strong> <?php echo esc_html( $message['group'] ); ?><br>
							<strong><?php esc_html_e( 'ID:', 'matchbox' ); ?></strong> <?php echo esc_html( $message['message_id'] ); ?><br>
							<pre><?php echo esc_html( wp_json_encode( $message['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) ); ?></pre>
						</div>
					<?php endforeach; ?>
				<?php endforeach; ?>
			<?php else : ?>
				<p><?php esc_html_e( 'No messages.', 'matchbox' ); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Logs an entry if the support monitor debugger has been enabled
	 *
	 * @param string $url - Full URL message was sent to
	 * @param array  $messages - Array of messages
	 * @param array  $response_code - Response code
	 * @since 1.0
	 * @return void
	 */
	public function maybe_add_log_entry( $url, $messages, $response_code ) {

		if ( ! $this->is_debug_enabled() ) {
			return;
		}

		if ( MATCHBOX_FIRE_PROTECTION_IS_NETWORK ) {
			$log = get_site_option( 'matchbox_support_monitor_log', [] );
		} else {
			$log = get_option( 'matchbox_support_monitor_log', [] );
		}

		$prepared = [
			'messages'          => $messages,
			'messages_response' => $response_code,
			'url'               => $url,
		];

		array_unshift( $log, $prepared );

		if ( MATCHBOX_FIRE_PROTECTION_IS_NETWORK ) {
			update_site_option( 'matchbox_support_monitor_log', $log );
		} else {
			update_option( 'matchbox_support_monitor_log', $log );
		}
	}

	/**
	 * Determines whether the debugger has been enabled
	 *
	 * @since 1.0
	 * @return boolean - true if defined and set, false if disabled
	 */
	public function is_debug_enabled() {
		return ( defined( 'SUPPORT_MONITOR_DEBUG' ) && SUPPORT_MONITOR_DEBUG );
	}
}

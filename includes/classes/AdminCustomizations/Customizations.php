<?php
/**
 * Admin customizations
 *
 * @package  matchbox-fire-protection
 */

namespace MatchboxFireProtection\AdminCustomizations;

use MatchboxFireProtection\Singleton;

/**
 * Admin Customizations class
 */
class Customizations {

	use Singleton;

	/**
	 * Setup module
	 *
	 * @since 1.0
	 */
	public function setup() {
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_filter( 'admin_footer_text', [ $this, 'filter_admin_footer_text' ] );
		add_action( 'admin_menu', [ $this, 'register_admin_pages' ] );
		add_filter( 'admin_title', [ $this, 'admin_title_fix' ], 10, 2 );
	}

	/**
	 * Register admin pages with output callbacks
	 */
	public function register_admin_pages() {
		add_submenu_page( null, esc_html__( 'About Matchbox', 'matchbox' ), esc_html__( 'About Matchbox', 'matchbox' ), 'edit_posts', 'matchbox-about', [ $this, 'main_screen' ] );
	}

	/**
	 * Ensure our admin pages get a proper title.
	 *
	 * Because of the empty page parent, the title doesn't get output as expected.
	 *
	 * @param  string $admin_title The page title, with extra context added.
	 * @param  string $title       The original page title.
	 * @return string              The altered page title.
	 */
	public function admin_title_fix( $admin_title, $title ) {
		$screen = get_current_screen();

		wp_enqueue_style( 'matchbox-admin', plugins_url( '/dist/css/admin.css', MATCHBOX_FIRE_PROTECTION_FILE ), array(), MATCHBOX_FIRE_PROTECTION_VERSION );

		if ( 0 !== strpos( $screen->base, 'admin_page_matchbox-' ) ) {
			return $admin_title;
		}

		// There were previously multiple Matchbox pages - leave this basic structure here in case we return to that later.
		if ( 'admin_page_matchbox-about' === $screen->base ) {
			$admin_title = esc_html__( 'About Matchbox', 'matchbox' ) . $admin_title;
		}

		return $admin_title;
	}

	/**
	 * Output about screens
	 */
	public function main_screen() {
		?>
		<div class="wrap about-wrap full-width-layout">

			<h1><?php esc_html_e( 'About Matchbox', 'matchbox' ); ?></h1>

			<div class="about-text">
				<?php
					echo wp_kses_post(
						sprintf(
							// translators: %s is a link to matchboxdesigngroup.com
							__( 'We&#8217;re a full-service digital agency making a better web with finely crafted websites, apps, and tools that drive business results. <a href="%s" target="_blank">Learn more →</a>', 'matchbox' ),
							esc_url( 'https://matchboxdesigngroup.com' )
						)
					);
				?>
			</div>

			<div class="feature-section one-col">
				<h2><?php esc_html_e( 'Thanks for working with Matchbox', 'matchbox' ); ?></h2>

				<p><?php esc_html_e( 'You have the Matchbox Fire Protection plugin installed, which typically means Matchbox built or is supporting your site. The Fire Protection plugin configures WordPress to better protect and inform our clients, including security precautions like blocking unauthenticated access to your content over the REST API, safety measures like preventing code-level changes from being made inside the admin, and some other resources, including a list of vetted plugins we recommend for common use cases and information about us.', 'matchbox' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Setup scripts for customized admin experience
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		wp_enqueue_style( 'matchbox-admin', plugins_url( '/dist/css/admin.css', MATCHBOX_FIRE_PROTECTION_FILE ), array(), MATCHBOX_FIRE_PROTECTION_VERSION );

		if ( 0 === strpos( $screen->base, 'admin_page_matchbox-' ) ) {
			wp_enqueue_style( 'matchbox-about', plugins_url( '/dist/css/matchbox-pages.css', MATCHBOX_FIRE_PROTECTION_FILE ), array(), MATCHBOX_FIRE_PROTECTION_VERSION );
		}
	}

	/**
	 * Enqueue front end scripts
	 */
	public function enqueue_scripts() {
		// Only load css on front-end if the admin bar is showing.
		if ( is_admin_bar_showing() ) {
			wp_enqueue_style( 'matchbox-admin', plugins_url( '/dist/css/admin.css', MATCHBOX_FIRE_PROTECTION_FILE ), array(), MATCHBOX_FIRE_PROTECTION_VERSION );
		}
	}

	/**
	 * Filter admin footer text "Thank you for creating..."
	 *
	 * @return string
	 */
	public function filter_admin_footer_text() {
		$new_text = sprintf( __( 'Thank you for creating with <a href="https://wordpress.org">WordPress</a> and <a href="http://matchboxdesigngroup.com">Matchbox</a>.', 'matchbox' ) );
		return $new_text;
	}
}

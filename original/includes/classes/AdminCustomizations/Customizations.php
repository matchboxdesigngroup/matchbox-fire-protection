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
		add_action( 'admin_bar_menu', [ $this, 'add_about_menu' ], 11 );
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

			<a class="matchbox-badge" href="http://matchboxdesigngroup.com" target="_blank"><span aria-label="<?php esc_html_e( 'Link to matchboxdesigngroup.com', 'matchbox' ); ?>">matchboxdesigngroup.com</span></a>

			<div class="feature-section one-col">
				<h2><?php esc_html_e( 'Thanks for working with Matchbox', 'matchbox' ); ?></h2>

				<p><?php esc_html_e( 'You have the Matchbox Fire Protection plugin installed, which typically means Matchbox built or is supporting your site. The Fire Protection plugin configures WordPress to better protect and inform our clients, including security precautions like blocking unauthenticated access to your content over the REST API, safety measures like preventing code-level changes from being made inside the admin, and some other resources, including a list of vetted plugins we recommend for common use cases and information about us.', 'matchbox' ); ?></p>
			</div>

			<div class="feature-section one-col">
				<h3><?php esc_html_e( 'Making a Better Web', 'matchbox' ); ?></h3>

					<p><?php esc_html_e( 'We make the internet better with consultative creative and engineering services, innovative tools, and dependable products that take the pain out of content creation and management, in service of digital experiences that advance business and marketing objectives. We’re a group of people built to solve problems, made to create, wired to delight.', 'matchbox' ); ?></p>

					<p><?php esc_html_e( 'A customer-centric service model that covers every base, unrivaled leadership and investment in open platforms and tools for digital makers and content creators, and a forward-looking remote work culture make for a refreshing agency experience.', 'matchbox' ); ?></p>
			</div>

			<div class="full-width-img">
				<img src="<?php echo esc_url( plugins_url( '/assets/img/matchbox-image-1.jpg', MATCHBOX_FIRE_PROTECTION_FILE ) ); ?>" alt="">
			</div>

			<div class="feature-section one-col">
				<h3><?php esc_html_e( 'Building Without Boundaries', 'matchbox' ); ?></h3>
				<p><?php esc_html_e( 'The best talent isn’t found in a single zip code, and an international clientele requires a global perspective. From New York City, to the wilds of Idaho, to a dozen countries across Europe, our model empowers us to bring in the best strategists, designers, and engineers, wherever they may live. Veterans of commercial agencies, universities, start ups, nonprofits, and international technology brands, our team has an uncommon breadth.', 'matchbox' ); ?></p>
			</div>

			<div class="full-width-img">
				<img src="<?php echo esc_url( plugins_url( '/assets/img/matchbox-image-2.jpg', MATCHBOX_FIRE_PROTECTION_FILE ) ); ?>" alt="">
			</div>

			<div class="feature-section one-col">
				<h3><?php esc_html_e( 'Full Service Reach', 'matchbox' ); ?></h3>

				<p><strong><?php esc_html_e( 'Strategy:', 'matchbox' ); ?></strong> <?php esc_html_e( 'Should I build an app or a responsive website? Am I maximizing my ad revenue? Why don’t my visitors click “sign up”? We don’t just build: we figure out the plan.', 'matchbox' ); ?></p>

				<p><strong><?php esc_html_e( 'Design:', 'matchbox' ); ?></strong> <?php esc_html_e( 'Inspiring design brings the functional and the beautiful; a delightful blend of art and engineering. We focus on the audience whimsy and relationship between brand and consumer, delivering design that works.', 'matchbox' ); ?></p>

				<p><strong><?php esc_html_e( 'Engineering:', 'matchbox' ); ?></strong> <?php esc_html_e( 'Please. Look under the hood. Our team of sought after international speakers provides expert code review for enterprise platforms like WordPress.com VIP. Because the best website you have is the one that’s up.', 'matchbox' ); ?></p>

				<p><strong><?php esc_html_e( 'Marketing:', 'matchbox' ); ?></strong> <?php esc_html_e( 'Please. Look under the hood. Our team of sought after international speakers provides expert code review for enterprise platforms like WordPress.com VIP. Because the best website you have is the one that’s up.', 'matchbox' ); ?></p>

				<p class="center"><a href="https://matchboxdesigngroup.com" class="button button-hero button-primary" target="_blank"><?php esc_html_e( 'Learn more about Matchbox', 'matchbox' ); ?></a></p>
			</div>
			<hr>
		</div>
		<?php
	}


	/**
	 * Let's setup our Matchbox menu in the toolbar
	 *
	 * @param object $wp_admin_bar Current WP Admin bar object
	 */
	public function add_about_menu( $wp_admin_bar ) {
		if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
			$wp_admin_bar->add_menu(
				array(
					'id'    => 'matchbox',
					'title' => '<div class="matchbox-icon ab-item"><span class="screen-reader-text">' . esc_html__( 'About Matchbox', 'matchbox' ) . '</span></div>',
					'href'  => admin_url( 'admin.php?page=matchbox-about' ),
					'meta'  => array(
						'title' => 'Matchbox',
					),
				)
			);

			$wp_admin_bar->add_menu(
				array(
					'id'     => 'matchbox-about',
					'parent' => 'matchbox',
					'title'  => esc_html__( 'About Matchbox', 'matchbox' ),
					'href'   => esc_url( admin_url( 'admin.php?page=matchbox-about' ) ),
					'meta'   => array(
						'title' => esc_html__( 'About Matchbox', 'matchbox' ),
					),
				)
			);
		}

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

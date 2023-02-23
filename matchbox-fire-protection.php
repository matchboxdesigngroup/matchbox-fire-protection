<?php
/**
 * Plugin Name:       Matchbox Fire Protection
 * Plugin URI:        https://github.com/matchboxdesigngroup/matchbox-fire-protection
 * Description:       The Matchbox Fire Protection plugin configures WordPress to better protect our clients' sites.
 * Version:           1.0.0
 * Author:            Matchbox
 * Author URI:        https://matchboxdesigngroup.com
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       matchbox
 * Domain Path:       /languages/
 * Update URI:        https://github.com/matchboxdesigngroup/matchbox-fire-protection
 *
 * @package           matchbox-fire-protection
 */

namespace MatchboxFireProtection;

use Puc_v4_Factory;

define( 'MATCHBOX_FIRE_PROTECTION_VERSION', '1.0.0' );
define( 'MATCHBOX_FIRE_PROTECTION_DIR', __DIR__ );
define( 'MATCHBOX_FIRE_PROTECTION_FILE', __FILE__ );

require_once __DIR__ . '/vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php';

require_once __DIR__ . '/includes/utils.php';

spl_autoload_register(
	function( $class_name ) {
		$path_parts = explode( '\\', $class_name );

		if ( ! empty( $path_parts ) ) {
			$package = $path_parts[0];

			unset( $path_parts[0] );

			if ( 'MatchboxFireProtection' === $package ) {
				require_once __DIR__ . '/includes/classes/' . implode( '/', $path_parts ) . '.php';
			} elseif ( 'ZxcvbnPhp' === $package ) {
				require_once __DIR__ . '/vendor/bjeavons/zxcvbn-php/src/' . implode( '/', $path_parts ) . '.php';
			}
		}
	}
);

$matchbox_plugin_updater = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/matchboxdesigngroup/matchbox-fire-protection/',
	__FILE__,
	'matchbox-fire-protection'
);

if ( defined( 'MATCHBOX_FIRE_PROTECTION_GITHUB_KEY' ) ) {
	$matchbox_plugin_updater->setAuthentication( MATCHBOX_FIRE_PROTECTION_GITHUB_KEY );
}

$matchbox_plugin_updater->addResultFilter(
	function( $plugin_info, $http_response = null ) {
		$plugin_info->icons = array(
			'svg' => plugins_url( '/assets/img/matchbox.svg', __FILE__ ),
		);

		return $plugin_info;
	}
);

// Define a constant if we're network activated to allow plugin to respond accordingly.
$network_activated = Utils\is_network_activated( plugin_basename( __FILE__ ) );

define( 'MATCHBOX_FIRE_PROTECTION_IS_NETWORK', (bool) $network_activated );

if ( ! defined( 'MATCHBOX_DISABLE_BRANDING' ) || ! MATCHBOX_DISABLE_BRANDING ) {
	AdminCustomizations\Customizations::instance();
}

API\API::instance();
Authentication\Usernames::instance();
Authors\Authors::instance();
Gutenberg\Gutenberg::instance();
Headers\Headers::instance();
Plugins\Plugins::instance();
PostPasswords\PostPasswords::instance();
Notifications\Welcome::instance();

/**
 * We load this later to make sure there are no conflicts with other plugins.
 */
add_action(
	'plugins_loaded',
	function() {
		Authentication\Passwords::instance();
	}
);

/**
 * Disable plugin/theme editor
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

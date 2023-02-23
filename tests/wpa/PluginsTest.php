<?php
/**
 * Test plugin install functionality
 *
 * @package matchbox-fire-protection
 */

/**
 * PHPUnit test class
 */
class PluginsTest extends \WPAcceptance\PHPUnit\TestCase {

	/**
	 * @testdox I see Matchbox suggested plugins
	 */
	public function testMatchboxSuggestedWorking() {
		$I = $this->openBrowserPage();

		$I->loginAs( 'admin' );

		$I->moveTo( 'wp-admin/plugin-install.php' );

		$I->seeLink( 'Matchbox Suggested' );

		// Make sure ElasticPress is shown since this is definitely suggested

		$I->seeElement( '.plugin-card-elasticpress' );
		$I->seeText( 'ElasticPress' );
	}

	/**
	 * @testdox I see a warning when I look at non-suggested plugins
	 */
	public function testNonSuggestedWarning() {
		$I = $this->openBrowserPage();

		$I->loginAs( 'admin' );

		$I->moveTo( 'http://matchbox-fire-protection.test/wp-admin/plugin-install.php?tab=popular' );

		$I->seeText( 'Some plugins may affect display, performance, and reliability' );
	}
}

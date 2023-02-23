<?php
/**
 * Menu tests
 *
 * @package matchbox-fire-protection
 */

/**
 * PHPUnit test class
 */
class MenuTest extends \WPAcceptance\PHPUnit\TestCase {

	/**
	 * @testdox I see the Matchbox logo in admin bar
	 */
	public function testAdminBarMatchboxLogo() {
		$I = $this->openBrowserPage();

		$I->loginAs( 'admin' );
		$I->seeElement( '#wp-admin-bar-matchbox' );
	}
}

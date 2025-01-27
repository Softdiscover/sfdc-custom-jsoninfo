<?php

namespace sfdc\wpJsonPlaceholder\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Brain;

/**
 * TestCase base class.
 */
abstract class TestCase extends BaseTestCase {

    /* Previous code */
 
    /**
     * Runs before each test.
     */
    protected function setUp(): void {
        parent::setUp();
        Brain\Monkey\setUp();
    }
 
    /**
     * Runs after each test.
     */
    protected function tearDown(): void {
        Brain\Monkey\tearDown();
        parent::tearDown();
    }

	/**
	 * Tests for expected output.
	 *
	 * @param string $expected    Expected output.
	 * @param string $description Explanation why this result is expected.
	 */
	protected function expectOutput( $expected, $description = '' ) {
		$output = \ob_get_contents();
		\ob_clean();

		$output   = \preg_replace( '|\R|', "\r\n", $output );
		$expected = \preg_replace( '|\R|', "\r\n", $expected );

		$this->assertEquals( $expected, $output, $description );
	}
}

<?php

namespace plainview\tests;

/**
	@brief		TestCase for Plainview SDK testing.

	@details

	@par		Changelog

	- 20130718	Initial version.

	@since		20130718
	@version	20130718
**/
class TestCase extends \PHPUnit_Framework_TestCase
{
	/**
		@brief		Check if a string contains a substring.
		@since		20130718
	**/
	public function assertStringContains( $needle, $haystack )
	{
		$this->assertTrue( strpos( $needle, $haystack ) !== false );
	}

	/**
		@brief		Check if a string does not contain a substring.
		@since		20130718
	**/
	public function assertStringDoesNotContain( $needle, $haystack )
	{
		$this->assertTrue( strpos( $needle, $haystack ) === false );
	}
}

<?php
/**
 * @package     FrameworkOnFramework.UnitTest
 * @subpackage  Input
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Test class for F0FString
 *
 * @package  FrameworkOnFramework.UnitTest
 * @since    x.y
 */
class F0FInputTestBase extends PHPUnit_Framework_TestCase
{
	/**
	 * [setUp description]
	 *
	 * @return  void
	 */
	protected function setUp()
	{

		$data = array(
					'var1' => 'one',
					'var2' => 'two',
					'int13' => 13,
					'anarray' => array('a' => 'A','B' => 'b','z' => 'Z'),
					'var3' => 'three',
					'var4' => 'four'
				);

		$this->F0FInput = new F0FInput($data);
	}

	/**
	 * [tearDown description]
	 *
	 * @return  void
	 */
	protected function tearDown()
	{
		// Grab a beer
	}

	/*
	 *	TESTS
	 */

	/**
	 * [testGetVar description]
	 *
	 * @return  void
	 */
	public function testGetVar()
	{
		$this->assertEquals('one', $this->F0FInput->get('var1'));
		$this->assertEquals('two', $this->F0FInput->get('var2'));
		$this->assertEquals('three', $this->F0FInput->get('var3'));
		$this->assertEquals('four', $this->F0FInput->get('var4'));
		$this->assertEquals(13, $this->F0FInput->get('int13'));
		$this->assertEquals(true, is_array($this->F0FInput->get('anarray')));
		$this->assertArrayHasKey('a', $this->F0FInput->get('anarray'));
		$this->assertArrayHasKey('B', $this->F0FInput->get('anarray'));
		$this->assertArrayHasKey('z', $this->F0FInput->get('anarray'));
	}

	/**
	 * [testGetVarDefault description]
	 *
	 * @return  void
	 */
	public function testGetVarDefault()
	{
		$this->assertEquals('one', $this->F0FInput->get('var1', 'dumdidilidum'));
		$this->assertEquals('myDefaulfValue', $this->F0FInput->get('var100', 'myDefaulfValue'));
	}

	/**
	 * [testGetData description]
	 *
	 * @return  void
	 */
	public function testGetData()
	{
		$theData = $this->F0FInput->getData();
		$this->assertEquals('one', $theData['var1']);
		$this->assertEquals('two', $theData['var2']);
		$this->assertEquals('three', $theData['var3']);
		$this->assertEquals('four', $theData['var4']);
	}

	/**
	 * [testMagicGet description]
	 *
	 * @return  void
	 */
	public function testMagicGet()
	{
		$this->assertEquals('one', $this->F0FInput->getString('var1'));
		$this->assertEquals(13, $this->F0FInput->getInt('int13'));
	}

}

<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  hal.link
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Test class for F0FHalLink
 */
class F0FHalLinkTest extends PHPUnit_Framework_TestCase
{
	public function getTestCreateNoExceptionData()
	{
		return array(
			array('http://www.example.com/nada.json', false, null, null, null, 'Untemplated link without name should be created'),
			array('http://www.example.com/nada{?id}.json', true, null, null, null, 'Templated link without name should be created'),
			array('http://www.example.com/nada.json', false, 'Test name', null, null, 'Untemplated link with name should be created'),
			array('http://www.example.com/nada{?id}.json', true, 'Test name', null, null, 'Templated link with name should be created'),
			array('http://www.example.com/nada.json', false, 'Test name', 'en-GB', null, 'Untemplated link with hreflang should be created'),
			array('http://www.example.com/nada{?id}.json', true, 'Test name', 'en-GB', null, 'Templated link with hreflang should be created'),
			array('http://www.example.com/nada.json', false, 'Test name', 'en-GB', 'My title', 'Untemplated link with title should be created'),
			array('http://www.example.com/nada{?id}.json', true, 'Test name', 'en-GB', 'My title', 'Templated link with title should be created'),
		);
	}

	/**
	 * @dataProvider	getTestCreateNoExceptionData
	 * @covers			F0FHalLink::__construct
	 */
	public function testCreateNoException($href, $templated, $name, $hreflang, $title, $message)
	{
		try
		{
			$result = new F0FHalLink($href, $templated, $name, $hreflang, $title);
		}
		catch (Exception $exc)
		{
			$this->fail($message);
		}
	}

	public function getTestCreateExceptionData()
	{
		return array(
			array(null, false, null, null, null, 'Null link is not allowed'),
			array('', false, null, null, null, 'Empty link is not allowed'),
		);
	}

	/**
	 * @dataProvider				getTestCreateExceptionData
	 * @covers						F0FHalLink::__construct
	 * @expectedException			RuntimeException
	 * @expectedExceptionMessage	A HAL link must always have a non-empty href
	 */
	public function testCreateException($href, $templated, $name, $hreflang, $title, $message)
	{
		$result = new F0FHalLink($href, $templated, $name, $hreflang, $title);
	}


	public function getTestCheckData()
	{
		return array(
			array('http://www.example.com/nada.json', false, true, 'Absolute URL link should always be considered non-empty'),
			array('nada.json', false, true, 'Relative URL link should always be considered non-empty'),
			array('http://www.example.com/nada{?id}.json', false, true, 'Absolute templated URL should always be considered non-empty'),
			array('nada{?id}.json', false, true, 'Relative templated URL should always be considered non-empty'),
		);
	}

	/**
	 * @dataProvider	getTestCheckData
	 * @covers			F0FHalLink::check
	 */
	public function testCheck($href, $templated, $expect, $message)
	{
		$halLink = new F0FHalLink($href, $templated);
		$this->assertEquals($halLink->check(), $expect, $message);
	}

	public function getTestMagicGetterData()
	{
		return array(
			array('href', 'http://www.example.com/nada.json', 'The href property cannot be gotten'),
			array('templated', false, 'The templated property cannot be gotten'),
			array('name', 'My name', 'The name property cannot be gotten'),
			array('hreflang', 'en-GB', 'The hreflang property cannot be gotten'),
			array('title', 'My title', 'The title property cannot be gotten'),
			array('invalidwhatever', null, 'An invalid property should not be gotten'),
		);
	}

	/**
	 * @dataProvider	getTestMagicGetterData
	 * @covers			F0FHalLink::__get
	 */
	public function testMagicGetter($property, $expect, $message)
	{
		$link = new F0FHalLink('http://www.example.com/nada.json', false, 'My name', 'en-GB', 'My title');

		$this->assertEquals($link->$property, $expect, $message);
	}

	public function getTestMagicSetterData()
	{
		return array(
			array('href', 'http://www.example.com/lol.json', 'The href property cannot be set'),
			array('templated', true, 'The templated property cannot be set'),
			array('name', 'My new name', 'The name property cannot be set'),
			array('hreflang', 'el-CY', 'The hreflang property cannot be set'),
			array('title', 'My new title', 'The title property cannot be set'),
			array('invalidwhatever', 123, 'An invalid property should not be set'),
		);
	}

	/**
	 * @dataProvider	getTestMagicGetterData
	 * @covers			F0FHalLink::__set
	 */
	public function testMagicSetter($property, $expect, $message)
	{
		$link = new F0FHalLink('http://www.example.com/nada.json', false, 'My name', 'en-GB', 'My title');

		$link->$property = $expect;

		$this->assertEquals($link->$property, $expect, $message);
	}
}
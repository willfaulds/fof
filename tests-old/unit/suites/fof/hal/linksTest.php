<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  hal.link
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Test class for F0FHalLinks
 */
class F0FHalLinksTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers F0FHalLinks::addLink
	 */
	function testAddLink()
	{
		// Create a sample link
		$link = new F0FHalLink('http://www.example.com/nada.json');
		$linkset = new F0FHalLinks();

		// ==== Add a link to a link set ====
		$result = $linkset->addLink('custom', $link);

		$links = $this->readAttribute($linkset, '_links');

		$this->assertArrayHasKey('custom', $links, 'The link set must have an array key for our rel');

		$this->assertInternalType('object', $links['custom']);

		$this->assertEquals($link, $links['custom'], 'The link item is not present in the link set');

		// ==== Replace a link in the link set ====
		$newlink = new F0FHalLink('http://www.example.com/yeah.json', false, 'Something');
		$result = $linkset->addLink('custom', $newlink, true);

		$links = $this->readAttribute($linkset, '_links');

		$this->assertArrayHasKey('custom', $links, 'The link set must have an array key for our replaced rel');

		$this->assertInternalType('object', $links['custom']);

		$this->assertEquals($newlink, $links['custom'], 'The replaced link item is not present in the link set');

		// ==== Add a link in the link set ====

		$anotherlink = new F0FHalLink('http://www.example.com/another.json', false, 'Something else');
		$result = $linkset->addLink('custom', $anotherlink, false);

		$links = $this->readAttribute($linkset, '_links');

		$this->assertArrayHasKey('custom', $links, 'The link set must have an array key for our replaced rel');

		$this->assertInternalType('array', $links['custom']);

		$this->assertEquals($newlink, $links['custom'][0]);
		$this->assertEquals($anotherlink, $links['custom'][1]);
	}

	/**
	 * @covers F0FHalLinks::addLinks
	 */
	function testAddLinks()
	{
		// Create a sample link
		$link = new F0FHalLink('http://www.example.com/nada.json');
		$linkset = new F0FHalLinks();

		// ==== Add a link to a link set ====
		$result = $linkset->addLink('custom', $link);

		// ==== Replace the link in the link set ====
		$newlinks = array(
			new F0FHalLink('http://www.example.com/yeah.json', false, 'Something'),
			new F0FHalLink('http://www.example.com/another.json', false, 'Something else')
		);

		$result = $linkset->addLinks('custom', $newlinks, true);

		$links = $this->readAttribute($linkset, '_links');

		$this->assertArrayHasKey('custom', $links, 'The link set must have an array key for our replaced rel');

		$this->assertInternalType('array', $links['custom']);

		$this->assertEquals($newlinks[0], $links['custom'][0]);
		$this->assertEquals($newlinks[1], $links['custom'][1]);

		// ==== Append to an existing set ====
		$result = $linkset->addLink('custom', $link, true);

		$result = $linkset->addLinks('custom', $newlinks, false);

		$links = $this->readAttribute($linkset, '_links');

		$this->assertArrayHasKey('custom', $links, 'The link set must have an array key for our replaced rel');

		$this->assertInternalType('array', $links['custom']);

		$this->assertEquals($link, $links['custom'][0]);
		$this->assertEquals($newlinks[0], $links['custom'][1]);
		$this->assertEquals($newlinks[1], $links['custom'][2]);
	}

	function testGetLinks()
	{
		// Create a sample link
		$newlinks = array(
			'foo' => array(
				new F0FHalLink('http://www.example.com/yeah.json', false, 'Something'),
				new F0FHalLink('http://www.example.com/another.json', false, 'Something else')
			),
			'bar' => array(
				new F0FHalLink('http://www.example.com/foo{?id}.json', true, 'Foo link'),
				new F0FHalLink('http://www.example.com/bar{?id}.json', true, 'Bar link')
			),
		);

		$linkset = new F0FHalLinks();

		$linkset->addLinks('foo', $newlinks['foo']);
		$linkset->addLinks('bar', $newlinks['bar']);

		$links = $linkset->getLinks();

		$this->assertArrayHasKey('foo', $links);
		$this->assertArrayHasKey('bar', $links);

		$this->assertEquals($newlinks['foo'][0], $links['foo'][0]);
		$this->assertEquals($newlinks['foo'][1], $links['foo'][1]);
		$this->assertEquals($newlinks['bar'][0], $links['bar'][0]);
		$this->assertEquals($newlinks['bar'][1], $links['bar'][1]);

		$links = $linkset->getLinks('foo');
		$this->assertEquals($newlinks['foo'][0], $links[0]);
		$this->assertEquals($newlinks['foo'][1], $links[1]);
	}
}
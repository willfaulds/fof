<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Utils
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once __DIR__ . '/eventInspector.php';
require_once __DIR__ . '/eventStub.php';

class F0FUtilsObservableEventTest extends FtestCase
{
	/**
	 * @group F0FUtilsObservableEvent
	 */
	public function test__construct()
	{
		$dispatcher = new F0FUtilsObservableDispatcher;
		$event = new F0FUtilsObservableEventInspector($dispatcher);

		$this->assertThat(
			TestReflection::getValue($event, '_subject'),
			$this->equalTo($dispatcher)
		);
	}

	/**
	 * @group F0FUtilsObservableEvent
	 */
	public function testUpdate()
	{
		$dispatcher = new F0FUtilsObservableDispatcher;
		$event = new F0FUtilsObservableEventInspector($dispatcher);

		$args = array('event' => 'onTestEvent');

		$this->assertThat(
			$event->update($args),
			$this->equalTo('')
		);

		$args = array('event' => 'onTestEvent', 'test1');

		$this->assertThat(
			$event->update($args),
			$this->equalTo('test1')
		);

		$args = array('event' => 'onTestEvent', 'test1', 'test2');

		$this->assertThat(
			$event->update($args),
			$this->equalTo('test1test2')
		);

		$args = array('event' => 'onTestEvent', array('test3', 'test4'));

		$this->assertThat(
			$event->update($args),
			$this->equalTo('test3test4')
		);

		$args = array('event' => 'onTestEvent2');

		$this->assertThat(
			$event->update($args),
			$this->equalTo(null)
		);
	}

	/**
     * @group F0FUtilsObservableEvent
	 */
	public function testUpdateNoArgs()
	{
		// Get a mock for the
		$observable = $this->getMock('Observable', array('attach'));

		// We expect that the attach method of our mock object will be called because
		// when we instantiate an observer it needs something observable to attach itself to
		$observable->expects($this->once())
			->method('attach');

		// We create our object and pass our mock
		$object = new F0FUtilsObservableEventStub($observable);

		// We reset the calls property.  Our stub method will populate this when it gets called
		$object->calls = array();

		// We setup the arguments to pass to update and call it.
		$args = array(
			'event' => 'myEvent'
		);

		// We call update and assert that it returns true (the value from the stub)
		$this->assertThat(
			$object->update($args),
			$this->equalTo(true)
		);

		// First, we want to assert that myEvent was called
		$this->assertThat(
			$object->calls[0]['method'],
			$this->equalTo('myEvent')
		);

		// With no arguments
		$this->assertThat(
			$object->calls[0]['args'],
			$this->equalTo(array())
		);

		// Only once
		$this->assertThat(
			count($object->calls),
			$this->equalTo(1)
		);
	}

	/**
     * @group F0FUtilsObservableEvent
	 */
	public function testUpdateOneArg()
	{
		// Get a mock for the
		$observable = $this->getMock('Observable', array('attach'));

		// We expect that the attach method of our mock object will be called because
		// when we instantiate an observer it needs something observable to attach itself to
		$observable->expects($this->once())
			->method('attach');

		// We create our object and pass our mock
		$object = new F0FUtilsObservableEventStub($observable);

		// We reset the calls property.  Our stub method will populate this when it gets called
		$object->calls = array();

		// We setup the arguments to pass to update and call it.
		$args = array('myFirstArgument');
		$args['event'] = 'myEvent';

		// We call update and assert that it returns true (the value from the stub)
		$this->assertThat(
			$object->update($args),
			$this->equalTo(true)
		);

		// First, we want to assert that myEvent was called
		$this->assertThat(
			$object->calls[0]['method'],
			$this->equalTo('myEvent')
		);

		// With one arguments
		$this->assertThat(
			$object->calls[0]['args'],
			$this->equalTo(array('myFirstArgument'))
		);

		// Only once
		$this->assertThat(
			count($object->calls),
			$this->equalTo(1)
		);
	}

	/**
	 * @group F0FUtilsObservableEvent
	 */
	public function testUpdateMultipleArgs()
	{
		// Get a mock for the
		$observable = $this->getMock('Observable', array('attach'));

		// We expect that the attach method of our mock object will be called because
		// when we instantiate an observer it needs something observable to attach itself to
		$observable->expects($this->once())
			->method('attach');

		// We create our object and pass our mock
		$object = new F0FUtilsObservableEventStub($observable);

		// We reset the calls property.  Our stub method will populate this when it gets called
		$object->calls = array();

		// We setup the arguments to pass to update and call it.
		$args = array('myFirstArgument', 5);
		$args['event'] = 'myEvent';

		// We call update and assert that it returns true (the value from the stub)
		$this->assertThat(
			$object->update($args),
			$this->equalTo(true)
		);

		// First, we want to assert that myEvent was called
		$this->assertThat(
			$object->calls[0]['method'],
			$this->equalTo('myEvent')
		);

		// With one arguments
		$this->assertThat(
			$object->calls[0]['args'],
			$this->equalTo(array('myFirstArgument', 5))
		);

		// Only once
		$this->assertThat(
			count($object->calls),
			$this->equalTo(1)
		);
	}

	/**
	 * @group F0FUtilsObservableEvent
	 */
	public function testUpdateBadEvent()
	{
		// Get a mock for the
		$observable = $this->getMock('Observable', array('attach'));

		// We expect that the attach method of our mock object will be called because
		// when we instantiate an observer it needs something observable to attach itself to
		$observable->expects($this->once())
			->method('attach');

		// We create our object and pass our mock
		$object = new F0FUtilsObservableEventStub($observable);

		// We reset the calls property.  Our stub method will populate this when it gets called
		$object->calls = array();

		// We setup the arguments to pass to update and call it.
		$args = array('myFirstArgument');
		$args['event'] = 'myNonExistentEvent';

		// We call update and assert that it returns null (the value from the stub)
		$this->assertThat(
			$object->update($args),
			$this->equalTo(null)
		);

		// First, we want to assert that no methods were called
		$this->assertThat(
			count($object->calls),
			$this->equalTo(0)
		);
	}
}

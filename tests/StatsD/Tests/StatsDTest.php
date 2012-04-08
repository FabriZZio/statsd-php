<?php
namespace StatsD\Tests;

use StatsD\StatsD;
use StatsD\SocketHandler;

class StatsDTest extends \PHPUnit_Framework_TestCase
{
	/** @test **/
	public function StatCanBeIncremented() {

		$handler = $this->createHandlerMock();
		$handler->expects($this->once())
				->method('write')
				->with(true, 'stats.test:1|c');

		$statsD = new StatsD($handler);
		$statsD->increment('stats.test');
	}

	/** @test **/
	public function StatCanBeDecremented() 
	{
		$handler = $this->createHandlerMock();
		$handler->expects($this->once())
				->method('write')
				->with(true, 'stats.test:-1|c');

		$statsD = new StatsD($handler);
		$statsD->decrement('stats.test');
	}

	/** @test **/
	public function TimedStatCanBeUpdated() 
	{
		$handler = $this->createHandlerMock();
		$handler->expects($this->once())
				->method('write')
				->with(true, 'stats.test:100|ms');

		$statsD = new StatsD($handler);
		$statsD->time('stats.test', 100);
	}

	/** @test **/
	public function GenericStatCanBeSent()
	{
		$handler = $this->createHandlerMock();
		$handler->expects($this->once())
				->method('write')
				->with(true, 'stats.test:123|c');

		$statsD = new StatsD($handler);
		$statsD->update('stats.test', 123);
	}

	/** @test **/
	public function GenericStatsCanBeSent()
	{
		$handler = $this->createHandlerMock();
		$handler->expects($this->exactly(2))
				->method('write');
				//->with(true, 'stats.test:123|c'); // @todo test for second execution's parameters

		$statsD = new StatsD($handler);
		$statsD->update(array('stats.test', 'stats.test2'), 123);
	}

	/** @test **/
	public function CanBeSampled()
	{
		$this->markTestSkipped('Using mt_rand() numbers internally. Any way to test this?');
	}

	protected function createHandlerMock() {
		$handler = $this->getMock('StatsD\NodeJsHandler', array(), array(), '', false);
		$handler->expects($this->any())
				->method('open')
				->will($this->returnValue(true));
		return $handler;
	}
}
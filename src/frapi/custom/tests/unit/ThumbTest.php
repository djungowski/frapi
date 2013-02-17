<?php
namespace Score11\Frapi;

require_once CUSTOM_MODEL . '/Thumb.php';

class ThumbTest extends \PHPUnit_Framework_TestCase
{
	public function testGetTrendUp()
	{
		$thumb = new Thumb();
		$expected = 'up';
		// Ab 6.6 gibts Daumen hoch
		$actual = $thumb->getTrend(true, 6.6);
		self::assertSame($expected, $actual);
	}
	
	public function testGetTrendMiddle()
	{
		$thumb = new Thumb();
		$expected = 'middle';
		// Zwischen 3.5 und 6.5 gibts mittel
		$actual = $thumb->getTrend(true, 3.5);
		self::assertSame($expected, $actual);
		
		$actual = $thumb->getTrend(true, 6.5);
		self::assertSame($expected, $actual);
	}
	
	public function testGetTrendDown()
	{
		$thumb = new Thumb();
		$expected = 'down';
		// Bis 3.4 gibts Daumen runter
		$actual = $thumb->getTrend(true, 3.4);
		self::assertSame($expected, $actual);
	}
	
	public function testGetTrendNone()
	{
		$thumb = new Thumb();
		$expected = 'none';
		$actual = $thumb->getTrend(false, 0);
		self::assertSame($expected, $actual);
	}
}
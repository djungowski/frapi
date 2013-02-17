<?php
namespace Score11\Frapi;

require_once CUSTOM_MODEL . '/MovieImage.php';

class MovieImageTest extends \PHPUnit_Framework_TestCase
{
	public function testGetLinkWithoutImage()
	{
		$image = new MovieImage(28786, 'n');
		$expected = "img/logo-movie.png";
		$actual = $image->getLink();
		self::assertSame($expected, $actual);
	}
	
	public function testGetLinkWithImage()
	{
		$image = new MovieImage(28786);
		$expected = "http://www.score11.de/p/86/28786";
		$actual = $image->getLink();
		self::assertSame($expected, $actual);
	}
	
	public function testGetLinkWith1DigitFolder()
	{
		$image = new MovieImage(5);
		$expected = "http://www.score11.de/p/05/5";
		$actual = $image->getLink();
		self::assertSame($expected, $actual);
	}
}
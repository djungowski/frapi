<?php
namespace Score11\Frapi\Test\Integration;

use Score11\Frapi\Test\Library\ApiCall;

class SearchTest extends \PHPUnit_Framework_Testcase
{
	public function testQueryWithOneWord()
	{
		$api = new ApiCall('search/expendables');
		$content = $api->request();
		$this->assertInternalType('array', $content);
		$this->assertEquals(4, count($content['movies']));
		$this->assertSame('The Expendables 3', $content['movies'][3]['title']);
	}
}
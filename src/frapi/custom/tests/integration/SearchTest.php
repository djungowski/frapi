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
		$this->assertEquals(count($content), 4);
		$this->assertSame('The Expendables 3', $content[3]['ori']);
	}
}
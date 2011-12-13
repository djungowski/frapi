<?php
namespace Score11\Frapi\Test\Integration\Rating;

use Score11\Frapi\Test\Library\ApiCall;

class LatestTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $api = new ApiCall('rating/latest');
        $content = $api->request();
        $this->assertInternalType('array', $content);
        // Es gibt genau 10 Eintraege
        $this->assertEquals(10, count($content));
    }
}
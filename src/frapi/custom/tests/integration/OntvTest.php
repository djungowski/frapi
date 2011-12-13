<?php
namespace Score11\Frapi\Test\Integration;

use Score11\Frapi\Test\Library\ApiCall;

class OntvTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $api = new ApiCall('ontv/list');
        $content = $api->request();
        $this->assertInternalType('array', $content);
        // Es gibt mind 1 Eintrag (TV Listen eintraege werden gruppiert ausgegeben)
        $this->assertTrue(count($content) >= 1);
    }
}
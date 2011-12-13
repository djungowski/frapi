<?php
namespace Score11\Frapi\Test\Integration\Movie;

use Score11\Frapi\Test\Library\ApiCall;

class Test extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        // Film "Carnage"
        $call = new ApiCall('movie/56814');
        $content = $call->request();
        $this->assertInternalType('array', $content);
        // Paar random attribute ausprobieren
        $this->assertArrayHasKey('titleview', $content);
        $this->assertSame('110690', $content['ori']);
        $this->assertSame('Roman Polanski', $content['regie']);
    }
}
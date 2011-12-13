<?php
namespace Score11\Frapi\Test\Integration\Comment;

use Score11\Frapi\Test\Library;

class LatestTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $call = new Library\ApiCall('comment/latest');
        $content = $call->request();
        $this->assertInternalType('array', $content);
        // Standardmaessig gibt es 5 neueste Kommentare
        $this->assertEquals(5, count($content));
    }
}
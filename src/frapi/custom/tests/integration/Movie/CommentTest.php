<?php
namespace Score11\Frapi\Test\Integration\Movie;

use Score11\Frapi\Test\Library;

class CommentTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        // Kommentare fuer den Film "Carnage"
        $call = new Library\ApiCall('movie/56814/comments');
        $content = $call->request();
        $this->assertInternalType('array', $content);
        // Es gibt mindestens 3 Kommentare
        $this->assertTrue(count($content) >= 3);
    }
}
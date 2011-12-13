<?php
namespace Score11\Frapi\Test\Integration\User;

use Score11\Frapi\Test\Library\ApiCall;

class LoginTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        // Get geht beim user login nicht
        $api = new ApiCall('user/login');
        $content = $api->request();
        // Es kommt trotzdem ein Array zurueck...
        $this->assertInternalType('array', $content);
        // ... das uns einen NO_GET error liefert
        $this->assertSame('NO_GET', $content['errors'][0]['name']);
    }
    
    public function testPostWithoutCredentials()
    {
        $api = new ApiCall('user/login');
        $content = $api->request('POST');
        $this->assertInternalType('array', $content);
        // Gelieferter Fehler: ERROR_MISSING_REQUEST_ARG
        $this->assertSame('ERROR_MISSING_REQUEST_ARG', $content['errors'][0]['name']);
        $this->assertSame('Required Parameters: login, password', $content['errors'][0]['at']);
    }
}
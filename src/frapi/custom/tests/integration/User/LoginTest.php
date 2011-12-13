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
    
    public function testPostWithWrongCredentials()
    {
        $api = new ApiCall('user/login');
        $credentials = array(
            'login' => 'lebowski',
            'password' => 'somerandomstring' 
        );
        $api->setPostParameters($credentials);
        $content = $api->request('POST');
        $this->assertInternalType('array', $content);
        // Login darf nicht erfolgreich gewesen sein
        $this->assertFalse($content['success']);
        $this->assertSame('lebowski', $content['login']);
    }
    
    public function testPostWithCorrectCredentials()
    {
        $api = new ApiCall('user/login');
        $credentials = array(
            'login' => 'lebowski',
            'password' => '0ee9874e0d89f70758b86fe6e282db8f465d1c0d' 
        );
        $api->setPostParameters($credentials);
        $content = $api->request('POST');
        $this->assertInternalType('array', $content);
        // Login darf nicht erfolgreich gewesen sein
        $this->assertTrue($content['success']);
        $this->assertSame('lebowski', $content['login']);
        // Es gibt einen Array Key "token"
        $this->assertArrayHasKey('token', $content);
    }
    
    public function testPostWithDeactivatedUser()
    {
        $api = new ApiCall('user/login');
        $credentials = array(
            'login' => 'queenofscots',
            'password' => '54670e8b683451623ddb133b8977e4deca9e75d7'
        );
        $api->setPostParameters($credentials);
        $content = $api->request('POST');
        $this->assertInternalType('array', $content);
        // Login darf nicht erfolgreich gewesen sein
        $this->assertFalse($content['success']);
        $this->assertSame('queenofscots', $content['login']);
    }
}
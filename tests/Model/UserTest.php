<?php
namespace Tests\Api\Model;

use Api\Model\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    private $subject;

    public function setUp()
    {
        $this->subject = new User("Server Ina");
    }

    public function testPasswordIsNotStoredPlain()
    {
    	$password = "ezpass123";

    	$this->subject->setPassword($password);

        $this->assertNotEquals($this->subject->getPassword(), $password);
    }
}
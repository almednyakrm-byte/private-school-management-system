<?php

namespace App\Tests\Unit\Auth;

use App\Auth\Auth;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class TestAuth extends TestCase
{
    private $auth;
    private $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->auth = new Auth($this->connection);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ?', [$username])
            ->willReturn([
                ['id' => 1, 'username' => $username, 'password' => $password],
            ]);

        $result = $this->auth->login($username, $password);

        $this->assertTrue($result);
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ?', [$username])
            ->willReturn([]);

        $result = $this->auth->login($username, $password);

        $this->assertFalse($result);
    }

    public function testRegisterSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('INSERT INTO users (username, password) VALUES (?, ?)', [$username, $password])
            ->willReturn(true);

        $result = $this->auth->register($username, $password);

        $this->assertTrue($result);
    }

    public function testRegisterFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('INSERT INTO users (username, password) VALUES (?, ?)', [$username, $password])
            ->willThrowException(new Exception('Database error'));

        $result = $this->auth->register($username, $password);

        $this->assertFalse($result);
    }
}
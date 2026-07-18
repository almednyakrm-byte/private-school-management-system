<?php

namespace App\Tests\Controller;

use App\Controller\TeachersController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestTeachers extends TestCase
{
    private $teachersController;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->teachersController = new TeachersController($this->pdoMock);
    }

    public function testGetTeachers()
    {
        $expectedResponse = ['teachers' => ['teacher1', 'teacher2']];
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM teachers')
            ->willReturn($this->createMock(PDOStatement::class));

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expectedResponse);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM teachers')
            ->willReturn($stmtMock);

        $response = $this->teachersController->getTeachers();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateTeacher()
    {
        $expectedResponse = ['message' => 'Teacher created successfully'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO teachers (name) VALUES (:name)')
            ->willReturn($this->createMock(PDOStatement::class));

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->with(['name' => 'teacher1']);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO teachers (name) VALUES (:name)')
            ->willReturn($stmtMock);

        $response = $this->teachersController->createTeacher('teacher1');
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateTeacher()
    {
        $expectedResponse = ['message' => 'Teacher updated successfully'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE teachers SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->with(['name' => 'teacher1', 'id' => 1]);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE teachers SET name = :name WHERE id = :id')
            ->willReturn($stmtMock);

        $response = $this->teachersController->updateTeacher(1, 'teacher1');
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteTeacher()
    {
        $expectedResponse = ['message' => 'Teacher deleted successfully'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM teachers WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->with(['id' => 1]);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM teachers WHERE id = :id')
            ->willReturn($stmtMock);

        $response = $this->teachersController->deleteTeacher(1);
        $this->assertEquals($expectedResponse, $response);
    }
}



// TeachersController.php

namespace App\Controller;

use PDO;

class TeachersController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getTeachers()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM teachers');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createTeacher($name)
    {
        $stmt = $this->pdo->prepare('INSERT INTO teachers (name) VALUES (:name)');
        $stmt->execute(['name' => $name]);
        return ['message' => 'Teacher created successfully'];
    }

    public function updateTeacher($id, $name)
    {
        $stmt = $this->pdo->prepare('UPDATE teachers SET name = :name WHERE id = :id');
        $stmt->execute(['name' => $name, 'id' => $id]);
        return ['message' => 'Teacher updated successfully'];
    }

    public function deleteTeacher($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM teachers WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return ['message' => 'Teacher deleted successfully'];
    }
}
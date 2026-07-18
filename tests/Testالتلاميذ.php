<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\StudentsController;
use App\Repository\StudentsRepository;
use App\Entity\Students;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testالتلاميذ extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(StudentsRepository::class);
        $this->controller = new StudentsController($this->repository);
    }

    public function testGetStudents()
    {
        $students = [
            new Students('1', 'John Doe', 'john@example.com'),
            new Students('2', 'Jane Doe', 'jane@example.com'),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($students);

        $response = $this->controller->getStudents();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($students), $response->getContent());
    }

    public function testGetStudent()
    {
        $student = new Students('1', 'John Doe', 'john@example.com');

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($student);

        $response = $this->controller->getStudent('1');
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($student), $response->getContent());
    }

    public function testGetStudentNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $this->controller->getStudent('1');
    }

    public function testCreateStudent()
    {
        $student = new Students('1', 'John Doe', 'john@example.com');

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with([
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]);

        $response = $this->controller->createStudent($student);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateStudent()
    {
        $student = new Students('1', 'John Doe', 'john@example.com');

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($student);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with([
                'id' => '1',
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]);

        $response = $this->controller->updateStudent('1', $student);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteStudent()
    {
        $student = new Students('1', 'John Doe', 'john@example.com');

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($student);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with([
                'id' => '1',
            ]);

        $response = $this->controller->deleteStudent('1');
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
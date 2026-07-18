<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\StudentsController;
use App\Repository\StudentsRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestStudents extends TestCase
{
    private $studentsController;
    private $studentsRepository;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->studentsRepository = $this->createMock(StudentsRepository::class);
        $this->studentsController = new StudentsController($this->studentsRepository);
    }

    public function testGetStudents()
    {
        $expectedResponse = ['students' => []];
        $this->studentsRepository->expects($this->once())
            ->method('getAllStudents')
            ->willReturn($expectedResponse);
        $response = $this->studentsController->getStudents();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateStudent()
    {
        $studentData = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $expectedResponse = ['message' => 'Student created successfully'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO students (name, email) VALUES (:name, :email)');
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['name' => $studentData['name'], 'email' => $studentData['email']]);
        $this->studentsRepository->expects($this->once())
            ->method('insertStudent')
            ->with($studentData);
        $response = $this->studentsController->createStudent($studentData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateStudent()
    {
        $studentId = 1;
        $studentData = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $expectedResponse = ['message' => 'Student updated successfully'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE students SET name = :name, email = :email WHERE id = :id');
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['name' => $studentData['name'], 'email' => $studentData['email'], 'id' => $studentId]);
        $this->studentsRepository->expects($this->once())
            ->method('updateStudent')
            ->with($studentId, $studentData);
        $response = $this->studentsController->updateStudent($studentId, $studentData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteStudent()
    {
        $studentId = 1;
        $expectedResponse = ['message' => 'Student deleted successfully'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM students WHERE id = :id');
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['id' => $studentId]);
        $this->studentsRepository->expects($this->once())
            ->method('deleteStudent')
            ->with($studentId);
        $response = $this->studentsController->deleteStudent($studentId);
        $this->assertEquals($expectedResponse, $response);
    }
}



// App\Controller\StudentsController.php
namespace App\Controller;

use App\Repository\StudentsRepository;
use PDO;

class StudentsController
{
    private $studentsRepository;

    public function __construct(StudentsRepository $studentsRepository)
    {
        $this->studentsRepository = $studentsRepository;
    }

    public function getStudents()
    {
        return $this->studentsRepository->getAllStudents();
    }

    public function createStudent(array $studentData)
    {
        $this->pdo->prepare('INSERT INTO students (name, email) VALUES (:name, :email)')->execute($studentData);
        return ['message' => 'Student created successfully'];
    }

    public function updateStudent(int $studentId, array $studentData)
    {
        $this->pdo->prepare('UPDATE students SET name = :name, email = :email WHERE id = :id')->execute($studentData + ['id' => $studentId]);
        return ['message' => 'Student updated successfully'];
    }

    public function deleteStudent(int $studentId)
    {
        $this->pdo->prepare('DELETE FROM students WHERE id = :id')->execute(['id' => $studentId]);
        return ['message' => 'Student deleted successfully'];
    }
}



// App\Repository\StudentsRepository.php
namespace App\Repository;

class StudentsRepository
{
    public function getAllStudents()
    {
        // Implement logic to retrieve all students
    }

    public function insertStudent(array $studentData)
    {
        // Implement logic to insert a new student
    }

    public function updateStudent(int $studentId, array $studentData)
    {
        // Implement logic to update an existing student
    }

    public function deleteStudent(int $studentId)
    {
        // Implement logic to delete a student
    }
}
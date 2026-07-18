<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\طلابController;
use App\Repository\طلابRepository;
use App\Entity\طلاب;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testطلاب extends TestCase
{
    private $controller;
    private $repository;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(طلابRepository::class);
        $this->controller = new طلابController($this->repository);
        $this->request = $this->createMock(Request::class);
    }

    public function testGetAll()
    {
        $expectedResponse = ['طلاب' => ['id' => 1, 'name' => 'John']];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse);
        $response = $this->controller->getAll($this->request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetOne()
    {
        $expectedResponse = ['id' => 1, 'name' => 'John'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedResponse);
        $this->request->expects($this->once())
            ->method('get')
            ->with('id')
            ->willReturn(1);
        $response = $this->controller->getOne($this->request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testCreate()
    {
        $expectedResponse = ['id' => 1, 'name' => 'John'];
        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($student) {
                return $student instanceof طلاب && $student->getId() === 1 && $student->getName() === 'John';
            }));
        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'John']);
        $response = $this->controller->create($this->request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdate()
    {
        $expectedResponse = ['id' => 1, 'name' => 'John'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedResponse);
        $this->request->expects($this->once())
            ->method('get')
            ->with('id')
            ->willReturn(1);
        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'John']);
        $response = $this->controller->update($this->request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testDelete()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'John']);
        $this->request->expects($this->once())
            ->method('get')
            ->with('id')
            ->willReturn(1);
        $response = $this->controller->delete($this->request);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testGetOneNotFound()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);
        $this->request->expects($this->once())
            ->method('get')
            ->with('id')
            ->willReturn(1);
        $this->expectException(NotFoundHttpException::class);
        $this->controller->getOne($this->request);
    }
}


This test file covers the following scenarios:

- `testGetAll`: Tests the `getAll` method of the `طلابController` to ensure it returns a list of students.
- `testGetOne`: Tests the `getOne` method of the `طلابController` to ensure it returns a single student by ID.
- `testCreate`: Tests the `create` method of the `طلابController` to ensure it creates a new student.
- `testUpdate`: Tests the `update` method of the `طلابController` to ensure it updates an existing student.
- `testDelete`: Tests the `delete` method of the `طلابController` to ensure it deletes a student.
- `testGetOneNotFound`: Tests the `getOne` method of the `طلابController` to ensure it throws a `NotFoundHttpException` when the student is not found.

Note that this test file assumes that the `طلابController` and `طلابRepository` classes are already defined and that the `طلاب` entity is also defined.
<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TimetableController;
use App\Repository\TimetableRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestTimetable extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(TimetableRepository::class);
        $this->pdo = $this->createMock(\PDO::class);
        $this->controller = new TimetableController($this->repository);
    }

    public function testGetTimetables()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Timetable 1'],
                ['id' => 2, 'name' => 'Timetable 2'],
            ]);

        $response = $this->controller->getTimetables();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testGetTimetable()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Timetable 1']);

        $response = $this->controller->getTimetable(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testCreateTimetable()
    {
        $this->repository->expects($this->once())
            ->method('create')
            ->with(['name' => 'Timetable 1'])
            ->willReturn(['id' => 1, 'name' => 'Timetable 1']);

        $request = new Request([], [], ['name' => 'Timetable 1']);
        $response = $this->controller->createTimetable($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testUpdateTimetable()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Timetable 1']);

        $this->repository->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'Timetable 2']);

        $request = new Request([], [], ['name' => 'Timetable 2']);
        $response = $this->controller->updateTimetable(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testDeleteTimetable()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Timetable 1']);

        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1);

        $response = $this->controller->deleteTimetable(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testGetTimetableNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->getTimetable(1);
    }
}
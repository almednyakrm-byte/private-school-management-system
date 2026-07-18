<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\مركباتController;
use App\Repository\مركباتRepository;
use App\Entity\مركبات;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testالمركبات extends TestCase
{
    private $controller;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(مركباتRepository::class);
        $this->controller = new مركباتController($this->repository);
    }

    public function testGetAll()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetOne()
    {
        $expectedResponse = new JsonResponse(['data' => new مركبات()]);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new مركبات());
        $response = $this->controller->getOne(1);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreate()
    {
        $expectedResponse = new JsonResponse(['data' => new مركبات()]);
        $this->repository->expects($this->once())
            ->method('create')
            ->with(new مركبات())
            ->willReturn(new مركبات());
        $request = new Request([], [], ['data' => new مركبات()]);
        $response = $this->controller->create($request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate()
    {
        $expectedResponse = new JsonResponse(['data' => new مركبات()]);
        $this->repository->expects($this->once())
            ->method('update')
            ->with(1, new مركبات())
            ->willReturn(new مركبات());
        $request = new Request([], [], ['data' => new مركبات()]);
        $response = $this->controller->update(1, $request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete()
    {
        $expectedResponse = new JsonResponse(['message' => 'Deleted successfully']);
        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(true);
        $response = $this->controller->delete(1);
        $this->assertEquals($expectedResponse, $response);
    }
}
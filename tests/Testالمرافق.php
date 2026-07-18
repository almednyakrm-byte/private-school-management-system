<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\مرافقController;
use App\Repository\مرافقRepository;
use App\Entity\مرافق;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\Query\QueryException;

class Testالمرافق extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(مرافقRepository::class);
        $this->controller = new مرافقController($this->repository);
    }

    public function testGetAll()
    {
        $expectedData = [
            new مرافق(),
            new مرافق(),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedData);

        $response = $this->controller->getAll();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedData), $response->getContent());
    }

    public function testGetById()
    {
        $id = 1;
        $expectedData = new مرافق();

        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedData);

        $response = $this->controller->getById($id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedData), $response->getContent());
    }

    public function testGetByIdNotFound()
    {
        $id = 1;

        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->getById($id);
    }

    public function testCreate()
    {
        $data = [
            'name' => 'Test',
            'description' => 'Test description',
        ];

        $expectedData = new مرافق();

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO المرافق (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with($data);

        $this->repository->expects($this->once())
            ->method('find')
            ->with($this->pdo->lastInsertId())
            ->willReturn($expectedData);

        $response = $this->controller->create($data);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedData), $response->getContent());
    }

    public function testUpdate()
    {
        $id = 1;
        $data = [
            'name' => 'Test',
            'description' => 'Test description',
        ];

        $expectedData = new مرافق();

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE المرافق SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with($data);

        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedData);

        $response = $this->controller->update($id, $data);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedData), $response->getContent());
    }

    public function testUpdateNotFound()
    {
        $id = 1;
        $data = [
            'name' => 'Test',
            'description' => 'Test description',
        ];

        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->update($id, $data);
    }

    public function testDelete()
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM المرافق WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['id' => $id]);

        $response = $this->controller->delete($id);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound()
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM المرافق WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['id' => $id]);

        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->delete($id);
    }
}
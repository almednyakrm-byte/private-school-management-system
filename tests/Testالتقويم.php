<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Repository\التقويمRepository;
use App\Service\التقويمService;
use PHPUnit\Framework\MockObject\MockObject;

class Testالتقويم extends TestCase
{
    private $router;
    private $tokenStorage;
    private $repository;
    private $service;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->repository = $this->createMock(التقويمRepository::class);
        $this->service = $this->createMock(التقويمService::class);

        $this->tokenStorage->expects($this->any())
            ->method('getToken')
            ->willReturn(null);

        $this->repository->expects($this->any())
            ->method('findAll')
            ->willReturn([]);

        $this->repository->expects($this->any())
            ->method('find')
            ->willReturn(null);

        $this->repository->expects($this->any())
            ->method('save')
            ->willReturn(null);

        $this->repository->expects($this->any())
            ->method('remove')
            ->willReturn(null);
    }

    public function testGetAll(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $response = $this->service->getAll();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $response = $this->service->getOne(1);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testCreate(): void
    {
        $data = ['name' => 'التقويم', 'description' => 'description'];

        $this->repository->expects($this->once())
            ->method('save')
            ->with($data)
            ->willReturn(null);

        $response = $this->service->create($data);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $data = ['name' => 'التقويم', 'description' => 'description'];

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $response = $this->service->update(1, $data);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $response = $this->service->delete(1);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}



// Service
class التقويمService
{
    private $repository;

    public function __construct(التقويمRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): Response
    {
        $data = $this->repository->findAll();

        return new Response(json_encode($data), Response::HTTP_OK);
    }

    public function getOne($id): Response
    {
        $data = $this->repository->find($id);

        if (!$data) {
            throw new NotFoundHttpException('Resource not found');
        }

        return new Response(json_encode($data), Response::HTTP_OK);
    }

    public function create($data): Response
    {
        $this->repository->save($data);

        return new Response('', Response::HTTP_CREATED);
    }

    public function update($id, $data): Response
    {
        $data = $this->repository->find($id);

        if (!$data) {
            throw new NotFoundHttpException('Resource not found');
        }

        $this->repository->save($data);

        return new Response('', Response::HTTP_OK);
    }

    public function delete($id): Response
    {
        $data = $this->repository->find($id);

        if (!$data) {
            throw new NotFoundHttpException('Resource not found');
        }

        $this->repository->remove($data);

        return new Response('', Response::HTTP_OK);
    }
}



// Repository
class التقويمRepository
{
    public function findAll(): array
    {
        // Implement logic to fetch all data from database
    }

    public function find($id): ?object
    {
        // Implement logic to fetch data by id from database
    }

    public function save($data): void
    {
        // Implement logic to save data to database
    }

    public function remove($data): void
    {
        // Implement logic to remove data from database
    }
}
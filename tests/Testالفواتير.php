<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use App\Controller\الفواتيرController;
use App\Repository\الفواتيرRepository;
use App\Entity\الفواتير;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;

class Testالفواتير extends TestCase
{
    private $controller;
    private $router;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->repository = $this->createMock(الفواتيرRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->controller = new الفواتيرController($this->repository, $this->entityManager, $this->router);
    }

    public function testGetAll(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new الفواتير()]);

        $response = $this->controller->getAll();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new الفواتير());

        $response = $this->controller->getOne($id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPost(): void
    {
        $data = ['name' => 'Test Invoice'];
        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($invoice) use ($data) {
                return $invoice->getName() === $data['name'];
            }));

        $response = $this->controller->post($data);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPut(): void
    {
        $id = 1;
        $data = ['name' => 'Updated Test Invoice'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new الفواتير());

        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($invoice) use ($data) {
                return $invoice->getName() === $data['name'];
            }));

        $response = $this->controller->put($id, $data);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new الفواتير());

        $this->repository->expects($this->once())
            ->method('remove')
            ->with($this->callback(function ($invoice) {
                return true;
            }));

        $response = $this->controller->delete($id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'الفواتير' module. It creates a mock object for the RouterInterface, الفواتيرRepository, and EntityManagerInterface to isolate the controller's dependencies. The test methods cover the following scenarios:

*   `testGetAll`: Tests the getAll method, which retrieves all invoices from the repository.
*   `testGetOne`: Tests the getOne method, which retrieves a single invoice by its ID from the repository.
*   `testPost`: Tests the post method, which creates a new invoice and saves it to the repository.
*   `testPut`: Tests the put method, which updates an existing invoice and saves the changes to the repository.
*   `testDelete`: Tests the delete method, which removes an invoice from the repository.

Each test method uses the `expects` method to specify the expected behavior of the mock objects and the `willReturn` method to return a specific value when the method is called. The `callback` method is used to specify a callback function that will be executed when the method is called.
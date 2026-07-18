<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ServicesController;
use App\Repository\ServicesRepository;
use App\Entity\Services;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\RequestStack;
use PHPUnit\Framework\MockObject\MockBuilder;

class Testخدمات extends TestCase
{
    private $servicesController;
    private $servicesRepository;
    private $requestStack;

    protected function setUp(): void
    {
        $this->servicesRepository = $this->createMock(ServicesRepository::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->servicesController = new ServicesController($this->servicesRepository, $this->requestStack);
    }

    public function testGetServices()
    {
        $expectedResponse = ['services' => []];
        $this->servicesRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse);

        $response = $this->servicesController->getServices();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetServiceById()
    {
        $expectedResponse = ['service' => new Services()];
        $id = 1;
        $this->servicesRepository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse['service']);

        $response = $this->servicesController->getService($id);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetServiceByIdNotFound()
    {
        $id = 1;
        $this->servicesRepository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->servicesController->getService($id);
    }

    public function testCreateService()
    {
        $expectedResponse = ['service' => new Services()];
        $data = ['name' => 'Service Name'];
        $this->servicesRepository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($expectedResponse['service']);

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('request')
            ->with('json')
            ->willReturn(json_encode($data));

        $response = $this->servicesController->createService($request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateServiceBadRequest()
    {
        $data = ['name' => ''];
        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('request')
            ->with('json')
            ->willReturn(json_encode($data));

        $this->expectException(BadRequestHttpException::class);
        $this->servicesController->createService($request);
    }

    public function testUpdateService()
    {
        $expectedResponse = ['service' => new Services()];
        $id = 1;
        $data = ['name' => 'Service Name'];
        $this->servicesRepository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn($expectedResponse['service']);

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('request')
            ->with('json')
            ->willReturn(json_encode($data));

        $response = $this->servicesController->updateService($id, $request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateServiceNotFound()
    {
        $id = 1;
        $data = ['name' => 'Service Name'];
        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('request')
            ->with('json')
            ->willReturn(json_encode($data));

        $this->servicesRepository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->servicesController->updateService($id, $request);
    }

    public function testDeleteService()
    {
        $id = 1;
        $this->servicesRepository->expects($this->once())
            ->method('delete')
            ->with($id);

        $response = $this->servicesController->deleteService($id);
        $this->assertEquals(['message' => 'Service deleted successfully'], $response);
    }

    public function testDeleteServiceNotFound()
    {
        $id = 1;
        $this->servicesRepository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->servicesController->deleteService($id);
    }
}
<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ProfesseursController;
use App\Repository\ProfesseursRepository;
use App\Entity\Professeurs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestProfesseurs extends TestCase
{
    private $professeursController;
    private $professeursRepository;
    private $entityManager;
    private $router;

    protected function setUp(): void
    {
        $this->professeursRepository = $this->createMock(ProfesseursRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->professeursController = new ProfesseursController($this->professeursRepository, $this->entityManager, $this->router);
    }

    public function testGetProfesseurs()
    {
        $professeurs = [
            new Professeurs('1', 'John Doe', 'john@example.com'),
            new Professeurs('2', 'Jane Doe', 'jane@example.com'),
        ];

        $this->professeursRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($professeurs);

        $response = $this->professeursController->getProfesseurs();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($professeurs), $response->getContent());
    }

    public function testGetProfesseurById()
    {
        $professeur = new Professeurs('1', 'John Doe', 'john@example.com');

        $this->professeursRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($professeur);

        $response = $this->professeursController->getProfesseurById('1');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($professeur), $response->getContent());
    }

    public function testGetProfesseurByIdNotFound()
    {
        $this->professeursRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $response = $this->professeursController->getProfesseurById('1');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testCreateProfesseur()
    {
        $professeur = new Professeurs('1', 'John Doe', 'john@example.com');

        $this->professeursRepository->expects($this->once())
            ->method('save')
            ->with($professeur);

        $request = new Request([], [], ['json' => ['id' => '1', 'name' => 'John Doe', 'email' => 'john@example.com']]);

        $response = $this->professeursController->createProfesseur($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($professeur), $response->getContent());
    }

    public function testUpdateProfesseur()
    {
        $professeur = new Professeurs('1', 'John Doe', 'john@example.com');

        $this->professeursRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($professeur);

        $this->professeursRepository->expects($this->once())
            ->method('save')
            ->with($professeur);

        $request = new Request([], [], ['json' => ['id' => '1', 'name' => 'John Doe', 'email' => 'john@example.com']]);

        $response = $this->professeursController->updateProfesseur('1', $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($professeur), $response->getContent());
    }

    public function testUpdateProfesseurNotFound()
    {
        $this->professeursRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $request = new Request([], [], ['json' => ['id' => '1', 'name' => 'John Doe', 'email' => 'john@example.com']]);

        $response = $this->professeursController->updateProfesseur('1', $request);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testDeleteProfesseur()
    {
        $professeur = new Professeurs('1', 'John Doe', 'john@example.com');

        $this->professeursRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($professeur);

        $this->professeursRepository->expects($this->once())
            ->method('remove')
            ->with($professeur);

        $response = $this->professeursController->deleteProfesseur('1');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteProfesseurNotFound()
    {
        $this->professeursRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $response = $this->professeursController->deleteProfesseur('1');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
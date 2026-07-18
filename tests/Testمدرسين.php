<?php

namespace App\Tests\Controller;

use App\Controller\ProfesseursController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TestProfesseurs extends TestCase
{
    private $professeursController;
    private $router;
    private $tokenStorage;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->pdoMock = $this->createMock(\PDO::class);

        $this->professeursController = new ProfesseursController(
            $this->router,
            $this->tokenStorage,
            $this->pdoMock
        );
    }

    public function testGetProfesseurs(): void
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM professeurs')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->professeursController->getProfesseurs();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostProfesseur(): void
    {
        $professeur = [
            'nom' => 'John Doe',
            'prenom' => 'Jane Doe',
            'email' => 'johndoe@example.com',
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO professeurs (nom, prenom, email) VALUES (:nom, :prenom, :email)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->professeursController->postProfesseur($professeur);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPutProfesseur(): void
    {
        $professeur = [
            'id' => 1,
            'nom' => 'John Doe',
            'prenom' => 'Jane Doe',
            'email' => 'johndoe@example.com',
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE professeurs SET nom = :nom, prenom = :prenom, email = :email WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->professeursController->putProfesseur($professeur);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteProfesseur(): void
    {
        $id = 1;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM professeurs WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->professeursController->deleteProfesseur($id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\GradesController;
use App\Repository\GradeRepository;
use App\Service\GradeService;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestGrades extends TestCase
{
    private $controller;
    private $gradeRepository;
    private $gradeService;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->gradeRepository = $this->createMock(GradeRepository::class);
        $this->gradeService = $this->createMock(GradeService::class);
        $this->controller = new GradesController($this->gradeRepository, $this->gradeService);
    }

    public function testGetGrades()
    {
        $expectedResponse = ['grades' => []];
        $this->gradeRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse);
        $response = $this->controller->getGrades();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPostGrade()
    {
        $grade = ['name' => 'John Doe', 'score' => 90];
        $expectedResponse = ['message' => 'Grade created successfully'];
        $this->gradeService->expects($this->once())
            ->method('createGrade')
            ->with($grade)
            ->willReturn($expectedResponse);
        $response = $this->controller->postGrade($grade);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPutGrade()
    {
        $grade = ['id' => 1, 'name' => 'John Doe', 'score' => 90];
        $expectedResponse = ['message' => 'Grade updated successfully'];
        $this->gradeService->expects($this->once())
            ->method('updateGrade')
            ->with($grade)
            ->willReturn($expectedResponse);
        $response = $this->controller->putGrade($grade);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteGrade()
    {
        $gradeId = 1;
        $expectedResponse = ['message' => 'Grade deleted successfully'];
        $this->gradeService->expects($this->once())
            ->method('deleteGrade')
            ->with($gradeId)
            ->willReturn($expectedResponse);
        $response = $this->controller->deleteGrade($gradeId);
        $this->assertEquals($expectedResponse, $response);
    }
}



// GradesController.php

namespace App\Controller;

use App\Repository\GradeRepository;
use App\Service\GradeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GradesController
{
    private $gradeRepository;
    private $gradeService;

    public function __construct(GradeRepository $gradeRepository, GradeService $gradeService)
    {
        $this->gradeRepository = $gradeRepository;
        $this->gradeService = $gradeService;
    }

    /**
     * @Route("/grades", methods={"GET"})
     */
    public function getGrades()
    {
        $grades = $this->gradeRepository->findAll();
        return new JsonResponse(['grades' => $grades]);
    }

    /**
     * @Route("/grades", methods={"POST"})
     */
    public function postGrade(Request $request)
    {
        $grade = json_decode($request->getContent(), true);
        $grade = $this->gradeService->createGrade($grade);
        return new JsonResponse(['message' => 'Grade created successfully']);
    }

    /**
     * @Route("/grades/{id}", methods={"PUT"})
     */
    public function putGrade(Request $request, $id)
    {
        $grade = json_decode($request->getContent(), true);
        $grade['id'] = $id;
        $grade = $this->gradeService->updateGrade($grade);
        return new JsonResponse(['message' => 'Grade updated successfully']);
    }

    /**
     * @Route("/grades/{id}", methods={"DELETE"})
     */
    public function deleteGrade($id)
    {
        $this->gradeService->deleteGrade($id);
        return new JsonResponse(['message' => 'Grade deleted successfully']);
    }
}



// GradeService.php

namespace App\Service;

use App\Entity\Grade;

class GradeService
{
    public function createGrade(array $grade)
    {
        // Create a new grade entity
        $newGrade = new Grade();
        $newGrade->setName($grade['name']);
        $newGrade->setScore($grade['score']);
        // Save the new grade to the database
        // ...
        return ['message' => 'Grade created successfully'];
    }

    public function updateGrade(array $grade)
    {
        // Update the grade entity
        // ...
        return ['message' => 'Grade updated successfully'];
    }

    public function deleteGrade($id)
    {
        // Delete the grade entity
        // ...
        return ['message' => 'Grade deleted successfully'];
    }
}



// GradeRepository.php

namespace App\Repository;

use App\Entity\Grade;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GradeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grade::class);
    }

    public function findAll()
    {
        // Return all grades from the database
        // ...
        return [];
    }
}
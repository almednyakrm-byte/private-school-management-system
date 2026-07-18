<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Repository\Mo3ameenRepository;
use App\Service\Mo3ameenService;
use PHPUnit\Framework\MockObject\MockObject;

class TestMo3ameen extends WebTestCase
{
    private $client;
    private $router;
    private $tokenStorage;
    private $mo3ameenRepository;
    private $mo3ameenService;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->router = static::$container->get(RouterInterface::class);
        $this->tokenStorage = static::$container->get(TokenStorageInterface::class);
        $this->mo3ameenRepository = static::$container->get(Mo3ameenRepository::class);
        $this->mo3ameenService = static::$container->get(Mo3ameenService::class);
    }

    public function testGetMo3ameen(): void
    {
        $request = Request::create('/mo3ameen', 'GET');
        $this->client->request($request->getMethod(), $request->getPathInfo(), $request->query->all(), $request->request->all(), $request->cookies->all(), $request->headers->all());

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testPostMo3ameen(): void
    {
        $mo3ameen = ['name' => 'Test Mo3ameen', 'email' => 'test@example.com'];
        $request = Request::create('/mo3ameen', 'POST', [], json_encode($mo3ameen), [], ['CONTENT_TYPE' => 'application/json']);
        $this->client->request($request->getMethod(), $request->getPathInfo(), $request->query->all(), $request->request->all(), $request->cookies->all(), $request->headers->all());

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    public function testPutMo3ameen(): void
    {
        $mo3ameen = $this->mo3ameenRepository->findOneBy(['name' => 'Test Mo3ameen']);
        $request = Request::create('/mo3ameen/' . $mo3ameen->getId(), 'PUT', [], json_encode(['name' => 'Updated Mo3ameen', 'email' => 'updated@example.com']), [], ['CONTENT_TYPE' => 'application/json']);
        $this->client->request($request->getMethod(), $request->getPathInfo(), $request->query->all(), $request->request->all(), $request->cookies->all(), $request->headers->all());

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteMo3ameen(): void
    {
        $mo3ameen = $this->mo3ameenRepository->findOneBy(['name' => 'Test Mo3ameen']);
        $request = Request::create('/mo3ameen/' . $mo3ameen->getId(), 'DELETE');
        $this->client->request($request->getMethod(), $request->getPathInfo(), $request->query->all(), $request->request->all(), $request->cookies->all(), $request->headers->all());

        $this->assertEquals(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());
    }
}



// Mo3ameenRepository.php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class Mo3ameenRepository extends EntityRepository
{
    public function findOneBy(array $criteria): ?object
    {
        return $this->findOneBy($criteria);
    }
}



// Mo3ameenService.php

namespace App\Service;

use App\Repository\Mo3ameenRepository;

class Mo3ameenService
{
    private $mo3ameenRepository;

    public function __construct(Mo3ameenRepository $mo3ameenRepository)
    {
        $this->mo3ameenRepository = $mo3ameenRepository;
    }

    public function createMo3ameen(array $mo3ameen): object
    {
        // Create a new Mo3ameen entity
        $mo3ameenEntity = new Mo3ameen();
        $mo3ameenEntity->setName($mo3ameen['name']);
        $mo3ameenEntity->setEmail($mo3ameen['email']);

        // Save the Mo3ameen entity
        $this->mo3ameenRepository->save($mo3ameenEntity);

        return $mo3ameenEntity;
    }

    public function updateMo3ameen(object $mo3ameen, array $data): void
    {
        // Update the Mo3ameen entity
        $mo3ameen->setName($data['name']);
        $mo3ameen->setEmail($data['email']);

        // Save the Mo3ameen entity
        $this->mo3ameenRepository->save($mo3ameen);
    }

    public function deleteMo3ameen(object $mo3ameen): void
    {
        // Remove the Mo3ameen entity
        $this->mo3ameenRepository->remove($mo3ameen);
    }
}



// Mo3ameenController.php

namespace App\Controller;

use App\Service\Mo3ameenService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Mo3ameenController
{
    private $mo3ameenService;

    public function __construct(Mo3ameenService $mo3ameenService)
    {
        $this->mo3ameenService = $mo3ameenService;
    }

    public function getMo3ameen(Request $request): Response
    {
        // Get all Mo3ameen entities
        $mo3ameen = $this->mo3ameenService->getAllMo3ameen();

        return new Response(json_encode($mo3ameen));
    }

    public function postMo3ameen(Request $request): Response
    {
        // Create a new Mo3ameen entity
        $mo3ameen = $this->mo3ameenService->createMo3ameen(json_decode($request->getContent(), true));

        return new Response(json_encode($mo3ameen), Response::HTTP_CREATED);
    }

    public function putMo3ameen(Request $request): Response
    {
        // Get the Mo3ameen entity to update
        $mo3ameen = $this->mo3ameenService->getMo3ameen($request->get('id'));

        // Update the Mo3ameen entity
        $this->mo3ameenService->updateMo3ameen($mo3ameen, json_decode($request->getContent(), true));

        return new Response('', Response::HTTP_OK);
    }

    public function deleteMo3ameen(Request $request): Response
    {
        // Get the Mo3ameen entity to delete
        $mo3ameen = $this->mo3ameenService->getMo3ameen($request->get('id'));

        // Delete the Mo3ameen entity
        $this->mo3ameenService->deleteMo3ameen($mo3ameen);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
<?php


namespace App\Controller;


use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/clients") */
class ClientController extends AbstractController
{
    private $em;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->em = $entityManager;
        $this->logger = $logger;
    }


    /** @Route("/", name="clients_view_page") */
    public function index(ClientRepository $repository)
    {
        //$repository = $this->em->getRepository(Client::class);
        //$clients = $repository->findAll();

        $clients = $repository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('main/clients.html.twig', ["clients" => $clients]);
    }


    /** @Route("/{clientId<\d+>}", name="client_overview_page", methods={"GET"}) */
    public function client($clientId)
    {
        $repository = $this->em->getRepository(Client::class);
        $client = $repository->find($clientId);
        if (!$client) {
            $this->logger->info("Client not found", [$clientId]);
            throw $this->createNotFoundException(sprintf('No client with id "%d"', $clientId));
        }

        return $this->render('main/client_overview.html.twig', ["client" => $client]);
    }

    /** @Route("/", methods={"POST"}) */
    public function new()
    {
        die("todo");
        //$email = "test@test.com"; // from form
/*        $repository = $this->em->getRepository(Client::class);
        $repository->findOneBy(['email' => $email]);*/

        $client = new Client();
        $client->setName("Jane")
            ->setSurname("Doe")
            ->setEmail("test2@mail.com")
            ->setPhone("55555513213")
            ->setEducationId(2)
            ->setProcessData(true);

        $this->em->persist($client);
        $this->em->flush();

        return $this->json(["data" => $client]);
        //return new JsonResponse(['hearts' => rand(5, 100)]);

    }

    public function update(Client $client)
    {
        $client->setEmail("update@mail.com");
        $this->em->flush();
        return new JsonResponse(['client' => $client]);
    }
}
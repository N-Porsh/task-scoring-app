<?php


namespace App\Controller;


use App\Entity\Client;
use App\Entity\Score;
use App\Form\ClientFormType;
use App\Repository\ClientRepository;
use App\Repository\EducationRepository;
use App\Service\ScoringEngine;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/clients") */
class ClientController extends AbstractController
{
    private $em;
    private $logger;
    private $scoring;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, ScoringEngine $scoringEngine)
    {
        $this->em = $entityManager;
        $this->logger = $logger;
        $this->scoring = $scoringEngine;
    }


    /** @Route("/", name="clients_view_page") */
    public function index(ClientRepository $repository)
    {
        //$repository = $this->em->getRepository(Client::class);
        //$clients = $repository->findAll();
        $clients = $repository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('main/clients.html.twig', ["clients" => $clients]);
    }

    /** @Route("/{id<\d+>}", name="client_overview_page") */
    public function edit(Client $client, Request $request, EducationRepository $educationRepository , ScoringEngine $scoringEngine)
    {
        $form = $this->createForm(ClientFormType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Client $client */
            $client = $form->getData();
            $this->em->persist($client);
            $this->em->flush();

            $scoringEngine->calculate($client);

            $this->addFlash('success', 'Client updated!');
            $this->redirectToRoute('client_overview_page',  [
                'id' => $client->getId()
            ]);
        }

        $educationOptions = $educationRepository->findAll();
        $scoreRepository = $this->em->getRepository(Score::class);
        $score = $scoreRepository->findOneBy(['clientId' => $client->getId()]);

        return $this->render('main/client_overview.html.twig', [
            'clientForm' => $form->createView(),
            "client" => $client,
            'score' => $score,
            "educationOptions" => $educationOptions
        ]);
    }
}
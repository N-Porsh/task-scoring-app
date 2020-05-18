<?php


namespace App\Controller;


use App\Entity\Client;
use App\Form\ClientFormType;
use App\Service\ScoringEngine;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{

    private $logger;
    private $scoring;

    public function __construct(LoggerInterface $logger, ScoringEngine $scoringEngine)
    {
        $this->logger = $logger;
        $this->scoring = $scoringEngine;
    }
    /** @Route("/", name="registration_view_page") */
    public function index(EntityManagerInterface $em, Request $request, ScoringEngine $scoringEngine)
    {
        $form = $this->createForm(ClientFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Client $client */
            $client = $form->getData();
            $em->persist($client);
            $em->flush();

            $scoringEngine->calculate($client);

            $this->addFlash('success', 'Client registered!');
            unset($request);
            $this->redirectToRoute('registration_view_page');
        }

        return $this->render('main/register.html.twig', [
            'clientForm' => $form->createView()
        ]);
    }

}
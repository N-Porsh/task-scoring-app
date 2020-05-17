<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/clients") */
class ClientController extends AbstractController
{
    /** @Route("/", name="clients_view_page") */
    public function index()
    {
        return $this->render('main/clients.html.twig');
    }


    /** @Route("/{clientId}", name="client_overview_page") */
    public function client($clientId)
    {
        return $this->render('main/client_overview.html.twig', ["clientId"=> $clientId]);
    }
}
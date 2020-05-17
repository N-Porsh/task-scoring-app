<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /** @Route("/", name="registration_view_page") */
    public function index()
    {
        return $this->render('main/register.html.twig');
    }

}
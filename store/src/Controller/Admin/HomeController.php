<?php

declare(strict_types=1);


namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin.")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("", name="home")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('app/admin/home.html.twig');
    }
}
<?php

declare(strict_types=1);


namespace App\Controller\Auth;


use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/reset/{token}", name="auth.reset.reset")
     * @param string $token
     * @param Request $request
     * @return Response
     */
    public function reset(string $token, Request $request): Response
    {
        return new Response("");
    }

    /**
     * @Route("/reset", name="auth.reset")
     * @param Request $request
     * @return Response
     */
    public function request(Request $request): Response
    {
        return new Response("");
    }
}
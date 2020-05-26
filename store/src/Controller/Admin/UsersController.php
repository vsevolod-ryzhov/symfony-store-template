<?php

declare(strict_types=1);


namespace App\Controller\Admin;


use App\Domain\User\Entity\User;
use App\Domain\User\UseCase\Create\Command;
use App\Domain\User\UseCase\Create\Form;
use App\Domain\User\UseCase\Create\Handler;
use App\Domain\User\UserQuery;
use DomainException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/users", name="admin.users")
 */
class UsersController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("", name="")
     * @param Request $request
     * @param UserQuery $users
     * @return Response
     */
    public function index(Request $request, UserQuery $users): Response
    {
        $users = $users->all();

        return $this->render('app/admin/users/index.html.twig', compact('users'));
    }

    /**
     * @Route("/create", name=".create")
     * @param Request $request
     * @param Handler $handler
     * @return Response
     */
    public function create(Request $request, Handler $handler): Response
    {
        $command = new Command();

        $form = $this->createForm(Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('admin.users');
            } catch (DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/admin/users/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name=".show")
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response
    {
        return $this->render('app/admin/users/show.html.twig', compact('user'));
    }
}
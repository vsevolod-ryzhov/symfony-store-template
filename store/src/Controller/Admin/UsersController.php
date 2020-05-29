<?php

declare(strict_types=1);


namespace App\Controller\Admin;


use App\Domain\User\Entity\User;
use App\Domain\User\Filter\UserIndex;
use App\Domain\User\UseCase\Edit;
use App\Domain\User\UseCase\Create;
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
    private const INDEX_ITEMS_COUNT = 15;

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
        $filter = new UserIndex\Filter();

        $form = $this->createForm(UserIndex\Form::class, $filter);
        $form->handleRequest($request);
        $users = $users->all($filter);

        return $this->render('app/admin/users/index.html.twig', [
            'users' => $users,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/create", name=".create")
     * @param Request $request
     * @param Create\Handler $handler
     * @return Response
     */
    public function create(Request $request, Create\Handler $handler): Response
    {
        $command = new Create\Command();

        $form = $this->createForm(Create\Form::class, $command);
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
     * @Route("/edit/{id}", name=".edit")
     * @param Request $request
     * @param User $user
     * @param Edit\Handler $handler
     * @return Response
     */
    public function edit(Request $request, User $user, Edit\Handler $handler): Response
    {
        $command = Edit\Command::createFromUser($user);

        $form = $this->createForm(Edit\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('admin.users.show', ['id' => $user->getId()]);
            } catch (DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/admin/users/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
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
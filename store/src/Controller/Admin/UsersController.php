<?php

declare(strict_types=1);


namespace App\Controller\Admin;


use App\Domain\User\Entity\Status;
use App\Domain\User\Entity\User;
use App\Domain\User\Filter\UserIndex;
use App\Domain\User\UseCase\Edit;
use App\Domain\User\UseCase\Create;
use App\Domain\User\UseCase\StatusChange;
use App\Domain\User\UseCase\RoleChange;
use App\Domain\User\UserQuery;
use DomainException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/users", name="admin.users")
 */
class UsersController extends AbstractController
{
    private const ADMIN_USERS_SHOW = 'admin.users.show';
    private const ERROR_FLASH_KEY = 'error';

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
        $user_list = $users->all(
            $filter,
            $request->query->getInt('page', 1),
            self::INDEX_ITEMS_COUNT,
            $request->query->get('sort', 'id'),
            $request->query->get('direction', 'desc')
        );

        return $this->render('app/admin/users/index.html.twig', [
            'users' => $user_list,
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
                $this->addFlash(self::ERROR_FLASH_KEY, $e->getMessage());
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
                return $this->redirectToRoute(self::ADMIN_USERS_SHOW, ['id' => $user->getId()]);
            } catch (DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash(self::ERROR_FLASH_KEY, $e->getMessage());
            }
        }

        return $this->render('app/admin/users/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/status/{id}/{status}", name=".status", methods={"POST"})
     * @param User $user
     * @param string $status
     * @param Request $request
     * @param StatusChange\Handler $handler
     * @return Response
     */
    public function status(User $user, string $status, Request $request, StatusChange\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('status', $request->request->get('token'))) {
            return $this->redirectToRoute(self::ADMIN_USERS_SHOW, ['id' => $user->getId()]);
        }

        $command = new StatusChange\Command($user->getId(), $status);

        try {
            $handler->handle($command);
        } catch (DomainException $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            $this->addFlash(self::ERROR_FLASH_KEY, $e->getMessage());
        }

        return $this->redirectToRoute(self::ADMIN_USERS_SHOW, ['id' => $user->getId()]);
    }

    /**
     * @Route("/role/{id}", name=".role")
     * @param User $user
     * @param Request $request
     * @param RoleChange\Handler $handler
     * @return Response
     */
    public function role(User $user, Request $request, RoleChange\Handler $handler): Response
    {
        $this->checkRoleChangeAvailability($user);

        $command = RoleChange\Command::fromUser($user);

        $form = $this->createForm(RoleChange\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute(self::ADMIN_USERS_SHOW, ['id' => $user->getId()]);
            } catch (DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash(self::ERROR_FLASH_KEY, $e->getMessage());
            }
        }

        return $this->render('app/admin/users/role.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @see role
     * @param User $user
     * @return RedirectResponse|null
     */
    private function checkRoleChangeAvailability(User $user): ?RedirectResponse
    {
        if ($this->getUser() && $user->getId() === $this->getUser()->getId()) {
            $this->addFlash(self::ERROR_FLASH_KEY, 'Невозможно изменить роль для своего аккаунта.');
            return $this->redirectToRoute(self::ADMIN_USERS_SHOW, ['id' => $user->getId()]);
        }
        return null;
    }

    /**
     * @Route("/{id}", name=".show")
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response
    {
        return $this->render('app/admin/users/show.html.twig', [
            'user' => $user,
            'status_active' => Status::STATUS_ACTIVE,
            'status_blocked' => Status::STATUS_BLOCKED
        ]);
    }
}
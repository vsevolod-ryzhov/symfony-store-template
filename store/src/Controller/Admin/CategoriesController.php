<?php

declare(strict_types=1);


namespace App\Controller\Admin;


use App\Domain\Category\CategoryQuery;
use App\Domain\Category\CategoryRepository;
use App\Domain\Category\UseCase\Create;
use App\Domain\Category\UseCase\Edit;
use App\Domain\Category\UseCase\Sort;
use App\Domain\Category\Entity\Category;
use App\Domain\Category\Filter\CategoryIndex;
use App\Domain\Category\Service\CategoryDecorator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use DomainException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/categories", name="admin.categories")
 */
class CategoriesController extends AbstractController
{
    private const ERROR_FLASH_KEY = 'error';

    private const INDEX_ITEMS_COUNT = 15;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("", name="")
     * @param Request $request
     * @param CategoryQuery $categories
     * @return Response
     */
    public function index(Request $request, CategoryQuery $categories): Response
    {
        $filter = new CategoryIndex\Filter();

        $form = $this->createForm(CategoryIndex\Form::class, $filter);
        $form->handleRequest($request);
        $category_list = $categories->all(
            $filter,
            $request->query->getInt('page', 1),
            self::INDEX_ITEMS_COUNT,
            $request->query->get('sort', 'id'),
            $request->query->get('direction', 'desc')
        );

        return $this->render('app/admin/categories/index.html.twig', [
            'categories' => $category_list,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sortable", name=".sortable")
     * @param EntityManagerInterface $em
     * @param CategoryRepository $categoryRepository
     * @return Response
     * @throws EntityNotFoundException
     */
    public function sortable(EntityManagerInterface $em, CategoryRepository $categoryRepository): Response
    {
        $repository = $em->getRepository(Category::class);
        // TODO: hide this call
        $category_list = $repository->childrenHierarchy(
            $categoryRepository->getRoot(),
            false,
            [
                'decorate' => true,
                'rootOpen' => '<ul class="nested-sortable nested-list-group">',
                'nodeDecorator' => static function ($node)
                {
                    return "<span><i class=\"fa fa-arrows\"></i> $node[name]</span>";
                },
                'childOpen' => static function ($node)
                {
                    return "<li data-id=\"$node[id]\">";
                }
            ],
            false
        );

        return $this->render('app/admin/categories/sortable.html.twig', [
            'categories' => $category_list
        ]);
    }

    /**
     * @Route("/sortable/sort", name=".sortable.sort", methods={"POST"})
     * @param Request $request
     * @param Sort\Handler $handler
     * @return Response
     */
    public function sort(Request $request, Sort\Handler $handler): Response
    {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        // TODO: parse full tree in handler
//        $handler->handle($request->getContent());
        $response->setContent($request->getContent());
        return $response;
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
                return $this->redirectToRoute('admin.categories');
            } catch (DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash(self::ERROR_FLASH_KEY, $e->getMessage());
            }
        }

        return $this->render('app/admin/categories/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name=".edit")
     * @param Request $request
     * @param Category $category
     * @param Edit\Handler $handler
     * @return Response
     */
    public function edit(Request $request, Category $category, Edit\Handler $handler): Response
    {
        $command = new Edit\Command($category);

        $form = $this->createForm(Edit\Form::class, $command, ['current_category_id' => $category->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('admin.categories.show', ['id' => $category->getId()]);
            } catch (DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash(self::ERROR_FLASH_KEY, $e->getMessage());
            }
        }

        return $this->render('app/admin/categories/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/{id}", name=".show")
     * @param Category $category
     * @return Response
     */
    public function show(Category $category): Response
    {
        return $this->render('app/admin/categories/show.html.twig', [
            'category' => $category,
            'category_full_name' => CategoryDecorator::getFullName($category, ' \ ', true)
        ]);
    }
}
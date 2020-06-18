<?php

declare(strict_types=1);


namespace App\Controller\Admin;


use App\Domain\Product\Entity\Product;
use App\Domain\Product\Filter\ProductIndex;
use App\Domain\Product\ProductQuery;
use App\Domain\Product\UseCase\Create;
use App\Domain\Product\UseCase\Edit;
use DomainException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/products", name="admin.products")
 */
class ProductsController extends AbstractController
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
     * @param ProductQuery $products
     * @return Response
     */
    public function index(Request $request, ProductQuery $products): Response
    {
        $filter = new ProductIndex\Filter();

        $form = $this->createForm(ProductIndex\Form::class, $filter);
        $form->handleRequest($request);
        $product_list = $products->all(
            $filter,
            $request->query->getInt('page', 1),
            self::INDEX_ITEMS_COUNT,
            $request->query->get('sort', 'id'),
            $request->query->get('direction', 'desc')
        );

        return $this->render('app/admin/products/index.html.twig', [
            'products' => $product_list,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/create", name=".create")
     * @param Request $request
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
                return $this->redirectToRoute('admin.products');
            } catch (DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash(self::ERROR_FLASH_KEY, $e->getMessage());
            }
        }

        return $this->render('app/admin/products/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name=".edit")
     * @param Request $request
     * @param Product $product
     * @param Edit\Handler $handler
     * @return Response
     */
    public function edit(Request $request, Product $product, Edit\Handler $handler): Response
    {
        $command = new Edit\Command($product);

        $form = $this->createForm(Edit\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('admin.products.show', ['id' => $product->getId()]);
            } catch (DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash(self::ERROR_FLASH_KEY, $e->getMessage());
            }
        }

        return $this->render('app/admin/products/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product
        ]);
    }

    /**
     * @Route("/{id}", name=".show")
     * @param Product $product
     * @return Response
     */
    public function show(Product $product): Response
    {
        return $this->render('app/admin/products/show.html.twig', [
            'product' => $product
        ]);
    }
}
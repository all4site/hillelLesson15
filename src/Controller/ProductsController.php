<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Product;
use App\Event\ProductInsertEvent;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{

	/**
	 * @Route("/", name="home")
	 */
	public function home()
	{
		return $this->redirectToRoute('products');
	}

	/**
	 * @Route("/products", name="products")
	 * @param PaginatorInterface $paginator
	 * @param Request $request
	 * @return Response
	 */
	public function index(PaginatorInterface $paginator, Request $request)
	{

		$products = $this->getDoctrine()->getRepository(Product::class)->findBy([], ['id' => 'DESC']);
		$brand = $this->getDoctrine()->getRepository(Brand::class)->findAll();
		$pagination = $paginator->paginate(
				$products,
				$request->query->getInt('page', 1),
				5
		);
		$pagination->setCustomParameters([
				'align' => 'center',
		]);

		return $this->render('products/index.html.twig', [
				'controller_name' => 'ProductsController',
				'products' => $pagination,
				'brand' => $brand
		]);
	}

	/**
	 * @Route("/create", name="create")
	 */
	public function create()
	{
		$brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();

		return $this->render('products/create.html.twig', [
				'controller_name' => 'ProductsController',
			'brands' => $brands
		]);
	}

	/**
	 * @Route("/store", name="store")
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{

		$brand = $this->getDoctrine()->getRepository(Brand::class)->findOneBy([
				'name' => $request->get('brand')
		]);
		$brand->getName();

		$entityManager = $this->getDoctrine()->getManager();
		$product = new Product();

		$product->setName($request->get('name'))
				->setDescription($request->get('description'))
				->setPrice($request->get('price'))
			->setBrand($brand);

		$entityManager->persist($product);
		$entityManager->flush();




		return $this->redirectToRoute('products');
	}

	/**
	 * @Route("/edit/{id}", name="edit")
	 * @param int $id
	 * @return Response
	 */
	public function edit(int $id)
	{

		$product = $this->getDoctrine()->getRepository(Product::class)->find($id);


		return $this->render('products/edit.html.twig', [
				'controller_name' => 'ProductsController',
				'product' => $product
		]);
	}

	/**
	 * @Route("/update/{id}", name="update")
	 * @param Request $request
	 * @param int $id
	 * @return RedirectResponse
	 */
	public function update(Request $request, int $id)
	{
		$entityManager = $this->getDoctrine()->getManager();

		$product = $entityManager->getRepository(Product::class)->find($id);


		$product->setName($request->get('name'))
				->setDescription($request->get('description'))
				->setPrice($request->get('price'));

		$entityManager->flush();

		return $this->redirectToRoute('products');
	}

	/**
	 * @Route("/show/{id}", name="show")
	 * @param int $id
	 * @param EventDispatcherInterface $dispatcher
	 * @return Response
	 */
	public function show(int $id, EventDispatcherInterface $dispatcher)
	{

		$product = $this->getDoctrine()->getRepository(Product::class)->find($id);

		$event = new ProductInsertEvent($product);
		$dispatcher->dispatch($event);

		return $this->render('products/show.html.twig', [
				'controller_name' => 'ProductsController',
				'product' => $product
		]);
	}

	/**
	 * @Route("/delete/{id}", name="delete")
	 * @param int $id
	 * @return Response
	 */
	public function delete(int $id)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$product = $this->getDoctrine()->getRepository(Product::class)->find($id);
		$entityManager->remove($product);
		$entityManager->flush();
		return $this->redirectToRoute('products');
	}

}
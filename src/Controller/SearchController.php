<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
	/**
	 * @Route("/search", name="search")
	 * @param PaginatorInterface $paginator
	 * @param Request $request
	 * @param ProductRepository $productRepository
	 * @return Response
	 */
    public function index(PaginatorInterface $paginator, Request $request, ProductRepository $productRepository)
    {
	    $products = $productRepository->querySearchForParameter($request->get('s'));
	    $brand = $this->getDoctrine()->getRepository(Brand::class)->findAll();

	    $pagination = $paginator->paginate(
			    $products,
			    $request->query->getInt('page', 1),
			    5
	    );

	    $pagination->setCustomParameters([
			    'align' => 'center',
	    ]);


        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
		        'products' => $pagination,
		        'brand' => $brand
        ]);
    }
}

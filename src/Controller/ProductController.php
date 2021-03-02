<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    private $entityManager ;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/nos-produits', name: 'products')]
    public function index(): Response
    {

        //Recupérer le repos et aprés j'utilise find all
        $products = $this->entityManager->getRepository(Product::class)->findAll();
      
        return $this->render('product/index.html.twig',[
            'products' => $products
        ]);
    }

    
    #[Route('/produit/{slug}', name: 'product')]
    public function show($slug)
    {
        //Recupérer le repos et aprés j'utilise findOneBy et  j'injecte le slug
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);

        if (!$product){
            return $this->redirectToRoute('products');
        }

        return $this->render('product/show.html.twig',[
            'product' => $product
        ]);
    }
}

<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request): Response
    {

        //Instancier notre class Search
        $search = new Search();

        //Creation du formulaire
        $form = $this->createForm(SearchType::class, $search);

        //Ecouter notre formulaire
        $form->handleRequest($request);

        //Si le form soumis et valid
        if($form->isSubmitted()&& $form->isValid()){
            //On appel notre repo productRepo et on appel notre methode findWithSearch
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
        }
        else{
        //Recupérer le repos et aprés j'utilise find all
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        }
        
        return $this->render('product/index.html.twig',[
            'products' => $products,
            'form' => $form->createView()
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

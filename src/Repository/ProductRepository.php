<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Requete qui me permet de récupérer les produits en fonction de la recherche de l'utilisateur
     * @return Product[]
     */
    public function findWithSearch(Search $search)
    {
        $query = $this
            //Premiére methode pour creér une query 
            ->createQueryBuilder('p')
            // Selectionner C Category et P Product en utilisant les alias offert par doctrine
            ->select('c', 'p')
            //Jointure entre le produit et le category
            ->join('p.category', 'c');


        //Si l'utlisateur renseigne les catégories a rechercher a ce moment tu execute ça
        if (!empty($search->categories)) {
            $query = $query
                //Condition where categories cad a quoi correspend categories
                ->andWhere('c.id IN (:categories)')
                /*   Pour ce faire en utlisant la nom de variable que nous avons injecter :categories
                 En 2 eme paramétre en lui donne la valeur de cette clé */
                ->setParameter('categories', $search->categories);
        }

        //Si l'utlisateur renseigne un texte a recherche tu execute ça
        if (!empty($search->string)) {
            $query = $query
                ->andWhere('p.name LIKE :string')
                ->setParameter('string', "%{$search->string}%");
        }

        //Enfin on dit a notre fonction retourner la resultat
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

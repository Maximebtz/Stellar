<?php

namespace App\Repository;

use App\Entity\Advert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Advert>
 *
 * @method Advert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advert[]    findAll()
 * @method Advert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advert::class);
    }

    public function findPaginatedAdverts($page, $perPage)
    {
        $offset = ($page - 1) * $perPage;

        return $this->createQueryBuilder('a')
            ->setFirstResult($offset)
            ->setMaxResults($perPage)
            ->getQuery()
            ->getResult();
    }

    // Compter le nombre d'Annonces
    public function getTotalAdvertsCount()
    {
        // Créer l'objet QueryBuilder
        return $this->createQueryBuilder('a')
            // Compter le nombre d'entité Advert
            ->select('COUNT(a.id)')
            
            ->getQuery()
            
            ->getSingleScalarResult();
    }

    public function filterAdverts(array $filters): array
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->leftJoin('a.reservations', 'r')
            ->where('1=1');

        if (!empty($filters['cities'])) {
            $queryBuilder->andWhere('a.city IN (:cities)')
                ->setParameter('cities', $filters['cities']);
        }

        if (!empty($filters['countries'])) {
            $queryBuilder->andWhere('a.country IN (:countries)')
                ->setParameter('countries', $filters['countries']);
        }

        if (!empty($filters['minPrice'])) {
            $queryBuilder->andWhere('a.price >= :minPrice')
                ->setParameter('minPrice', $filters['minPrice']);
        }

        if (!empty($filters['maxPrice'])) {
            $queryBuilder->andWhere('a.price <= :maxPrice')
                ->setParameter('maxPrice', $filters['maxPrice']);
        }

        if (!empty($filters['startDate']) && !empty($filters['endDate'])) {
            $queryBuilder->andWhere('NOT EXISTS (
                SELECT 1 FROM App\Entity\Reservation r
                WHERE r.advert = a
                AND r.startDate <= :endDate
                AND r.endDate >= :startDate
            )')
            ->setParameter('startDate', new \DateTime($filters['startDate']))
            ->setParameter('endDate', new \DateTime($filters['endDate']));
        }

        $queryBuilder->orderBy('a.price', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }
}

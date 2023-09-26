<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /** 
     * Récupère les dates réservées pour une annonce spécifique
     *
     * @param int $advertId L'ID de l'annonce
     * @return array Les dates réservées sous forme de tableau
     */
    public function findReservedDatesForAdvert(int $advertId): array
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r.arrivalDate', 'r.departureDate')
            ->where('r.advert = :advertId')
            ->setParameter('advertId', $advertId)
            ->getQuery();

        $result = $qb->getResult();

        $reservedDates = [];

        foreach ($result as $reservation) {
            $arrivalDate = $reservation['arrivalDate']->format('Y-m-d');
            $departureDate = $reservation['departureDate']->format('Y-m-d');

            // Ajoutez ces dates dans le tableau des dates réservées
            $reservedDates[] = [$arrivalDate, $departureDate];
        }

        return $reservedDates;
    }
}

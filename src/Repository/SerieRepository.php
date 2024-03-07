<?php

namespace App\Repository;

use App\Entity\Serie;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Uid\UuidV7;

/**
 * @extends ServiceEntityRepository<Serie>
 *
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    /**
     * @param UuidV7 $idGame
     * @return Serie[]
     * @throws Exception
     */
    public function findActiveByGameId(UuidV7 $idGame): array
    {
        return $this->createQueryBuilder('s')
            ->addSelect('s.name AS serie_name')
            ->addSelect('l.name AS league_name')
            ->join('s.league', 'l')
            ->join('l.game', 'g')
            ->andWhere('g.id = :idGame')
            ->andWhere('s.active = true')
            ->andWhere('s.endAt >= :currentDate')
            ->setParameter('idGame', $idGame)
            ->setParameter('currentDate', new DateTimeImmutable('now', new DateTimeZone('UTC')))
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return array|null
     * @throws Exception
     */
    public function findActiveByGame(): ?array
    {
        $entityManager = $this->getEntityManager();

        $dql = <<<SQL
            SELECT COUNT(s) AS ongoing_series, g.id AS game_id
            FROM App\Entity\Serie s
            JOIN s.league l
            JOIN l.game g
            WHERE s.active = TRUE
                AND l.active = TRUE
                AND g.active = TRUE
                AND s.beginAt <= :currentDate
                AND s.endAt >= :currentDate
            GROUP BY g.id
        SQL;


        $query = $entityManager
            ->createQuery($dql)
            ->setParameter('currentDate', new DateTimeImmutable('now', new DateTimeZone('UTC')))
        ;

        // returns an array of Product objects
        return $query->getResult();
    }
}

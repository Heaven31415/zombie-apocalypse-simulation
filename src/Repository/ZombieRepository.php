<?php

namespace App\Repository;

use App\Entity\Zombie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Zombie>
 *
 * @method Zombie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Zombie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Zombie[]    findAll()
 * @method Zombie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZombieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Zombie::class);
    }

    public function save(Zombie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Zombie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

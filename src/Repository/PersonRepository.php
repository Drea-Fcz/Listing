<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Person>
 *
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function add(Person $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Person $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @return Person[] Returns an array of Person objects
     */
    public function findPersonByIntervalAge($min, $max): array
    {
        $_qb = $this->createQueryBuilder('p');
        $this->addIntervalAge($_qb, $min, $max);
        return $_qb->getQuery()->getResult();
    }

    private function addIntervalAge(QueryBuilder $_qb, $min, $max)
    {
        $_qb->andWhere('p.age > :min and p.age < :max')
            ->setParameters(['min' => $min, 'max' => $max]);
    }


    /**
     * @return Person[] Returns an array of Person objects
     */
    public function statPersonByIntervalAgeAndMoyen($min, $max): array
    {
        $_qb = $this->createQueryBuilder('p')
            ->select('avg(p.age) as ageMoyen, count(p.id) as nbrPerson');
        $this->addIntervalAge($_qb, $min, $max);
        return $_qb->getQuery()
            ->getScalarResult();
    }
}

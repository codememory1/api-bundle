<?php

namespace Codememory\ApiBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Override;

/**
 * @template T as object
 */
abstract class AbstractRepository extends ServiceEntityRepository
{
    protected ?string $entity = null;
    protected ?string $alias = null;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->entity);
    }

    protected function createQueryWithDistribution(int $current, int $total): QueryBuilder
    {
        $qb = $this->createQB();
        $count = $this->count([]);

        $qb
            ->setFirstResult(($current - 1) * ceil($count / $total))
            ->setMaxResults(ceil($count / $total));

        return $qb;
    }

    public function createQB(?string $indexBy = null): QueryBuilder
    {
        return $this->createQueryBuilder($this->alias, $indexBy);
    }

    /**
     * @param      $id
     * @param null $lockMode
     * @param null $lockVersion
     *
     * @return null|T
     */
    #[Override]
    public function find($id, $lockMode = null, $lockVersion = null): ?object
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    /**
     * @return array<int, T>
     */
    #[Override]
    public function findAll(): array
    {
        return parent::findAll();
    }

    /**
     * @return array<int, T>
     */
    #[Override]
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @return null|T
     */
    #[Override]
    public function findOneBy(array $criteria, ?array $orderBy = null): ?object
    {
        return parent::findOneBy($criteria, $orderBy);
    }
}
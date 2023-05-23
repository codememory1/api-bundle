<?php

namespace Codememory\ApiBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractRepository extends ServiceEntityRepository
{
    protected ?string $entity = null;
    protected ?string $alias = null;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->entity);
    }

    public function createQB(?string $indexBy = null): QueryBuilder
    {
        return $this->createQueryBuilder($this->alias, $indexBy);
    }

    protected function generateQueryByProcess(int $numberProcess, int $numberProcesses): QueryBuilder
    {
        $qb = $this->createQB();
        $count = $this->count([]);

        $qb
            ->setFirstResult(floor(($numberProcess - 1) * ($count / $numberProcesses)))
            ->setMaxResults(ceil($numberProcess * ($count / $numberProcesses)));

        return $qb;
    }
}
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

    protected function generateQueryByProcess(int $processNumber, int $numberProcesses): QueryBuilder
    {
        $qb = $this->createQB();
        $count = $this->count([]);

        $qb
            ->setFirstResult(($processNumber - 1) * ceil($count / $numberProcesses))
            ->setMaxResults(ceil($count / $numberProcesses));

        return $qb;
    }

    public function createQB(?string $indexBy = null): QueryBuilder
    {
        return $this->createQueryBuilder($this->alias, $indexBy);
    }
}
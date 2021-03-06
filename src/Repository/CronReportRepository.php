<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Repository;

use Alpabit\ApiSkeleton\Pagination\AliasHelper;
use Alpabit\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;
use Cron\CronBundle\Entity\CronJob;
use Cron\CronBundle\Entity\CronReport;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class CronReportRepository implements PaginatableRepositoryInterface
{
    private $manager;

    private $repository;

    private $aliasHelper;

    public function __construct(EntityManagerInterface $manager, AliasHelper $aliasHelper)
    {
        $this->manager = $manager;
        $this->repository = $manager->getRepository(CronReport::class);
        $this->aliasHelper = $aliasHelper;
    }

    public function queryBuilder(string $alias): QueryBuilder
    {
        return $this->repository->createQueryBuilder($this->aliasHelper->findAlias('root'));
    }

    public function persist(object $object): void
    {
        $this->manager->persist($object);
        $this->manager->flush();
    }

    public function remove(object $object): void
    {
        $this->manager->remove($object);
        $this->manager->flush();
    }

    public function find(string $id): ?CronJob
    {
        return $this->repository->find($id);
    }
}

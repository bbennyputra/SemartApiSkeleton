<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Query\Group;

use Alpabit\ApiSkeleton\Util\StringUtil;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class SearchQueryExtension extends AbstractQueryExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $query = $request->query->get('q');
        if (!$query) {
            return;
        }

        $alias = $this->aliasHelper->findAlias('root');
        $queryBuilder->orWhere($queryBuilder->expr()->like(sprintf('UPPER(%s.code)', $alias), $queryBuilder->expr()->literal(sprintf('%%%s%%', StringUtil::uppercase($query)))));
        $queryBuilder->orWhere($queryBuilder->expr()->like(sprintf('UPPER(%s.name)', $alias), $queryBuilder->expr()->literal(sprintf('%%%s%%', StringUtil::uppercase($query)))));
    }
}

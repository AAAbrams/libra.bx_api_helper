<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockProperties\ElementPropertyValue\Strategy;

use Bitrix\Iblock\ORM\Query;

class NullPropertyValues implements FormatValuesInterface
{
    public function formatValues(array $values): array
    {
        return $values;
    }

    public function buildQuery(Query $query, array $propertyIds): Query
    {
        return $query;
    }
}
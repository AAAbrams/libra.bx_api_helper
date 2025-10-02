<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockProperties\ElementPropertyValue\Strategy;

use Bitrix\Main\ORM\Query\Query;

interface FormatValuesInterface
{
    public function buildQuery(Query $query, array $propertyIds): Query;
    public function formatValues(array $values);
}

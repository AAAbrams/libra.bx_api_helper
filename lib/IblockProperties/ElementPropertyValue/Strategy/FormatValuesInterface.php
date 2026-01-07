<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockProperties\ElementPropertyValue\Strategy;

use Bitrix\Main\ORM\Query\Query;

interface FormatValuesInterface
{
    public static function getValues(Query $query, array $propertyIds, array $elementIds): array;
}

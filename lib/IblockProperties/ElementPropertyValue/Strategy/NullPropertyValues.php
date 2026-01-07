<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockProperties\ElementPropertyValue\Strategy;

use Bitrix\Main\ORM\Query\Query;

class NullPropertyValues implements FormatValuesInterface
{
    public static function getValues(Query $query, array $propertyIds, array $elementIds): array
    {
        return [];
    }
}
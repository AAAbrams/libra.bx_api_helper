<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockProperties\ElementPropertyValue\Strategy;


use Bitrix\Main\ORM\Objectify\Collection;
use Bitrix\Main\ORM\Query\Query;
use Closure;

class CommonPropertyValues implements FormatValuesInterface
{
    public static function getValues(Query $query, array $propertyIds, array $elementIds): array
    {
        /**
         * @var Collection $valueCollection
         */
        $valueCollection = $query
            ->setSelect([
                'IBLOCK_ELEMENT_ID',
                'IBLOCK_PROPERTY_ID',
                'VALUE',
            ])
            ->whereIn('IBLOCK_PROPERTY_ID', $propertyIds)
            ->whereIn('IBLOCK_ELEMENT_ID', $elementIds)
            ->fetchCollection();

        return array_reduce($valueCollection->getAll(), static::getFormatValuesHandler(), []);
    }

    protected static function getFormatValuesHandler(): Closure
    {
        return function (array $acc, array $value) {
            $acc[$value['IBLOCK_ELEMENT_ID']][$value['IBLOCK_PROPERTY_ID']] = $value['VALUE'];
            return $acc;
        };
    }
}

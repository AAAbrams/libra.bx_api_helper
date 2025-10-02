<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockProperties\ElementPropertyValue\Strategy;


use Bitrix\Main\ORM\Query\Query;
use Closure;

class CommonPropertyValues implements FormatValuesInterface
{
    public function buildQuery(Query $query, array $propertyIds): Query
    {
        $query
            ->setSelect([
                'IBLOCK_ELEMENT_ID',
                'IBLOCK_PROPERTY_ID',
                'VALUE',
            ])
            ->whereIn('IBLOCK_PROPERTY_ID', $propertyIds);

        return $query;
    }

    public function formatValues(array $values): array
    {
        /*$select = [
            'IBLOCK_ELEMENT_ID',
            'IBLOCK_PROPERTY_ID',
            'VALUE',
        ];

        $values = $this->simplePropsEntity->getDataClass()::query()
            ->setSelect($select)
            ->whereIn('IBLOCK_PROPERTY_ID', $this->props)
            ->whereIn('IBLOCK_ELEMENT_ID', $this->els)
            ->fetchAll();*/

        return array_reduce($values, $this->getFormatValuesHandler(), []);
    }

    protected function getFormatValuesHandler(): Closure
    {
        return function (array $acc, array $value) {
            $acc[$value['IBLOCK_ELEMENT_ID']][$value['IBLOCK_PROPERTY_ID']] = $value['VALUE'];
            return $acc;
        };
    }
}

<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockProperties\ElementPropertyValue\Strategy;



use Bitrix\Main\ORM\Query\Query;

class SimplePropertyValues implements FormatValuesInterface
{

    public function buildQuery(Query $query, array $propertyIds): Query
    {
        $select = ['IBLOCK_ELEMENT_ID'];
        foreach ($propertyIds as $propertyId) {
            $select[] = 'PROPERTY_' . $propertyId;
        }
        $query->setSelect($select);

        return $query;
    }

    public function formatValues(array $values): array
    {
        /*$select = ['IBLOCK_ELEMENT_ID'];
        foreach ($this->props as $prop) {
            $select[] = 'PROPERTY_' . $prop;
        }
        $values = $this->simplePropsEntity->getDataClass()::query()
            ->setSelect($select)
            ->whereIn('IBLOCK_ELEMENT_ID', $this->els)
            ->fetchAll();*/


        $result = [];
        foreach ($values as $value) {
            $elementId = (int)$value['IBLOCK_ELEMENT_ID'];
            foreach ($value as $propertyFieldName => $propertyValue) {
                [$prefix, $propertyId] = explode('_', $propertyFieldName);
                if ($prefix !== 'PROPERTY' || (int)$propertyId === 0) {
                    continue;
                }
                $result[$elementId][$propertyId] = $propertyValue;
            }
        }

        return $result;
    }
}

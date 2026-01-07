<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockProperties\ElementPropertyValue\Strategy;

use Bitrix\Main\ORM\Objectify\Collection;
use Bitrix\Main\ORM\Objectify\EntityObject;
use Bitrix\Main\ORM\Query\Query;

class SimplePropertyValues implements FormatValuesInterface
{
    public static function getValues(Query $query, array $propertyIds, array $elementIds): array
    {
        $select = ['IBLOCK_ELEMENT_ID'];
        $entity = $query->getEntity();
        foreach ($propertyIds as $propertyId) {
            $propertyName = 'PROPERTY_' . $propertyId;
            $enumRefName = $propertyName . '_ENUM_REF';

            if ($entity->hasField($enumRefName)) {
                $select[] = $enumRefName;
            } elseif ($entity->hasField($propertyName)) {
                $select[] = $propertyName;
            }
        }

        /**
         * @var Collection $valueCollection
         */
        $valueCollection = $query
            ->setSelect($select)
            ->whereIn('IBLOCK_ELEMENT_ID', $elementIds)
            ->fetchCollection();

        return self::formatValueCollection($valueCollection, $select);

    }

    private static function formatValueCollection(Collection $collection, array $selectedFieldNames): array
    {
        $result = [];
        foreach ($collection as $elementPropertiesRow) {
            $elementId = (int)$elementPropertiesRow->get('IBLOCK_ELEMENT_ID');
            foreach ($selectedFieldNames as $propertyFieldName) {
                [$prefix, $propertyId, $refType] = explode('_', $propertyFieldName);
                if ($prefix !== 'PROPERTY' || (int)$propertyId === 0) {
                    continue;
                }
                $propertyValue = $elementPropertiesRow->get($propertyFieldName);

                if ($refType === 'ENUM') {
                    $propertyValue = $propertyValue instanceof EntityObject ? $propertyValue->collectValues() : [];
                }

                $result[$elementId][(int)$propertyId] = $propertyValue;
            }
        }

        return $result;
    }
}

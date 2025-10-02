<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockProperties\ElementPropertyValue;

use Libra\BxApiHelper\Iblock\IblockDto;
use Libra\BxApiHelper\IblockProperties\ElementPropertyValue\Strategy\CommonPropertyValues;
use Libra\BxApiHelper\IblockProperties\ElementPropertyValue\Strategy\FormatValuesInterface;
use Libra\BxApiHelper\IblockProperties\ElementPropertyValue\Strategy\MultiplePropertyValues;
use Libra\BxApiHelper\IblockProperties\ElementPropertyValue\Strategy\NullPropertyValues;
use Libra\BxApiHelper\IblockProperties\ElementPropertyValue\Strategy\SimplePropertyValues;
use Libra\BxApiHelper\IblockProperties\PropertyCollection;

class ElementPropertyValueHelper
{
    private FormatValuesInterface $simpleValuesStrategy;
    private SimpleProperties $simplePropsEntity;
    private MultipleProperties $multiPropsEntity;

    public function __construct(
        private readonly IblockDto $iblockDto
    )
    {
        $this->simpleValuesStrategy = $this->getSimpleValuesStrategy();
        $this->simplePropsEntity = new SimpleProperties($this->iblockDto);
        $this->multiPropsEntity = new MultipleProperties($this->iblockDto);
    }

    public function getFormattedQueryResult(PropertyCollection $propertyCollection, array $elementIds): array
    {
        $simple = $propertyCollection->simpleOnly();
        $simplePropsValues = [];
        if ($simple->count() > 0) {
            $simplePropsQuery = $this->simpleValuesStrategy->buildQuery(
                $this->simplePropsEntity->getModelClass()::query(),
                $simple->getIdList()
            );

            $simpleProps = $simplePropsQuery
                ->whereIn('IBLOCK_ELEMENT_ID', $elementIds)
                ->fetchAll();

            $simplePropsValues = $this->simpleValuesStrategy->formatValues($simpleProps);
        }

        $multiple = $propertyCollection->multipleOnly();
        $multiPropsValues = [];
        if ($multiple->count() > 0) {
            $multiPropsStrategy = new MultiplePropertyValues();
            $multiPropsQuery = $multiPropsStrategy->buildQuery(
                $this->multiPropsEntity->getModelClass()::query(),
                $multiple->getIdList()
            );

            $multiProps = $multiPropsQuery
                ->whereIn('IBLOCK_ELEMENT_ID', $elementIds)
                ->fetchAll();

            $multiPropsValues = $multiPropsStrategy->formatValues($multiProps);
            foreach ($multiPropsValues as &$elementMultiProps) {
                $elementMultiProps = array_reduce($multiple->getIdList(), function (array $acc, int $propertyId) {
                    if (!array_key_exists($propertyId, $acc)) {
                        $acc[$propertyId] = [];
                    }
                    return $acc;
                }, $elementMultiProps);
            }

        }


        foreach ($simplePropsValues as $elementId => &$simpleProperties) {
            $multiProps = $multiPropsValues[$elementId] ?? [];
            if (count($multiProps) === 0) {
                continue;
            }
            foreach ($multiProps as $propertyId => $multiPropVal) {
                $simpleProperties[$propertyId] = $multiPropVal;
            }
        }

        return $simplePropsValues;
    }

    private function getSimpleValuesStrategy(): FormatValuesInterface
    {
        return match ($this->iblockDto->version) {
            1 => new CommonPropertyValues(),
            2 => new SimplePropertyValues(),
            default => new NullPropertyValues(),
        };
    }
}

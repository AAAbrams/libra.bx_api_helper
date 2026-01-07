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
    /**
     * @var class-string<FormatValuesInterface>
     */
    private string $simpleValuesStrategy;
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
        if (count($elementIds) === 0) {
            return [];
        }

        $simple = $propertyCollection->simpleOnly();
        $resultPropertiesValues = [];

        if ($simple->count() > 0) {
            $resultPropertiesValues = $this->simpleValuesStrategy::getValues(
                $this->simplePropsEntity->getModelClass()::query(),
                $simple->getIdList(),
                $elementIds
            );
        }

        $multiple = $propertyCollection->multipleOnly();
        $multiPropsValues = [];
        if ($multiple->count() > 0) {
            $multiPropsValues = MultiplePropertyValues::getValues(
                $this->multiPropsEntity->getModelClass()::query(),
                $multiple->getIdList(),
                $elementIds
            );

            foreach ($multiPropsValues as &$elementMultiProps) {
                $elementMultiProps = array_reduce($multiple->getIdList(), function (array $acc, int $propertyId) {
                    if (!array_key_exists($propertyId, $acc)) {
                        $acc[$propertyId] = [];
                    }
                    return $acc;
                }, $elementMultiProps);
            }
        }

        foreach ($resultPropertiesValues as $elementId => &$simpleProperties) {
            $multiProps = $multiPropsValues[$elementId] ?? [];
            if (count($multiProps) === 0) {
                continue;
            }
            foreach ($multiProps as $propertyId => $multiPropVal) {
                $simpleProperties[$propertyId] = $multiPropVal;
            }
        }

        return $resultPropertiesValues;
    }

    /**
     * @return class-string<FormatValuesInterface>
     */
    private function getSimpleValuesStrategy(): string
    {
        return match ($this->iblockDto->version) {
            1 => CommonPropertyValues::class,
            2 => SimplePropertyValues::class,
            default => NullPropertyValues::class,
        };
    }
}

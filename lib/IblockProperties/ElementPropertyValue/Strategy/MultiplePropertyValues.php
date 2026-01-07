<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockProperties\ElementPropertyValue\Strategy;

use Closure;

class MultiplePropertyValues extends CommonPropertyValues
{
    protected static function getFormatValuesHandler(): Closure
    {
        return function (array $acc, array $value) {
            $acc[$value['IBLOCK_ELEMENT_ID']][$value['IBLOCK_PROPERTY_ID']][] = $value['VALUE'];
            return $acc;
        };
    }
}

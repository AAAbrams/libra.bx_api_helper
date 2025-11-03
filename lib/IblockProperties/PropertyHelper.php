<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockProperties;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Closure;

class PropertyHelper
{
    /**
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     */
    public static function getPropertyCollection(int $iblockId, ?Closure $queryFilter = null): PropertyCollection
    {
        $properties = PropertyTable::query()
            ->where('IBLOCK_ID', $iblockId)
            ->cacheJoins(true)
            ->setCacheTtl(300);

        if (!is_null($queryFilter)) {
            $queryFilter($properties);
        }

        return $properties->fetchCollection();
    }
}
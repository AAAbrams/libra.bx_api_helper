<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\Iblock;

use Bitrix\Iblock\IblockTable;
use Closure;
use Exception;

class IblockHelper
{

    public static function getIblockDto(Closure $filter): IblockDto
    {
        $iblockNullObject = new IblockDto();

        try {
            $iblock = IblockTable::query()
                ->setSelect([
                    'ID',
                    'VERSION',
                    'CODE',
                ])
                ->cacheJoins(true)
                ->setCacheTtl(3600 * 24)
                ->setLimit(1);

            $iblock = $filter($iblock)
                ->fetch();

            return is_array($iblock) ? new IblockDto($iblock) : $iblockNullObject;

        } catch (Exception $e) {
            return $iblockNullObject;
        }

    }
}

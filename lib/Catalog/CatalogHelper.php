<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\Catalog;

use Closure;
use Exception;

class CatalogHelper
{
    /**
     * @param Closure $filter
     * @return IblockSkuDto[]
     */
    public static function getIblockSkuDtoList(Closure $filter): array
    {
        try {
            $skuIblockQuery = CatalogIblockTable::query()
                ->setSelect([
                    'IBLOCK_ID',
                    'IBLOCK.VERSION',
                    'IBLOCK.CODE',
                    'SKU_PROPERTY_ID',
                    'PRODUCT_IBLOCK_ID',
                ])
                ->cacheJoins(true)
                ->setCacheTtl(3600 * 24);

            $filter($skuIblockQuery);

            $skuIblockCollection = $skuIblockQuery->fetchCollection();

            if ($skuIblockCollection->count() === 0) {
                return [];
            }

            return array_map(static function ($skuIblock) {
                return new IblockSkuDto([
                    'ID' => $skuIblock->getIblockId(),
                    'VERSION' => $skuIblock->getIblock()->getVersion(),
                    'CODE' => $skuIblock->getIblock()->getCode(),
                    'PRODUCT_IBLOCK_ID' => $skuIblock->getProductIblockId(),
                    'SKU_PROPERTY_ID' => $skuIblock->getSkuPropertyId(),
                ]);
            }, $skuIblockCollection->getAll());


        } catch (Exception $e) {
            return [];
        }
    }

    public static function getIblockSkuDto(int $productIblockId): IblockSkuDto
    {
        $iblockSkuNullObject = new IblockSkuDto();

        if ($productIblockId <= 0) {
            return $iblockSkuNullObject;
        }

        $iblockSkuDtoList = self::getIblockSkuDtoList(
            static fn (LibraCatalogIblockQuery $query) =>
                $query->where('PRODUCT_IBLOCK_ID', $productIblockId)->setLimit(1)
        );

        $iblockSkuDto = reset($iblockSkuDtoList);

        return $iblockSkuDto instanceof IblockSkuDto ? $iblockSkuDto : $iblockSkuNullObject;
    }
}

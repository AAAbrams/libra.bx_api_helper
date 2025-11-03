<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\Catalog;

use Libra\BxApiHelper\Iblock\IblockDto;

class IblockSkuDto extends IblockDto
{
    private(set) int $productIblockId;
    private(set) int $skuPropertyId;

    public function __construct(array $iblockData = [])
    {
        parent::__construct($iblockData);
        $this->productIblockId = (int)$iblockData['PRODUCT_IBLOCK_ID'];
        $this->skuPropertyId = (int)$iblockData['SKU_PROPERTY_ID'];
    }
}
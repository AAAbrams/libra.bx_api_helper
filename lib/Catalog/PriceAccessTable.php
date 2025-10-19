<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\Catalog;

use Bitrix\Catalog\GroupAccessTable;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Fields\Relations\Reference;

class PriceAccessTable extends GroupAccessTable
{
    public static function getMap(): array
    {
        return array_merge(parent::getMap(), [
            new Reference(
                'PRODUCT_PRICE',
                ProductPriceTable::class,
                Join::on('this.CATALOG_GROUP_ID', 'ref.CATALOG_GROUP_ID')
            ),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\Catalog {

    use Bitrix\Catalog\GroupAccessTable;
    use Bitrix\Catalog\PriceTable;
    use Bitrix\Main\ORM\Fields\IntegerField;
    use Bitrix\Main\ORM\Fields\Relations\OneToMany;
    use Bitrix\Main\ORM\Query\Filter\ConditionTree;
    use Bitrix\Main\ORM\Query\Result;
    use Bitrix\Main\ORM\Objectify\Collection;
    use Bitrix\Main\ORM\Objectify\EntityObject;
    use Libra\BxApiHelper\OrmSupport\QueryBuilder;

    /**
     * @method LibraProductPriceCollection fetchCollection()
     * @method LibraProductPriceObject fetchObject()
     * @method LibraProductPriceResult exec()
     * @method self scopeQuantityRange(int $quantity = 1)
     * @method self scopeUserGroupsAccess(array $userGroups, array $access = [])
     */
    class LibraProductPriceQuery extends QueryBuilder {}

    class LibraProductPriceCollection extends Collection {
        public static $dataClass = ProductPriceTable::class;
    }

    class LibraProductPriceObject extends EntityObject {
        public static $dataClass = ProductPriceTable::class;
    }

    /**
     * @method LibraProductPriceCollection fetchCollection()
     * @method LibraProductPriceObject fetchObject()
     */
    class LibraProductPriceResult extends Result {}

    /**
     * @method static LibraProductPriceResult getByPrimary($primary, array $parameters = [])
     * @method static LibraProductPriceQuery query()
     * @method static LibraProductPriceCollection createCollection()
     */
    class ProductPriceTable extends PriceTable
    {
        public static function getMap(): array
        {
            return array_merge(parent::getMap(), [
                new IntegerField('PRICE_TYPE_ID')
                    ->configureColumnName('CATALOG_GROUP_ID'),
                new OneToMany(
                    'ACCESS',
                    PriceAccessTable::class,
                    'PRODUCT_PRICE'
                ),
            ]);
        }

        public static function getObjectClass(): string
        {
            return LibraProductPriceObject::class;
        }

        public static function getCollectionClass(): string
        {
            return LibraProductPriceCollection::class;
        }

        public static function getQueryClass(): string
        {
            return LibraProductPriceQuery::class;
        }

        /**
         * @param QueryBuilder $query
         * @param int $quantity
         * @return void
         * @throws \Bitrix\Main\ArgumentException
         * @see
         */
        public static function scopeQuantityRange(QueryBuilder $query, int $quantity = 1): void
        {
            $query->where(
                QueryBuilder::filter()
                    ->logic(ConditionTree::LOGIC_OR)
                    ->where('QUANTITY_FROM', '<=', $quantity)
                    ->whereNull('QUANTITY_FROM')
            )->where(
                QueryBuilder::filter()
                    ->logic(ConditionTree::LOGIC_OR)
                    ->where('QUANTITY_TO', '>=', $quantity)
                    ->whereNull('QUANTITY_TO')
            );
        }

        public static function scopeUserGroupsAccess(
            QueryBuilder $query,
            array $userGroups,
            array $access = [GroupAccessTable::ACCESS_BUY]
        ): void
        {
            $query->whereIn('ACCESS.GROUP_ID', $userGroups);
            if (count($access) > 0) {
                $query->whereIn('ACCESS.ACCESS', $access);
            }
        }
    }
}

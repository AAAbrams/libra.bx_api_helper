<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\Catalog {

    use Bitrix\Main\ORM\Query\Result;
    use Bitrix\Main\ORM\Objectify\Collection;
    use Bitrix\Main\ORM\Objectify\EntityObject;
    use Bitrix\Main\ORM\Query\Query;

    /**
     * @method LibraCatalogIblockCollection fetchCollection()
     * @method LibraCatalogIblockObject fetchObject()
     * @method LibraCatalogIblockResult exec()
     */
    class LibraCatalogIblockQuery extends Query {}

    class LibraCatalogIblockCollection extends Collection {
        public static $dataClass = CatalogIblockTable::class;
    }

    class LibraCatalogIblockObject extends EntityObject {
        public static $dataClass = CatalogIblockTable::class;
    }

    /**
     * @method LibraCatalogIblockCollection fetchCollection()
     * @method LibraCatalogIblockObject fetchObject()
     */
    class LibraCatalogIblockResult extends Result {}


    /**
     * @method static LibraCatalogIblockResult getByPrimary($primary, array $parameters = [])
     * @method static LibraCatalogIblockQuery query()
     */
    class CatalogIblockTable extends \Bitrix\Catalog\CatalogIblockTable
    {
        public static function getObjectClass(): string
        {
            return LibraCatalogIblockObject::class;
        }

        public static function getCollectionClass(): string
        {
            return LibraCatalogIblockCollection::class;
        }

        public static function getQueryClass(): string
        {
            return LibraCatalogIblockQuery::class;
        }

    }
}

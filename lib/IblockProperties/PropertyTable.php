<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockProperties {

    use Bitrix\Main\ORM\Query\Result;
    use Bitrix\Main\ORM\Objectify\Collection;
    use Bitrix\Main\ORM\Objectify\EntityObject;
    use Bitrix\Main\ORM\Query\Query;

    /**
     * @method PropertyCollection fetchCollection()
     * @method PropertyObject fetchObject()
     * @method LibraPropertyResult exec()
     */
    class LibraPropertyQuery extends Query {}

    class LibraPropertyCollection extends Collection {
        public static $dataClass = PropertyTable::class;
    }

    class LibraPropertyObject extends EntityObject {
        public static $dataClass = PropertyTable::class;
    }

    /**
     * @method PropertyCollection fetchCollection()
     * @method PropertyObject fetchObject()
     */
    class LibraPropertyResult extends Result {}


    /**
     * @method static LibraPropertyResult getByPrimary($primary, array $parameters = [])
     * @method static LibraPropertyQuery query()
     */
    class PropertyTable extends \Bitrix\Iblock\PropertyTable
    {
        public static function getObjectClass(): string
        {
            return PropertyObject::class;
        }

        public static function getCollectionClass(): string
        {
            return PropertyCollection::class;
        }

        public static function getQueryClass(): string
        {
            return LibraPropertyQuery::class;
        }

    }
}

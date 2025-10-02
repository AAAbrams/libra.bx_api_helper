<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockElement {

    use Bitrix\Main\ORM\Query\Result;
    use Bitrix\Main\ORM\Objectify\Collection;
    use Bitrix\Main\ORM\Objectify\EntityObject;
    use Bitrix\Main\ORM\Query\Query;

    /**
     * @method ElementCollection fetchCollection()
     * @method ElementObject fetchObject()
     * @method LibraElementResult exec()
     */
    class LibraElementQuery extends Query {}

    class LibraElementCollection extends Collection {
        public static $dataClass = ElementTable::class;
    }

    class LibraElementObject extends EntityObject {
        public static $dataClass = ElementTable::class;
    }

    /**
     * @method ElementCollection fetchCollection()
     * @method EntityObject fetchObject()
     */
    class LibraElementResult extends Result {}


    /**
     * @method static LibraElementResult getByPrimary($primary, array $parameters = [])
     * @method static LibraElementQuery query()
     */
    class ElementTable extends \Bitrix\Iblock\ElementTable
    {
        public static function getObjectClass(): string
        {
            return ElementObject::class;
        }

        public static function getCollectionClass(): string
        {
            return ElementCollection::class;
        }

        public static function getQueryClass(): string
        {
            return LibraElementQuery::class;
        }
    }
}

<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockProperties;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ORM\Fields\FloatField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Query\Join;
use Exception;

class ElementPropertyTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'b_iblock_element_property';
    }

    /**
     * @throws Exception
     */
    public static function getMap(): array
    {
        return [
            new IntegerField('ID')
                ->configurePrimary(true)
                ->configureAutocomplete(true),

            new IntegerField('IBLOCK_PROPERTY_ID'),

            new IntegerField('IBLOCK_ELEMENT_ID'),

            new Reference(
                'ELEMENT', ElementTable::class,
                Join::on('this.IBLOCK_ELEMENT_ID', 'ref.ID')
            ),

            new TextField('VALUE'),

            new StringField('VALUE_TYPE'),

            new IntegerField('VALUE_ENUM'),

            new FloatField('VALUE_NUM'),

            new StringField('DESCRIPTION'),

            new Reference(
                'ENUM',
                PropertyEnumerationTable::class,
                Join::on('this.VALUE_ENUM', 'ref.ID')
            ),
        ];
    }
}

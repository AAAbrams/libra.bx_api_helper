<?php

namespace Libra\BxApiHelper\IblockProperties\ElementPropertyValue;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Entity;
use Bitrix\Main\SystemException;
use Libra\BxApiHelper\IblockProperties\ElementPropertyValue\Strategy\FormatValuesInterface;

class MultipleProperties extends PropertiesEntity
{
    protected const string ENTITY_NAME_POSTFIX = 'MultiplePropertiesTable';
    private const string MULTI_TABLE_NAME_PREFIX = 'b_iblock_element_prop_m';

    /**
     * @throws SystemException
     * @throws ArgumentException
     */
    protected function compileEntity(): Entity
    {
        $entityName = Entity::snake2camel($this->iblockDto->code) . self::ENTITY_NAME_POSTFIX;
        return $this->compileCommonEntity($entityName);
    }

    protected function getTableName(): string
    {
        return $this->iblockDto->version === 2 ? (self::MULTI_TABLE_NAME_PREFIX . $this->iblockDto->id) : '';
    }
}

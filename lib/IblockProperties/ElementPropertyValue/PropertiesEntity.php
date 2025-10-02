<?php

namespace Libra\BxApiHelper\IblockProperties\ElementPropertyValue;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Entity;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\SystemException;
use Libra\BxApiHelper\Iblock\IblockDto;
use Libra\BxApiHelper\IblockProperties\ElementPropertyTable;
use Libra\BxApiHelper\IblockProperties\ElementPropertyValue\Strategy\FormatValuesInterface;

/**
 *
 * b_iblock_property - таблица с данными свойств инфоблоков \Bitrix\Iblock\PropertyTable
 *
 * b_iblock_element_prop_m{iblock_id} - таблица множ значений свойств элементов для ИБ
 * b_iblock_element_prop_s{iblock_id} - таблица ед. значений свойств элементов для ИБ
 * b_iblock_element_property - общая таблица хранения значений свойств элементов
 * b_iblock_property_enum - таблица значений свойств списков \Bitrix\Iblock\PropertyEnumerationTable
 */
abstract class PropertiesEntity
{
    protected const string NAMESPACE = 'Libra\\BxApiHelper\\ORM\\IblockProperties\\ElementPropertyValue';

    protected IblockDto $iblockDto;

    abstract protected function compileEntity(): Entity;

    abstract protected function getTableName(): string;

    public function __construct(IblockDto $iblockDto)
    {
        $this->iblockDto = $iblockDto;
    }

    /**
     * @return class-string<DataManager>
     */
    public function getModelClass(): string
    {
        return $this->getEntity()->getDataClass();
    }

    public function getEntity(): Entity
    {
        return $this->compileEntity();
    }

    public function getEntityName(): string
    {
        return Entity::snake2camel($this->iblockDto->code) . static::ENTITY_NAME_POSTFIX;
    }

    public function getFullEntityName(): string
    {
        return self::NAMESPACE . '\\' . $this->getEntityName();
    }

    /**
     * @throws ArgumentException
     * @throws SystemException
     */
    protected function compileCommonEntity(string $entityName): Entity
    {

        $fullEntityName = $this->getFullEntityName();
        if (Entity::has($fullEntityName)) {
            return Entity::getInstance($fullEntityName);
        }

        $fields = array_filter(
            ElementPropertyTable::getMap(),
            fn (Field $field) => $field->getName() !== 'VALUE_TYPE'
        );

        $tableName = $this->getTableName();

        return Entity::compileEntity(
            $entityName,
            array_values($fields),
            [
                'table_name' => strlen($tableName) > 0 ? $tableName : ElementPropertyTable::getTableName(),
                'namespace' => self::NAMESPACE,
            ]
        );
    }
}

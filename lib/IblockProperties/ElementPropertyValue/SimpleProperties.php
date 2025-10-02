<?php

namespace Libra\BxApiHelper\IblockProperties\ElementPropertyValue;

use Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Entity\Query\Join;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\ORM\Entity;
use Bitrix\Main\ORM\Fields\FloatField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\SystemException;
use Libra\BxApiHelper\IblockProperties\PropertyHelper;
use Libra\BxApiHelper\IblockProperties\PropertyObject;

class SimpleProperties extends PropertiesEntity
{
    protected const string ENTITY_NAME_POSTFIX = 'SimplePropertiesTable';
    private const string SIMPLE_TABLE_NAME_PREFIX = 'b_iblock_element_prop_s';

    private array $fields;

    /**
     * @return Entity
     * @throws ArgumentException
     * @throws SystemException
     */
    protected function compileEntity(): Entity
    {
        $entityName = $this->getEntityName();

        if ($this->iblockDto->version === 2) {
            $tableName =  self::SIMPLE_TABLE_NAME_PREFIX . $this->iblockDto->id;

            $fullEntityName = self::NAMESPACE . '\\' . $entityName;
            if (Entity::has($fullEntityName)) {
                return Entity::getInstance($fullEntityName);
            }

            return Entity::compileEntity(
                $entityName,
                $this->getFields(),
                [
                    'table_name' => $tableName,
                    'namespace' => self::NAMESPACE,
                ]);
        }


        return $this->compileCommonEntity($entityName);
    }



    protected function getTableName(): string
    {
        return $this->iblockDto->version === 2 ? (self::SIMPLE_TABLE_NAME_PREFIX . $this->iblockDto->id) : '';
    }

    private function getFields(): array
    {
        $this->fields = [
            new IntegerField('IBLOCK_ELEMENT_ID')
                ->configurePrimary(true),
        ];

        $properties = PropertyHelper::getPropertyCollection($this->iblockDto->id)
            ->simpleOnly();

         foreach ($properties as $property) {
            if (empty($property->getCode())) continue;
             try {
                 $this->addPropertyFields($property);
             } catch (SystemException $e) {
                 continue;
             }

        }

        return $this->fields;
    }

    /**
     * @param PropertyObject $prop
     * @return void
     * @throws SystemException
     */
    private function addPropertyFields(PropertyObject $prop): void
    {
        $fieldClass = match ($prop->getPropertyType()) {
            PropertyTable::TYPE_FILE,
            PropertyTable::TYPE_SECTION,
            PropertyTable::TYPE_ELEMENT,
            PropertyTable::TYPE_LIST => IntegerField::class,
            PropertyTable::TYPE_NUMBER => FloatField::class,
            default => StringField::class,
        };

        $propColumnName = 'PROPERTY_' . $prop->getId();
        $parameters = [
            'column_name' => $propColumnName,
        ];

        $this->fields[] = new $fieldClass($prop->getCode(), $parameters);
        $this->fields[] = new $fieldClass($propColumnName);

        if ($prop->getPropertyType() === PropertyTable::TYPE_LIST) {
            $this->fields[] = new ReferenceField(
                "{$propColumnName}_ENUM_REF",
                PropertyEnumerationTable::class,
                Join::on("this.$propColumnName", 'ref.ID')
            );
        }
    }
}
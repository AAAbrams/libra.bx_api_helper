<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockProperties;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\SystemException;

class PropertyCollection extends LibraPropertyCollection
{
    /**
     * @return self
     * @throws ArgumentException
     * @throws SystemException
     */
    public function activeOnly(): self
    {
        return array_reduce($this->getAll(), function (PropertyCollection $collection, PropertyObject $property) {
            if ($property->isActive()) {
                $collection->add($property);
            }
            return $collection;
        }, new self());
    }

    /**
     * @return self
     * @throws ArgumentException
     * @throws SystemException
     */
    public function multipleOnly(): self
    {
        return array_reduce($this->getAll(), function (PropertyCollection $collection, PropertyObject $property) {
            if ($property->isMultiple()) {
                $collection->add($property);
            }
            return $collection;
        }, new self());
    }

    /**
     * @return self
     * @throws ArgumentException
     * @throws SystemException
     */
    public function simpleOnly(): self
    {
        return array_reduce($this->getAll(), function (PropertyCollection $collection, PropertyObject $property) {
            if (!$property->isMultiple()) {
                $collection->add($property);
            }
            return $collection;
        }, new self());
    }
}

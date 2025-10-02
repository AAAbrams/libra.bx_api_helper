<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockElement;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Closure;
use Libra\BxApiHelper\Iblock\IblockDto;
use Libra\BxApiHelper\IblockProperties\ElementPropertyValue\ElementPropertyValueHelper;
use Libra\BxApiHelper\IblockProperties\PropertyCollection;
use Libra\BxApiHelper\IblockProperties\PropertyHelper;

class ElementCollection extends LibraElementCollection
{
    private IblockDto $iblockDto;

    public function setIblockDto(IblockDto $iblockDto): self
    {
        $this->iblockDto = $iblockDto;
        return $this;
    }

    /**
     * @param string|Closure $filter
     * @return $this
     * @throws ArgumentException
     * @throws SystemException
     * @throws ObjectPropertyException
     */
    public function withProperties(Closure $filter): self
    {

        $propertyCollection = PropertyHelper::getPropertyCollection($this->iblockDto->id);

        /**
         * @var PropertyCollection $propertyCollection
         */
        $propertyCollection = $filter($propertyCollection);


        $props = new ElementPropertyValueHelper($this->iblockDto);

        $elementsPropsValues = $props->getFormattedQueryResult($propertyCollection, $this->getIdList());
        /**
         * @var ElementObject $element
         */
        foreach ($this as $element) {
            $element->properties = $elementsPropsValues[$element->getId()] ?? [];
        }

        return $this;
    }
}

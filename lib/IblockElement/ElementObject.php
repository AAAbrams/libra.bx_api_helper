<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockElement;

class ElementObject extends LibraElementObject
{
    public array $properties {
        set {
            $this->properties = $value;
        }
    }

    /**
     * @param int $propertyId
     * @return mixed
     */
    public function getPropertyValue(int $propertyId): mixed
    {
        return $this->properties[$propertyId];
    }

}

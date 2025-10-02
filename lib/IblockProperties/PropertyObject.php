<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockProperties;

/**
 * @method bool hasActive()
 * @method bool getActive()
 * @method bool hasMultiple()
 * @method bool getMultiple()
 */
class PropertyObject extends LibraPropertyObject
{
    public function isActive(): bool
    {
        return $this->hasActive() && $this->getActive();
    }

    public function isMultiple(): bool
    {
        return $this->hasMultiple() && $this->getMultiple();
    }

    public function isSimple(): bool
    {
        return !$this->getMultiple();
    }
}
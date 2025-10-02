<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\Iblock;

class IblockDto
{
    private(set) int $id;
    private(set) int $version;
    private(set) string $code;

    public function __construct(array $iblockData = [])
    {
        $this->id = (int)$iblockData['ID'];
        $this->version = (int)$iblockData['VERSION'];
        $this->code = (string)$iblockData['CODE'];
    }
}

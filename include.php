<?php

declare(strict_types=1);

use Bitrix\Main\Loader;

Loader::includeModule('iblock');
Loader::includeModule('catalog');
Loader::includeModule('sale');

require Loader::getLocal('modules/libra.bx_api_helper/vendor/autoload.php');

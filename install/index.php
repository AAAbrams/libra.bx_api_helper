<?php

declare(strict_types=1);


use Bitrix\Main\ModuleManager;

class libra_bx_api_helper extends CModule
{
    public function __construct()
    {
        $this->MODULE_ID = 'libra.bx_api_helper';
        $this->MODULE_NAME = 'Libra API Layer';
        $this->MODULE_DESCRIPTION = '';
        $this->MODULE_VERSION = '1.0';
        $this->MODULE_VERSION_DATE = '2025-09-26';
    }

    public function DoInstall(): void
    {
        ModuleManager::registerModule($this->MODULE_ID);
    }

    public function DoUninstall(): void
    {
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}

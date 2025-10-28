<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

$app = \Bitrix\Main\Application::getInstance();
$request = $app->getContext()->getRequest();

$module = $request->get('module');
$resource = $request->get('resource');
$page = $request->get('page');
$isPopup = (string)$request->get('popup') === 'Y' ;

\Bitrix\Main\Loader::includeModule($module);
$config = \Bitrix\Main\Config\Configuration::getInstance($module);

$resourceClass = $config->get('resources')[$resource];
$resource = new $resourceClass();

if ($isPopup) {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_popup_admin.php");
}
else {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
}

$resource->renderPage($page);

if ($isPopup) {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_popup_admin.php");
}
else {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
}

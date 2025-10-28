<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

$app = \Bitrix\Main\Application::getInstance();
$request = $app->getContext()->getRequest();

$module = $request->get('module');
$resource = $request->get('resource');

$action = $request->getJsonList()['action_type'];
\Bitrix\Main\Loader::includeModule($module);
$config = \Bitrix\Main\Config\Configuration::getInstance($module);

$resourceClass = $config->get('resources')[$resource];
$resource = new $resourceClass();

if ($action === \Bitrix\Iblock\Grid\ActionType::DELETE) {
    $resource->delete();
} else {
    $resource->save();
}

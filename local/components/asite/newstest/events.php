<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;

if (Loader::includeModule('iblock')) {
    $eventManager = EventManager::getInstance();

    $eventManager->addEventHandler(
        'iblock',
        'OnAfterIBlockElementAdd',
        ['NewsListComponent', 'clearCache']
    );

    $eventManager->addEventHandler(
        'iblock',
        'OnAfterIBlockElementUpdate',
        ['NewsListComponent', 'clearCache']
    );


}
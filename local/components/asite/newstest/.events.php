<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

if (!Loader::includeModule('iblock')) {
    return;
}

$eventManager = \Bitrix\Main\EventManager::getInstance();

$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementAdd', array('NewsListComponent', 'clearCache'));
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', array('NewsListComponent', 'clearCache'));
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementDelete', array('NewsListComponent', 'clearCache'));
$eventManager->addEventHandler('iblock', 'OnAfterIBlockSectionAdd', array('NewsListComponent', 'clearCache'));
$eventManager->addEventHandler('iblock', 'OnAfterIBlockSectionUpdate', array('NewsListComponent', 'clearCache'));
$eventManager->addEventHandler('iblock', 'OnAfterIBlockSectionDelete', array('NewsListComponent', 'clearCache'));
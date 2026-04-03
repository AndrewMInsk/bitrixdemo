<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/functions.php");
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");
require_once($_SERVER["DOCUMENT_ROOT"]."/local/components/asite/newstest/class.php"); // подумать может убрать

$eventManager = \Bitrix\Main\EventManager::getInstance();

$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementAdd', array('NewsListComponent', 'clearCache'));
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', array('NewsListComponent', 'clearCache'));
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementDelete', array('NewsListComponent', 'clearCache'));
$eventManager->addEventHandler('iblock', 'OnAfterIBlockSectionAdd', array('NewsListComponent', 'clearCache'));
$eventManager->addEventHandler('iblock', 'OnAfterIBlockSectionUpdate', array('NewsListComponent', 'clearCache'));
$eventManager->addEventHandler('iblock', 'OnAfterIBlockSectionDelete', array('NewsListComponent', 'clearCache'));
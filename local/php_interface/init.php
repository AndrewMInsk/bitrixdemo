<?php
use Bitrix\Main\Loader;

require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/functions.php");

//иначе класс не подгружен, и к нему ивенты не подцепятся
Loader::registerAutoLoadClasses(
    null,
    [
        'NewsListComponent' => '/local/components/asite/newstest/class.php'
    ]
);
//ивенты
require_once($_SERVER["DOCUMENT_ROOT"]."/local/components/asite/newstest/events.php");
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");

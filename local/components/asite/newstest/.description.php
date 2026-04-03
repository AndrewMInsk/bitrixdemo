<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
    'NAME' => GetMessage('NEWS_LIST_COMPONENT_NAME'), // локализую только тут для примера
    'DESCRIPTION' => 'Список новостей тестовых с фильтрацией и пагинацией',
    'ICON' => '',
    'CACHE_PATH' => 'Y',
    'PATH' => array(
        'ID' => 'my_components',
        'NAME' => 'Мои компоненты',
    ),
);
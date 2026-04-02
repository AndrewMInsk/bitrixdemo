<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
    'NAME' => GetMessage('NEWS_LIST_COMPONENT_NAME'),
    'DESCRIPTION' => 'Список новостей тестовых с фильтрацией и пагинацией',
    'ICON' => '',
    'CACHE_PATH' => 'Y',
    'PATH' => array(
        'ID' => 'my_components',
        'NAME' => 'Мои компоненты',
    ),
);
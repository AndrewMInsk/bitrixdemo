<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = array(
    'PARAMETERS' => array(
        'IBLOCK_ID' => array(
            'NAME' => 'Инфоблока ID',
            'TYPE' => 'STRING',
            'MULTIPLE' => 'N',
            'DEFAULT' => '',
        ),
        'CACHE_TIME' => array(
            'NAME' => 'Время кеширования',
            'TYPE' => 'STRING',
            'MULTIPLE' => 'N',
            'DEFAULT' => '36000000',
        ),
    ),
);
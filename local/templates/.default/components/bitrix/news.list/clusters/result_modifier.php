<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
global $APPLICATION;

if (!Loader::includeModule("iblock")) {
    return;
}

$arFilter = array(
    'IBLOCK_ID' => 5,
    'ACTIVE' => 'Y',
    'DEPTH_LEVEL' => 1, // только топ-уровень
);

$arOrder = array(
    'SORT' => 'ASC',
    'NAME' => 'ASC',
);

$arSelect = array(
    'ID',
    'NAME',
    'CODE',
    'SECTION_PAGE_URL',
    'UF_CUT_NAME',
    'UF_NUMBER',
    'UF_SQUARE',
    'UF_SLIDER',
    'DESCRIPTION',
);

$rsSections = CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect);

// Определить текущий кластер
$currentCode = '';
if (preg_match('#^/clusters/([^/]+)/#', $APPLICATION->GetCurDir(), $matches)) {
    $currentCode = $matches[1];
}

$arResult['ITEMS'] = array();
$activeSet = false;
while ($arSection = $rsSections->GetNext()) {
    $isActive = ($arSection['CODE'] == $currentCode);
    if ($isActive) {
        $activeSet = true;
    }
    $arResult['ITEMS'][] = array(
        'ID' => $arSection['ID'],
        'NAME' => $arSection['UF_CUT_NAME'] ? $arSection['UF_CUT_NAME'] : $arSection['NAME'],
        'CODE' => $arSection['CODE'],
        'URL' => $arSection['SECTION_PAGE_URL'],
        'NUMBER' => $arSection['UF_NUMBER'],
        'UF_SQUARE' => $arSection['UF_SQUARE'],
        'UF_SLIDER' => $arSection['UF_SLIDER'],
        'DESCRIPTION' => $arSection['DESCRIPTION'],
        'ACTIVE' => $isActive,
    );
}
if (!$activeSet && !empty($arResult['ITEMS'])) {
    $arResult['ITEMS'][0]['ACTIVE'] = true;
}
?>

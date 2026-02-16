<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */

$filteredItems = $arResult["ITEMS"];

$search = trim($_GET['search'] ?? '');

// Фильтр по поиску
if (!empty($search)) {
    $filteredItems = array_filter($filteredItems, function ($item) use ($search) {
        $name = mb_strtolower($item['NAME'] ?? '');
        $previewText = mb_strtolower($item['PREVIEW_TEXT'] ?? '');
        $searchLower = mb_strtolower($search);
        return strpos($name, $searchLower) !== false || strpos($previewText, $searchLower) !== false;
    });
}

$arResult["ITEMS"] = array_values($filteredItems); // Переиндексируем массив
?>

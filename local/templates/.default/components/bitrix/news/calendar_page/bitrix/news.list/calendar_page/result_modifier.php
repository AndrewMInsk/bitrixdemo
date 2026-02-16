<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */

$filteredItems = $arResult["ITEMS"];

$search = trim($_GET['search'] ?? '');
$dateFrom = trim($_GET['date_from'] ?? '');
$dateTo = trim($_GET['date_to'] ?? '');

// Фильтр по поиску
if (!empty($search)) {
    $filteredItems = array_filter($filteredItems, function ($item) use ($search) {
        $name = mb_strtolower($item['NAME'] ?? '');
        $previewText = mb_strtolower($item['PREVIEW_TEXT'] ?? '');
        $searchLower = mb_strtolower($search);
        return strpos($name, $searchLower) !== false || strpos($previewText, $searchLower) !== false;
    });
}

// Фильтр по датам
if (!empty($dateFrom) || !empty($dateTo)) {
    $dateFromTimestamp = !empty($dateFrom) ? strtotime($dateFrom . ' 00:00:00') : null;
    $dateToTimestamp = !empty($dateTo) ? strtotime($dateTo . ' 23:59:59') : null;

    $filteredItems = array_filter($filteredItems, function ($item) use ($dateFromTimestamp, $dateToTimestamp) {
        $itemTimestamp = strtotime($item['DATE_CREATE'] ?? '');
        if ($itemTimestamp === false) {
            return true; // Если дата не парсится, оставляем элемент
        }
        $matchesFrom = $dateFromTimestamp === null || $itemTimestamp >= $dateFromTimestamp;
        $matchesTo = $dateToTimestamp === null || $itemTimestamp <= $dateToTimestamp;
        return $matchesFrom && $matchesTo;
    });
}

$arResult["ITEMS"] = array_values($filteredItems); // Переиндексируем массив
?>

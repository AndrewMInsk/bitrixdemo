<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Data\Cache;

class NewsListComponent extends CBitrixComponent
{
    protected $cacheTime = 36000000;

    public static function clearCache($ID, $arFields)
    {
        $cache = \Bitrix\Main\Data\Cache::createInstance();
        $cache->cleanDir('/news/list');
    }

    public function onPrepareComponentParams($arParams)
    {
        $arParams['IBLOCK_ID'] = intval($arParams['IBLOCK_ID']);

        return $arParams;
    }

    protected function checkModules()
    {
        if (!Loader::includeModule('iblock')) {
            ShowError('Модуль информационных блоков не установлен');
            return false;
        }
        return true;
    }

    public function getCacheID($additionalCacheID = false)
    {
        return md5(serialize($this->arParams) . $this->getTemplateName());
    }

    protected function getNewsList()
    {
        $cache = Cache::createInstance();
        $cacheID = $this->getCacheID();
        $result['CACHED'] = false;
        if ($cache->initCache($this->cacheTime, $cacheID, '/news/list')) {
            $result = $cache->getVars();
            $result['CACHED'] = true;
        } elseif ($cache->startDataCache()) {
            $result = array();

            $arSelect = array(
                'ID',
                'NAME',
                'PREVIEW_TEXT',
                'PREVIEW_PICTURE',
                'DATE_ACTIVE_FROM',
                'DETAIL_PAGE_URL',
                'IBLOCK_SECTION_ID',
                'CREATED_BY'
            );

            $arFilter = array(
                'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                'ACTIVE' => 'Y',
                'CHECK_PERMISSIONS' => 'Y'
            );

            // Добавляем фильтр из GET-параметров
            if (!empty($_GET['date_from'])) {
                $arFilter['>=DATE_ACTIVE_FROM'] = $_GET['date_from'];
            }
            if (!empty($_GET['date_to'])) {
                $arFilter['<=DATE_ACTIVE_FROM'] = $_GET['date_to'];
            }
            if (!empty($_GET['section_id'])) {
                $arFilter['SECTION_ID'] = $_GET['section_id'];
            }
            if (!empty($_GET['name'])) {
                $arFilter['NAME'] = '%' . $_GET['name'] . '%';
            }

            $res = CIBlockElement::GetList(
                array(
                    'ACTIVE_FROM' => 'DESC',
                    'SORT' => 'ASC'
                ),
                $arFilter,
                false,
                array(
                    'nPageSize' => 9,
                    'bShowAll' => false
                ),
                $arSelect
            );

            while ($ob = $res->GetNextElement()) {
                $arFields = $ob->GetFields();
                $arFields['PREVIEW_PICTURE'] = CFile::GetFileArray($arFields['PREVIEW_PICTURE']);
                $arFields['DATE_ACTIVE_FROM'] = ConvertDateTime($arFields['DATE_ACTIVE_FROM'], 'DD.MM.YYYY HH:MI:SS');
                $arFields['AUTHOR'] = CUser::GetByID($arFields['CREATED_BY'])->Fetch()['NAME'];
                $result['ITEMS'][] = $arFields;
            }

            $result['NAV_STRING'] = $res->GetPageNavStringEx($navComponentObject, 'Новости', '', false);

            $result['CACHED'] = false;
            $cache->endDataCache($result);
        }

        return $result;
    }

    public function executeComponent()
    {
        try {
            if (!$this->checkModules()) {
                return;
            }

            if (empty($this->arParams['IBLOCK_ID'])) {
                ShowError('Необходимо указать ID инфоблока');
                return;
            }

            $this->arResult = $this->getNewsList();

            if (empty($this->arResult['ITEMS'])) {
                $this->arResult['EMPTY'] = 'Y';
            }

            $this->includeComponentTemplate();
        } catch (Exception $e) {
            ShowError($e->getMessage());
        }
    }
}
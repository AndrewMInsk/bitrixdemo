<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Data\Cache;

class NewsListComponent extends CBitrixComponent
{
    public static function clearCache($ID, $arFields)
    {
        $iblockId = isset($arFields['IBLOCK_ID']) ? intval($arFields['IBLOCK_ID']) : 0;
        AddMessage2Log("ClearCache вызван", "news_cache_debug");

        if ($iblockId > 0) {
            $cacheTag = 'news_iblock_' . $iblockId;
            
            $taggedCache = \Bitrix\Main\Application::getInstance()->getTaggedCache();
            $taggedCache->clearByTag($cacheTag);
        }
    }

    public function onPrepareComponentParams($arParams)
    {
        $arParams['IBLOCK_ID'] = intval($arParams['IBLOCK_ID']);
        $arParams['CACHE_TIME'] = isset($arParams['CACHE_TIME']) ? intval($arParams['CACHE_TIME']) : 36000000;

        return $arParams;
    }



    public function getCacheID($additionalCacheID = false)
    {
        return md5(serialize($this->arParams) . $this->getTemplateName());
    }

    protected function getNewsList()
    {
        $cache = Cache::createInstance();
        $cacheID = $this->getCacheID();
        $cachePath = '/news/list';
        $cacheTag = 'news_iblock_' . $this->arParams['IBLOCK_ID'];
        
        $result['CACHED'] = false;
        if ($cache->initCache($this->arParams['CACHE_TIME'], $cacheID, $cachePath)) {
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
            
            $taggedCache = \Bitrix\Main\Application::getInstance()->getTaggedCache();
            $taggedCache->startTagCache($cachePath);
            $taggedCache->registerTag($cacheTag);
            $taggedCache->endTagCache();
            
            $cache->endDataCache($result);
        }

        return $result;
    }

    public function executeComponent()
    {
        try {


            if (empty($this->arParams['IBLOCK_ID'])) {
                ShowError('Необходимо указать ID инфоблока');
                return;
            }

            $this->arResult = $this->getNewsList();
            $this->includeComponentTemplate();
        } catch (Exception $e) {
            ShowError($e->getMessage());
        }
    }
}
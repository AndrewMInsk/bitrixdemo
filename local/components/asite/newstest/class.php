<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Data\Cache;

class NewsListComponent extends CBitrixComponent
{
    public static function clearCache($arFields)
    {
        $iblockId = isset($arFields['IBLOCK_ID']) ? intval($arFields['IBLOCK_ID']) : 0;
        AddMessage2Log("clearCache вызван");

        if ($iblockId > 0) {
            $cacheTag = 'news_iblock_' . $iblockId;
            
            $taggedCache = \Bitrix\Main\Application::getInstance()->getTaggedCache();
            $taggedCache->clearByTag($cacheTag);
        }
    }

    public function onPrepareComponentParams($arParams)
    {
        $arParams['IBLOCK_ID'] = intval($arParams['IBLOCK_ID']);
        $arParams['CACHE_TIME'] = isset($arParams['CACHE_TIME']) ? intval($arParams['CACHE_TIME']) : 3600;

        return $arParams;
    }



    public function getCacheID($additionalCacheID = false)
    {
        $additionalCacheID = serialize([
            'page' => $_GET['PAGEN_1'] ?? 1
        ]);
        return md5(serialize($this->arParams) . $this->getTemplateName() . $additionalCacheID);
    }

    protected function getSections()
    {
        $sections = [];
        $rsSections = CIBlockSection::GetList(
            ['SORT' => 'ASC', 'NAME' => 'ASC'],
            [
                'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                'DEPTH_LEVEL' => 1,
            ],
            false,
            ['ID', 'NAME']
        );
        
        while ($arSection = $rsSections->GetNext()) {
            $sections[$arSection['ID']] = $arSection['NAME'];
        }
        
        return $sections;
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
                'PROPERTY_AUTHOR'
            );

            $arFilter = array(
                'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                'ACTIVE' => 'Y',
                'CHECK_PERMISSIONS' => 'Y'
            );
            if (!empty($_GET['date_from'])) {
                $date = DateTime::createFromFormat('Y-m-d', $_GET['date_from']);
                if ($date) {
                    $arFilter['>=DATE_ACTIVE_FROM'] = $date->format('d.m.Y');
                }
            }
            if (!empty($_GET['date_to'])) {
                $date = DateTime::createFromFormat('Y-m-d', $_GET['date_to']);
                if ($date) {
                    $arFilter['<=DATE_ACTIVE_FROM'] = $date->format('d.m.Y');
                }
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
                ),
                $arFilter,
                false,
                array(
                    'nPageSize' => 4
                ),
                $arSelect
            );
            while ($ob = $res->GetNextElement()) {
                $arFields = $ob->GetFields();
                $arFields['PREVIEW_PICTURE'] = CFile::GetFileArray($arFields['PREVIEW_PICTURE']);
                $arFields['DATE_ACTIVE_FROM'] = ConvertDateTime($arFields['DATE_ACTIVE_FROM'], 'DD.MM.YYYY');
                $arFields['AUTHOR'] = $arFields['PROPERTY_AUTHOR_VALUE'] ?? 'Аноним';
                $result['ITEMS'][] = $arFields;
            }
            if (empty($result['ITEMS'])) {
                $cache->abortDataCache();
                throw new Exception('Новости не найдены');
            }
            $sections = $this->getSections();
            
            foreach ($result['ITEMS'] as &$item) {
                if (!empty($item['IBLOCK_SECTION_ID'])) {
                    $item['SECTION_NAME'] = $sections[$item['IBLOCK_SECTION_ID']] ?? '';
                }
            }

            $result['NAV_STRING'] = $res->GetPageNavStringEx($navComponentObject, 'Новости');

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
            $this->arResult['SECTIONS'] = $this->getSections();
            $this->includeComponentTemplate();
        } catch (Exception $e) {
            AddMessage2Log("Ошибочка " . $e->getMessage());

            ShowError($e->getMessage());
        }
    }
}
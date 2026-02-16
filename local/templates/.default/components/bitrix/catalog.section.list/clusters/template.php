<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<section class="infrastructure-page__clusters-list">
<?foreach($arResult["SECTIONS"] as $arSection):?>
    <?$cnt = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "SECTION_ID" => $arSection["ID"]), array(), false);?>
    <?if($cnt > 0):?>
    <div class="infrastructure-page__clusters-list-wrapper">
        <h2 class="infrastructure-page__cluster-title"><?=$arSection["NAME"]?></h2>
        <div class="infrastructure-page__cluster-items">
            <?$GLOBALS["hallFilter"] = array("SECTION_ID" => $arSection["ID"]);?>
            <?$APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "halls",
                Array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "NEWS_COUNT" => "100",
                    "SORT_BY1" => "SORT",
                    "SORT_ORDER1" => "ASC",
                    "SORT_BY2" => "NAME",
                    "SORT_ORDER2" => "ASC",
                    "FILTER_NAME" => "hallFilter",
                    "FIELD_CODE" => Array("ID", "NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_TEXT", "DETAIL_PICTURE"),
                    "PROPERTY_CODE" => Array("SLIDER", "PRICE_INCLUDED", "TECHNICAL", "PRICES"),
                    "CHECK_DATES" => "Y",
                    "DETAIL_URL" => "",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "AJAX_OPTION_HISTORY" => "N",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "36000000",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "Y",
                    "SET_TITLE" => "N",
                    "SET_BROWSER_TITLE" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                    "PARENT_SECTION" => "",
                    "PARENT_SECTION_CODE" => "",
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "DISPLAY_DATE" => "N",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => "N",
                    "DISPLAY_PREVIEW_TEXT" => "N",
                    "PREVIEW_TRUNCATE_LEN" => "",
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                    "USE_PERMISSIONS" => "N",
                    "GROUP_PERMISSIONS" => Array("1"),
                    "DISPLAY_TOP_PAGER" => "N",
                    "DISPLAY_BOTTOM_PAGER" => "N",
                    "PAGER_TITLE" => "Новости",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_TEMPLATE" => "",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "SET_STATUS_404" => "N",
                    "SHOW_404" => "N",
                    "MESSAGE_404" => "",
                ),
                $component
            );?>
        </div>
    </div>
    <?endif;?>
<?endforeach;?>
</section>

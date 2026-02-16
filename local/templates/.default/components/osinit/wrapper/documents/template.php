<? 
$GLOBALS['DOC_FILTER']=['SECTION_ID'=>getSections($arParams['IBLOCK_ID'],'education')];
$APPLICATION->IncludeComponent("bitrix:news.list", "documents", array(
	"IBLOCK_ID" => $arParams['IBLOCK_ID'],
	"NEWS_COUNT" => $arParams['NEWS_COUNT'],
	"SORT_BY1" => $arParams['SORT_BY1'],
	"SORT_ORDER1" => $arParams['SORT_ORDER1'],
	"CACHE_TYPE" => $arParams['CACHE_TYPE'],
	"CACHE_TIME" => $arParams['CACHE_TIME'],
	"PROPERTY_CODE" => $arParams['PROPERTY_CODE'],
	"FILTER_NAME"=>"DOC_FILTER"
)); ?>

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
$GLOBALS['news_pagination']->setRecordCount(count($arResult['ITEMS']));
$items = array_slice($arResult["ITEMS"], $GLOBALS['news_pagination']->getOffset(), $GLOBALS['news_pagination']->getLimit());
?>
	<?if (count($items)!=0){?>
<div class="news-content-gallery">
 <?php
            foreach ($items as $arItem):?>
      <div class="news-gallery-item">
        <div class="news-gallery-item__date">
           <?=$arItem["DISPLAY_ACTIVE_FROM"] ?: $arItem["DATE_CREATE"]?>
        </div>
        <div class="news-gallery-item__title">
			<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
				<?=$arItem["NAME"]?>
			</a>
		</div>
      </div>			
                <?php
            endforeach;
            ?>
        </div>
<?$APPLICATION->IncludeComponent('bitrix:main.pagenavigation', 'news_page', [
	'NAV_OBJECT' => $GLOBALS['news_pagination'],
	'SEF_MODE' => 'N', // включить ЧПУ
]);?>
	<?}?>

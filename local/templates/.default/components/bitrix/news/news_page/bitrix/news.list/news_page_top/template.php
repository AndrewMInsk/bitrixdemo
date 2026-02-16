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

	<?if (count($arResult['ITEMS'])!=0){?>
  <div class="news-content-main-news">
  <?foreach ($arResult["ITEMS"] as $arItem):?>
    <div class="main-news-card">
      <div class="main-news-card__image">
        <?php if ($arItem["PREVIEW_PICTURE"]["SRC"]): ?>
			<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>">
		<?php else: ?>
			<img src="<?=ASSETS_DIR?>/assets/images/news-placeholder.jpg" alt="">
		<?php endif; ?>
      </div>
      <div class="main-news-card__date">
         <?=$arItem["DISPLAY_ACTIVE_FROM"] ?: $arItem["DATE_CREATE"]?>
      </div>
      <div class="main-news-card__title">
        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
			<?=$arItem["NAME"]?>
		</a>
      </div>
    </div>
 <?endforeach;?>
</div> 
	<?}?>


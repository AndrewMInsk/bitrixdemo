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

<div class="news-page-content">
    <div class="news-page-image">
        <?php if ($arResult["DETAIL_PICTURE"]["SRC"]): ?>
            <img src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arResult["NAME"]?>">
        <?php elseif ($arResult["PREVIEW_PICTURE"]["SRC"]): ?>
            <img src="<?=$arResult["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arResult["NAME"]?>">
        <?php else: ?>
            <img src="/assets/images/default-news.jpg" alt="">
        <?php endif; ?>
    </div>
    <div class="news-page-text">
        <div class="news-page-title">
            <?=$arResult["NAME"]?>
        </div>
        <div class="news-page-date">
            <?=$arResult["DISPLAY_ACTIVE_FROM"] ?: $arResult["DATE_CREATE"]?>
        </div>
        <div class="news-page-text-content">
            <?=$arResult["DETAIL_TEXT"]?>
        </div>
    </div>
</div>

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
<div id="aboutPartnersCarouselSource" style="display:none;">
<?php if ($arResult["ITEMS"]): ?>
    <?php foreach ($arResult["ITEMS"] as $arItem): ?>
        <?php
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="partner-card" data-id="<?=$arItem['ID']?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <div class="partner-card__card-image">
                <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"] ?: $arItem["NAME"]?>" class="partner-card__img" />
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>


<div
        id="aboutPartnersCarousel" class="carousel carousel-bottom-buttons slide" data-bs-ride="carousel">
    <div class="carousel-inner"></div>

    <div class="carousel-controls">
        <div class="carousel-controls-inner">
            <button class="carousel-control-prev bottom-button" type="button" data-bs-target="#aboutPartnersCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Назад</span>
            </button>

            <div class="carousel-indicators-custom"></div>
            <button class="carousel-control-next bottom-button" type="button" data-bs-target="#aboutPartnersCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Вперед</span>
            </button>
        </div>
    </div>
</div>
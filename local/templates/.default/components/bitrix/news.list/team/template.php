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
<div class="team-grid">
    <div class="row g-4">
        <?php foreach ($arResult["ITEMS"] as $arItem): ?>
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <div class="team-card">
                    <div class="team-card__image">
                        <img
                                src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>"
                                alt="<?=$arItem['PREVIEW_PICTURE']['ALT'] ?: $arItem["NAME"]?>"
                                class="img-fluid"
                        />
                    </div>
                    <div class="team-card__content">
                        <h3 class="team-card__name"><?=$arItem['NAME']?></h3>
                        <p class="team-card__position"><?=$arItem['PROPERTIES']['DOLJNOST']['VALUE']?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

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
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

if (empty($arResult['ITEMS'])) return;
?>
<div id="gallery3" class="carousel carousel-bottom-buttons slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php foreach ($arResult['ITEMS'] as $index => $item): ?>
            <div class="carousel-item<?php if ($index === 0) echo ' active'; ?>">
                <div class="about-construction-carousel-card-large">
                    <div class="left-section">
                        <div class="left-section__title"><?= htmlspecialchars($item['NAME']) ?></div>
                        <div class="left-section__description">
                            <p><?= htmlspecialchars($item['PREVIEW_TEXT']) ?></p>
                        </div>
                    </div>
                    <div class="right-section">
                            <img src="<?= $item['PREVIEW_PICTURE']['SRC'] ?? '/assets/images/image-116.jpg' ?>" alt="<?= htmlspecialchars($item['NAME']) ?>" />
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="carousel-bottom-controls">
        <button class="carousel-control-prev bottom-button" type="button" data-bs-target="#gallery3" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Назад</span>
        </button>

        <div class="carousel-indicators ">
            <?php foreach ($arResult['ITEMS'] as $index => $item): ?>
                <button type="button" data-bs-target="#gallery3" data-bs-slide-to="<?= $index ?>" <?php if ($index === 0) echo 'class="active" aria-current="true"'; ?> aria-label="Slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-next bottom-button" type="button" data-bs-target="#gallery3" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Вперед</span>
        </button>
    </div>
</div>

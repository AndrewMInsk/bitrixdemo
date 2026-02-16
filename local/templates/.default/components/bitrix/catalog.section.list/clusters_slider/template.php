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

if (empty($arResult['SECTIONS'])) return;

$slides = $arResult['SECTIONS'];
?>

<div id="homeClustersSource" style="display:none;">

    <?php foreach ($slides as $index => $slide): ?>
<?
            $image = $slide['UF_SLIDER'] ?? $slide['PICTURE'];
            if ($image) {
                $resizedImage = CFile::ResizeImageGet($image, array("width" => 500), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                $imagePath = $resizedImage['src'];
            } else {
                $imagePath = '';
            }
            ?>

        <div class="cluster-card" data-id="<?=$slide['ID']?>">
            <div class="cluster-card__image">
                <?if ($imagePath):?>
                    <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($slide['NAME']) ?>" class="" />
                <?else:?>
                    <img src="<?=ASSETS_DIR?>/assets/images/image-91.jpg" alt="<?= htmlspecialchars($slide['NAME']) ?>" class="" />
                <?endif;?>
            </div>
            <div class="cluster-card__content">
                <h3 class="cluster-card__title"><?= htmlspecialchars($slide['NAME']) ?></h3>
                <p class="cluster-card__description"><?= htmlspecialchars($slide['UF_INTROTEXTER']) ?></p>
                <a href="<?= $slide['SECTION_PAGE_URL'] ?>" class="btn btn-light cluster-card__button">Подробнее</a>
            </div>
        </div>
    <?php endforeach; ?>




</div>
<div id="homeClustersCarousel" class="carousel carousel-bottom-buttons slide" data-bs-ride="carousel">

<div class="carousel-inner"></div>
    <div class="carousel-controls">
        <div class="carousel-controls-inner">
            <button class="carousel-control-prev bottom-button" type="button" data-bs-target="#homeClustersCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Назад</span>
            </button>

            <div class="carousel-indicators-custom"></div>
            <button class="carousel-control-next bottom-button" type="button" data-bs-target="#homeClustersCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Вперед</span>
            </button>
        </div>
    </div>
</div>
<!-- Навигация и индикаторы -->

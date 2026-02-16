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

$slides = array_chunk($arResult['SECTIONS'], 3);
?>
<div id="clustersCarousel" class="carousel slide" data-bs-ride="carousel">

<div class="carousel-inner">
    <?php foreach ($slides as $index => $slide): ?>
        <div class="carousel-item<?= $index === 0 ? ' active' : '' ?>">
            <div class="row g-4">
                <?php foreach ($slide as $item):
                    $image = $item['PICTURE'] ?? $item['DETAIL_PICTURE'];
                    if ($image) {
                        $resizedImage = CFile::ResizeImageGet($image, array("width" => 500), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                        $imagePath = $resizedImage['src'];
                    } else {
                        $imagePath = '';
                    }
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="cluster-card" data-id="<?= $item['ID'] ?>">
                            <div class="cluster-card__image">
                                <?if ($imagePath):?>
                                    <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($item['NAME']) ?>" class="cluster-card__img" />
                                <?else:?>
                                    <img src="<?=ASSETS_DIR?>/assets/images/image-91.jpg" alt="<?= htmlspecialchars($item['NAME']) ?>" class="cluster-card__img" />
                                <?endif;?>
                            </div>
                            <div class="cluster-card__content">
                                <h3 class="cluster-card__title"><?= htmlspecialchars($item['NAME']) ?></h3>
                                <p class="cluster-card__description"><?= htmlspecialchars($item['UF_INTROTEXTER']) ?></p>
                                <a href="<?= $item['SECTION_PAGE_URL'] ?>" class="btn btn-light cluster-card__button">Подробнее</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</div>
<!-- Навигация и индикаторы -->
<div class="carousel-navigation mt-4">
    <div class="d-flex align-items-center justify-content-center gap-3">
        <!-- Кнопка назад -->
        <button
            class="carousel-control-prev btn-carousel"
            type="button"
            data-bs-target="#clustersCarousel"
            data-bs-slide="prev"
        >
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Предыдущий</span>
        </button>

        <!-- Индикаторы -->
        <div class="carousel-indicators-custom">
            <?php foreach ($slides as $i => $slide): ?>
                <button
                    type="button"
                    data-bs-target="#clustersCarousel"
                    data-bs-slide-to="<?= $i ?>"
                    <?= $i === 0 ? 'class="active" aria-current="true"' : '' ?>
                    aria-label="Slide <?= $i + 1 ?>"
                ></button>
            <?php endforeach; ?>
        </div>

        <!-- Кнопка вперед -->
        <button
            class="carousel-control-next btn-carousel"
            type="button"
            data-bs-target="#clustersCarousel"
            data-bs-slide="next"
        >
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Следующий</span>
        </button>
    </div>
</div>

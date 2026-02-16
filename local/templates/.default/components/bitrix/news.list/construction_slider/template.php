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

if (empty($arResult['ITEMS'])) return;

$slides = array_chunk($arResult['ITEMS'], 3);
$clusterName = htmlspecialchars($arParams['CLUSTER_NAME'] ?? '');
$carouselId = 'aboutConstructionCarousel' . ($arParams['CLUSTER_ID'] ?? '0');
?>
<div class="about-construction-section">
    <div class="about-construction-section__title"><?=$clusterName?></div>
            <div id="galleryEducationalCluster<?=$carouselId?>" class="carousel carousel-bottom-buttons slide" data-bs-ride="carousel">


            <div class="carousel-inner">
                <?php foreach ($slides as $index => $slide): ?>
                    <div class="carousel-item<?= $index === 0 ? ' active' : '' ?>">
                        <div class="construction-card-list">
                            <?php foreach ($slide as $item):
                                $image = $item['PREVIEW_PICTURE'];
                                if ($image) {
                                    $resizedImage = CFile::ResizeImageGet($image['ID'], array("width" => 400), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                                    $imagePath = $resizedImage['src'];
                                } else {
                                    $imagePath = ASSETS_DIR . '/assets/images/image-91.jpg';
                                }
                                ?>
                                    <div class="construction-card" data-id="<?= $item['ID'] ?>">
                                        <div class="construction-card__image">
                                            <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($item['NAME']) ?>" />
                                        </div>
                                        <div class="construction-card__title"><?= htmlspecialchars($item['NAME']) ?></div>
                                        <div class="construction-card__description">
                                            <?= htmlspecialchars($item['PREVIEW_TEXT']) ?>
                                        </div>
                                    </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>


        <!-- Навигация и индикаторы -->
        <?php if (count($slides) > 1): ?>

            <div class="carousel-bottom-controls">
                <button class="carousel-control-prev bottom-button" type="button" data-bs-target="#galleryEducationalCluster<?=$carouselId?>" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Назад</span>
                </button>


                <div class="carousel-indicators">

                    <?php foreach ($slides as $i => $slide): ?>
                        <button
                                type="button"
                                data-bs-target="#galleryEducationalCluster<?=$carouselId?>"
                                data-bs-slide-to="<?= $i ?>"
                            <?= $i === 0 ? 'class="active" aria-current="true"' : '' ?>
                                aria-label="Slide <?= $i + 1 ?>"
                        ></button>
                    <?php endforeach; ?>

                </div>

                <button class="carousel-control-next bottom-button" type="button" data-bs-target="#galleryEducationalCluster<?=$carouselId?>" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Вперед</span>
                </button>
            </div>


        <?php endif; ?>
        </div>
    </div>


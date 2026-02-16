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

$slides = array_chunk($arResult['ITEMS'], 8);
?>
<div id="residentsCarousel" class="carousel slide" data-bs-ride="carousel">
<div class="carousel-inner">
    <?php foreach ($slides as $index => $slide): ?>
        <div class="carousel-item<?= $index === 0 ? ' active' : '' ?>">
            <div class="row g-3">
                <?php foreach ($slide as $item):
                    $image = $item['PROPERTIES']['IMAGE']['VALUE'] ?? '';
                    if ($image) {
                        $resizedImage = CFile::ResizeImageGet($image, array("width" => 500), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                        $imagePath = $resizedImage['src'];
                    } else {
                        $imagePath = '';
                    }
                    ?>
                    <div class="col-3">
                        <div class="resident-card" data-id="<?= $item['ID'] ?>">
                            <?if ($imagePath):?>
                                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($item['NAME']) ?>" class="resident-card__img" />
                            <?else:?>
                                <?= htmlspecialchars($item['NAME']) ?>
                            <?endif;?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</div>
<div class="carousel-navigation mt-4">
    <div class="d-flex align-items-center justify-content-center gap-3">
        <button class="carousel-control-prev btn-carousel" type="button" data-bs-target="#residentsCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Предыдущий</span>
        </button>

        <div class="carousel-indicators-custom">
            <?php foreach ($slides as $i => $slide): ?>
                <button type="button" data-bs-target="#residentsCarousel"
                        data-bs-slide-to="<?= $i ?>"<?= $i === 0 ? ' class="active" aria-current="true"' : '' ?> aria-label="Slide <?= $i + 1 ?>"></button>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-next btn-carousel" type="button" data-bs-target="#residentsCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Следующий</span>
        </button>
    </div>
</div>

<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

$activeItem = null;
foreach ($arResult['ITEMS'] as $item) {
    if ($item['ACTIVE']) {
        $activeItem = $item;
        break;
    }
}
?>
<div class="cluster-select-block">
    <div class="left-section">
        <h1 class="cluster-select-block__title">9 кластеров долины МГУ</h1>
        <div class="cluster-select-list">
            <?php


            $count = 0;
            foreach ($arResult['ITEMS'] as $item):
                $count++;
                ?>
                <a href="<?= $item['URL'] ?>" class="cluster-select-list__item<?= $item['ACTIVE'] ? ' active' : '' ?>">
                    <div class="cluster-select-list__item-icon<?= $item['NUMBER'] == 1777 ? ' cluster-select-list__item-icon_red' : '' ?>"><?= $item['NUMBER'] ?></div>
                    <div class="cluster-select-list__item-title"><?= htmlspecialchars($item['NAME']) ?></div>
                </a>
                <?php if ($count % 3 == 0): ?>
                <br />
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php if ($activeItem['ID'] == 14 || $activeItem['ID'] == 6): ?>
            <a class="btn <?= $activeItem['ID'] == 14777 ? 'btn-danger' : 'btn-primary' ?>" href="/resident/become/?activeItem=<?=$activeItem['ID']?>">Стать резидентом</a>
        <?php else: ?>
            <div class="cluster-button-block">
                <a class="btn btn-primary disabled" disabled="" href="/resident/become/">Стать резидентом</a>
                <div class="cluster-info-badge">В стадии строительства</div>
            </div>
        <?php endif; ?>
    </div>
    <div class="right-section">
        <div class="map-interactive-block">
            <div class="map-interactive-block__content">
                <img id="map-interactive-image" src="<?=ASSETS_DIR?>/assets/images/map.png" alt="map" />
                <?php foreach ($arResult['ITEMS'] as $item): ?>
                    <?php $isActive = $item['ACTIVE']; ?>
                    <div class="map-interactive-point<?= $isActive ? ' active' : '' ?>" id="map-interactive-point-<?= $item['NUMBER'] ?>">
                        <a href="<?= $item['URL'] ?>">
                            <?php if ($isActive): ?>
                                <img src="<?=ASSETS_DIR?>/assets/images/icons/map-bubble-<?= $item['NUMBER'] ?>.svg" alt="" />
                            <?php else: ?>
                                <?= $item['NUMBER'] ?>
                            <?php endif; ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php
// Вывод блока cluster-about для активного кластера

if ($activeItem):
    ?>
    <section class="page-tabs">
        <nav class="page-tabs__navigation">
            <div class="nav page-tabs__nav">
                <button class="nav-link page-tabs__nav-link no-link active">
                    О кластере
                </button>
                <?php if ($activeItem['ID'] == 6 || $activeItem['ID'] == 14): ?>
                    <button class="nav-link page-tabs__nav-link no-link">
                        Конгрессная инфраструктура
                    </button>
                <?php else: ?>
                    <button class="nav-link page-tabs__nav-link no-link disabled" disabled="">
                        Конгрессная инфраструктура
                    </button>
                <?php endif; ?>
            </div>
        </nav>
    </section>

    <div class="cluster-about-tab-content tab-content">

        <div class="cluster-about">
            <div class="cluster-about__left-section">
                <div class="cluster-name-wrapper">

                    <div class="cluster-name">
                        <h2 class="cluster-name__title">Кластер «<?= htmlspecialchars($activeItem['NAME']) ?>»</h2>
                        <div class="cluster-name__area-badge"><?= htmlspecialchars($activeItem['UF_SQUARE']) ?> тыс. м2</div>
                    </div>
                </div>
                <div class="cluster-info">
                    <p class="cluster-info__text">
                        <?= $activeItem['DESCRIPTION']?>
                    </p>
                </div>
            </div>
            <div class="cluster-about__right-section">
                <?php if ($activeItem['UF_SLIDER'] && is_array($activeItem['UF_SLIDER']) && count($activeItem['UF_SLIDER']) > 0): ?>
                    <div id="gallery3" class="carousel carousel-bottom-buttons slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($activeItem['UF_SLIDER'] as $index => $fileId): ?>
                                <?php if (intval($fileId) > 0): ?>
                                    <div class="carousel-item<?= $index == 0 ? ' active' : '' ?>">
                                        <div class="carousel-image">
                                            <img src="<?= CFile::GetPath($fileId) ?>" alt="">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="carousel-bottom-controls">
                            <button class="carousel-control-prev bottom-button" type="button" data-bs-target="#gallery3" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Назад</span>
                            </button>
                            <div class="carousel-indicators">
                                <?php foreach ($activeItem['UF_SLIDER'] as $index => $fileId): ?>
                                    <?php if (intval($fileId) > 0): ?>
                                        <button type="button" data-bs-target="#gallery3" data-bs-slide-to="<?= $index ?>"<?= $index == 0 ? ' class="active" aria-current="true"' : '' ?> aria-label="Slide <?= $index + 1 ?>"></button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <button class="carousel-control-next bottom-button" type="button" data-bs-target="#gallery3" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Вперед</span>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>


            </div>
        </div>
    </div>

    <?php
// Получить залы активного кластера
    if ($activeItem) {
        $halls = [];
        $res = CIBlockElement::GetList(
            ['SORT' => 'ASC'],
            ['IBLOCK_ID' => 5, 'SECTION_ID' => $activeItem['ID'], 'ACTIVE' => 'Y'],
            false,
            false
        );
        while ($hall = $res->GetNextElement()) {
            $fields = $hall->GetFields();
            $props = $hall->GetProperties();
            $halls[] = array_merge($fields, $props);
        }
        // Удаление дублей
        $uniqueHalls = [];
        foreach ($halls as $hall) {
            $uniqueHalls[$hall['ID']] = $hall;
        }
        $halls = array_values($uniqueHalls);

        if (!empty($halls)):
            ?>
            <div class="cluster-infrastructure-tab-content tab-content hidden">
                <div class="cluster-about-list">
                    <?php foreach ($halls as $index => $hall): ?>
                        <div class="cluster-about">
                            <div class="cluster-about__left-section">
                                <?php if (!empty($hall['SLIDER']['VALUE']) && is_array($hall['SLIDER']['VALUE'])): ?>
                                    <div id="gallery-<?= $index + 1 ?>" class="carousel carousel-side-buttons slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <?php foreach ($hall['SLIDER']['VALUE'] as $slideIndex => $fileId): ?>
                                                <div class="carousel-item<?= $slideIndex == 0 ? ' active' : '' ?>">
                                                    <div class="carousel-image">
                                                        <?php
                                                        $file = CFile::GetFileArray($fileId);
                                                        $src = CFile::GetPath($fileId);
                                                        if ($file && $file['WIDTH'] > 0) {
                                                            $newWidth = 800;
                                                            $newHeight = round($file['HEIGHT'] * $newWidth / $file['WIDTH']);
                                                            $resize = CFile::ResizeImageGet($fileId, array("width" => $newWidth, "height" => $newHeight), BX_RESIZE_IMAGE_EXACT, true);
                                                            $src = $resize['src'];
                                                        }
                                                        ?>
                                                        <img src="<?= $src ?>" alt="">
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="carousel-indicators">
                                            <?php foreach ($hall['SLIDER']['VALUE'] as $slideIndex => $fileId): ?>
                                                <button type="button" data-bs-target="#gallery-<?= $index + 1 ?>" data-bs-slide-to="<?= $slideIndex ?>"<?= $slideIndex == 0 ? ' class="active" aria-current="true"' : '' ?> aria-label="Slide <?= $slideIndex + 1 ?>"></button>
                                            <?php endforeach; ?>
                                        </div>
                                        <button class="carousel-control-prev side-button" type="button" data-bs-target="#gallery-<?= $index + 1 ?>" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Назад</span>
                                        </button>
                                        <button class="carousel-control-next side-button" type="button" data-bs-target="#gallery-<?= $index + 1 ?>" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Вперед</span>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="cluster-about__right-section">
                                <div class="cluster-about__right-section-container">
                                    <div class="congress-hall-info-wrapper">
                                        <div class="congress-hall-info">
                                            <h2 class="cluster-name__title"><?= htmlspecialchars($hall['NAME']) ?></h2>
                                            <?if (@intval($hall['PLACES']['VALUE'])!=0){?><div class="cluster-name__area-badge"><?= htmlspecialchars($hall['PLACES']['VALUE']) ?> мест</div><?}?>
                                        </div>
                                        <?php if (!empty($hall['PRICE_INCLUDED']['VALUE']) && is_array($hall['PRICE_INCLUDED']['VALUE'])): ?>
                                            <div class="congress-hall-info-details">
                                                <? if ($activeItem['ID'] != '6'): ?>
                                                    <div class="congress-hall-info-details__title">Стоимость включает в
                                                        себя
                                                    </div>
                                                <? endif; ?>
                                                <ul class="congress-hall-details-list">
                                                    <?php foreach ($hall['PRICE_INCLUDED']['~VALUE']  as $item): ?>
                                                        <li><?= htmlspecialchars($item['TEXT']) ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($hall['TECHNICAL']['VALUE']) && is_array($hall['TECHNICAL']['VALUE'])): ?>
                                            <div class="congress-hall-info-details">
                                                <div class="congress-hall-info-details__title-small">Техническое оборудование зала:</div>
                                                <ul class="congress-hall-details-list">
                                                    <?php foreach ($hall['TECHNICAL']['~VALUE']  as $item): ?>
                                                        <li><?= htmlspecialchars($item['TEXT']) ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="congress-hall-order-wrapper">
                                        <? if ($activeItem['ID'] != '6'): ?>
                                            <a class="btn btn-primary cluster-order-btn"
                                               href="/reserve/?cluster-id=<?= $activeItem['ID'] ?>&congress-hall-id=<?= $hall['ID'] ?>">
                                                Забронировать
                                            </a>
                                        <? else: ?>
                                            <a class="btn btn-primary cluster-order-btn"
                                               href="https://i.moscow/intc_kongress">
                                                Забронировать
                                            </a>
                                        <? endif; ?>
                                        <?php if (!empty($hall['PRICES']['VALUE']) && is_array($hall['PRICES']['VALUE'])): ?>
                                            <? if ($activeItem['ID'] != '6'): ?>
                                                <?php foreach ($hall['PRICES']['~VALUE'] as $price): ?>
                                                    <?php $parts = explode('-', $price['TEXT']); ?>
                                                    <div class="congress-hall-info-badge">
                                                        <h2 class="congress-hall-info-badge__title"><?= $parts[0] ?></h2>
                                                        <div class="congress-hall-info-badge__description"><?= $parts[1] ?? '' ?></div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                    array("AREA_FILE_SHOW" => "file", "PATH" => "/include/cluster_services_list.php"));?>

                <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                    array("AREA_FILE_SHOW" => "file", "PATH" => "/include/cluster_additional_services.php"));?>

                <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                    array("AREA_FILE_SHOW" => "file", "PATH" => "/include/cluster_discounts.php"));?>


                <?
                $GLOBALS['DOC_FILTER']=['SECTION_ID'=>getSections(11,'clusters')];
                $APPLICATION->IncludeComponent("bitrix:news.list", "documents", array(
                    "IBLOCK_ID" => 11,
                    "NEWS_COUNT" => "120",
                    "SORT_BY1" => "SORT",
                    "SORT_ORDER1" => "ASC",
                    "CACHE_TYPE" => "Y",
                    "CACHE_TIME" => 3600,
                    "PROPERTY_CODE" => array("FILE"),
                    "FILTER_NAME"=>"DOC_FILTER"
                )); ?>

            </div>
        <?php
        endif;
    }
endif;
?>

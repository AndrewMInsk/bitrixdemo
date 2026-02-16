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
    <div class="news-filter">
        <div class="news-filter__search-input">
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-white border-0 px-4">
                  <img src="<?=ASSETS_DIR?>/assets/images/icons/search-icon.svg" alt=""/>
                </span>
                <input type="search" name="search" id="news-search" class="form-control border-0" placeholder="Поиск по тексту"
                       value="<?=$_GET['search']?>"
                       autocomplete="off"
                />
            </div>
        </div>
        <div class="news-date-range">
            <div class="date-range-picker">

                <div class="date-range-input form-control" data-range-trigger>
                    <span data-range-placeholder>Выберите период</span>
                    <i class="bi bi-calendar"></i>
                </div>

                <div class="date-range-panel" data-range-panel>
                    <div class="date-range-fields">
                        <div>
                            <label class="form-label small">От</label>
                            <input type="date" name="date_from" class="form-control" data-range-from value="<?=$_GET['date_from']?>">
                        </div>
                        <div>
                            <label class="form-label small">До</label>
                            <input type="date" name="date_to" class="form-control" data-range-to value="<?=$_GET['date_to']?>">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-light" data-range-apply>
                            Подтвердить
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="news-filter-controls">
        <button class="btn btn-primary" data-filters-apply>
            Применить
        </button>
        <button class="btn btn-light" data-filters-reset>
            Сбросить фильтры
        </button>
    </div>

    <div class="news-content">
        <div class="news-content-main-news">
            <?php
            $mainNewsCount = 0;
            foreach ($arResult["ITEMS"] as $arItem):
                if ($mainNewsCount >= 3) break;
                ?>
                <div class="main-news-card">
                    <div class="main-news-card__image">
                        <?php if ($arItem["PREVIEW_PICTURE"]["SRC"]): ?>
                            <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>">
                        <?php else: ?>
                            <img src="<?=ASSETS_DIR?>/assets/images/default-news.jpg" alt="">
                        <?php endif; ?>
                    </div>
                    <div class="activity-card__header">
                        <div class="activity-card__date">
                            <div class="activity-card__day-number"><?=$arItem["PROPERTIES"]["DATE_START"]["VALUE"] ? date("d", strtotime($arItem["PROPERTIES"]["DATE_START"]["VALUE"])) : ''?></div>
                            <div class="activity-card__date-info">
                                <div class="activity-card__month"><?=$arItem["PROPERTIES"]["DATE_START"]["VALUE"] ? date("F", strtotime($arItem["PROPERTIES"]["DATE_START"]["VALUE"])) : ''?></div>
                                <div class="activity-card__weekday"><?=$arItem["PROPERTIES"]["DATE_START"]["VALUE"] ? date("l", strtotime($arItem["PROPERTIES"]["DATE_START"]["VALUE"])) : ''?></div>
                            </div>
                        </div>
                        <div class="activity-card__time-info">
                            <div class="activity-card__time"><?=$arItem["PROPERTIES"]["DATE_START"]["VALUE"] ? date("H:i", strtotime($arItem["PROPERTIES"]["DATE_START"]["VALUE"])) : ''?></div>
                            <div class="activity-card__format">онлайн</div>
                        </div>
                    </div>
                    <div class="activity-card__title">
                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                            <?=$arItem["NAME"]?>
                        </a>
                        <div class="activity-card__organizer">Организатор: <?=$arItem["PROPERTIES"]["ORGANIZER"]["VALUE"]?></div>
                        <div class="activity-card__place">Место: <?=$arItem["PROPERTIES"]["MESTO"]["VALUE"]?></div>
                    </div>
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="btn btn-primary activity-card__btn">Подробнее</a>
                </div>
                <?php
                $mainNewsCount++;
            endforeach;
            ?>
        </div>

        <!-- Десктоп версия: 2 слайда по 4 карточки (2×2) -->
        <div class="activities-desktop d-none d-md-block">
            <div id="gallery-activities-desktop" class="carousel carousel-bottom-buttons slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $carouselItems = array_slice($arResult["ITEMS"], 3); // Начиная с 4-го элемента
                    $chunks = array_chunk($carouselItems, 4);
                    $active = true;
                    foreach ($chunks as $chunk):
                        ?>
                        <div class="carousel-item <?php if ($active) echo 'active'; $active = false; ?>">
                            <div class="activities-list">
                                <?php foreach ($chunk as $arItem): ?>
                                    <div class="activity-card">
                                        <div class="activity-card__header">
                                            <div class="activity-card__date">
                                                <div class="activity-card__day-number"><?=$arItem["PROPERTIES"]["DATE_START"]["VALUE"] ? date("d", strtotime($arItem["PROPERTIES"]["DATE_START"]["VALUE"])) : ''?></div>
                                                <div class="activity-card__date-info">
                                                    <div class="activity-card__month"><?=$arItem["PROPERTIES"]["DATE_START"]["VALUE"] ? date("F", strtotime($arItem["PROPERTIES"]["DATE_START"]["VALUE"])) : ''?></div>
                                                    <div class="activity-card__weekday"><?=$arItem["PROPERTIES"]["DATE_START"]["VALUE"] ? date("l", strtotime($arItem["PROPERTIES"]["DATE_START"]["VALUE"])) : ''?></div>
                                                </div>
                                            </div>
                                            <div class="activity-card__time-info">
                                                <div class="activity-card__time"><?=$arItem["PROPERTIES"]["DATE_START"]["VALUE"] ? date("H:i", strtotime($arItem["PROPERTIES"]["DATE_START"]["VALUE"])) : ''?></div>
                                                <div class="activity-card__format">офлайн</div>
                                            </div>
                                        </div>
                                        <div class="activity-card__title">
                                            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                                                <?=$arItem["NAME"]?>
                                            </a>
                                            <div class="activity-card__organizer">Организатор: <?=$arItem["PROPERTIES"]["ORGANIZER"]["VALUE"]?></div>
                                            <div class="activity-card__place">Место: <?=$arItem["PROPERTIES"]["MESTO"]["VALUE"]?></div>
                                        </div>
                                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="btn btn-primary activity-card__btn">Подробнее</a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="carousel-bottom-controls">
                    <button class="carousel-control-prev bottom-button" type="button" data-bs-target="#gallery-activities-desktop" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Назад</span>
                    </button>

                    <div class="carousel-indicators">
                        <?php for ($i = 0; $i < count($chunks); $i++): ?>
                            <button type="button" data-bs-target="#gallery-activities-desktop" data-bs-slide-to="<?=$i?>"
                                    class="<?php if ($i == 0) echo 'active'; ?>" aria-current="<?php if ($i == 0) echo 'true'; ?>"
                                    aria-label="Slide <?=$i+1?>"></button>
                        <?php endfor; ?>
                    </div>

                    <button class="carousel-control-next bottom-button" type="button" data-bs-target="#gallery-activities-desktop" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Вперед</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- end-of Секция - карусель - news desktop -->

        <!-- Мобильная версия: 8 слайдов по 1 карточке -->
        <div class="activities-mobile d-block d-md-none">
            <div id="gallery-activities-mobile" class="carousel carousel-bottom-buttons slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $mobileChunks = array_chunk($carouselItems, 1); // Для мобильной по 1
                    $active = true;
                    foreach ($mobileChunks as $chunk):
                        ?>
                        <div class="carousel-item <?php if ($active) echo 'active'; $active = false; ?>">
                            <div class="activities-list">
                                <?php foreach ($chunk as $arItem): ?>
                                    <div class="activity-card">
                                        <div class="activity-card__header">
                                            <div class="activity-card__date">
                                                <div class="activity-card__day-number"><?=$arItem["PROPERTIES"]["DATE_START"]["VALUE"] ? date("d", strtotime($arItem["PROPERTIES"]["DATE_START"]["VALUE"])) : ''?></div>
                                                <div class="activity-card__date-info">
                                                    <div class="activity-card__month"><?=$arItem["PROPERTIES"]["DATE_START"]["VALUE"] ? date("F", strtotime($arItem["PROPERTIES"]["DATE_START"]["VALUE"])) : ''?></div>
                                                    <div class="activity-card__weekday"><?=$arItem["PROPERTIES"]["DATE_START"]["VALUE"] ? date("l", strtotime($arItem["PROPERTIES"]["DATE_START"]["VALUE"])) : ''?></div>
                                                </div>
                                            </div>
                                            <div class="activity-card__time-info">
                                                <div class="activity-card__time"><?=$arItem["PROPERTIES"]["DATE_START"]["VALUE"] ? date("H:i", strtotime($arItem["PROPERTIES"]["DATE_START"]["VALUE"])) : ''?></div>
                                                <div class="activity-card__format">онлайн</div>
                                            </div>
                                        </div>
                                        <div class="activity-card__title">
                                            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                                                <?=$arItem["NAME"]?>
                                            </a>
                                            <div class="activity-card__organizer">Организатор: <?=$arItem["PROPERTIES"]["ORGANIZER"]["VALUE"]?></div>
                                            <div class="activity-card__place">Место: <?=$arItem["PROPERTIES"]["MESTO"]["VALUE"]?></div>
                                        </div>
                                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="btn btn-primary activity-card__btn">Подробнее</a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="carousel-bottom-controls">
                    <button class="carousel-control-prev bottom-button" type="button" data-bs-target="#gallery-activities-mobile" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Назад</span>
                    </button>

                    <div class="carousel-indicators">
                        <?php for ($i = 0; $i < count($mobileChunks); $i++): ?>
                            <button type="button" data-bs-target="#gallery-activities-mobile" data-bs-slide-to="<?=$i?>"
                                    class="<?php if ($i == 0) echo 'active'; ?>" aria-current="<?php if ($i == 0) echo 'true'; ?>"
                                    aria-label="Slide <?=$i+1?>"></button>
                        <?php endfor; ?>
                    </div>

                    <button class="carousel-control-next bottom-button" type="button" data-bs-target="#gallery-activities-mobile" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Вперед</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- end-of Секция - карусель - news mobile -->
    </div>

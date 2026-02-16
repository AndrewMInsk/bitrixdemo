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

// Фильтрация элементов
$filteredItems = $arResult["ITEMS"];
if (!empty($_GET['activity_format'])) {
    $filteredItems = array_filter($filteredItems, function($item) {
        $format = $item["PROPERTIES"]["TYPE_ONLINE"]["VALUE"];
        return $format == $_GET['activity_format'];
    });
}
if (!empty($_GET['activity_type'])) {
    $filteredItems = array_filter($filteredItems, function($item) {
        $type = $item["PROPERTIES"]["VID_MEROPR"]["VALUE"];
        return $type == $_GET['activity_type'];
    });
}
if (!empty($_GET['date_from'])) {
    $filteredItems = array_filter($filteredItems, function($item) {
        $date = strtotime($item["PROPERTIES"]["DATE_START"]["VALUE"]);
        return $date >= strtotime($_GET['date_from']);
    });
}
if (!empty($_GET['date_to'])) {
    $filteredItems = array_filter($filteredItems, function($item) {
        $date = strtotime($item["PROPERTIES"]["DATE_START"]["VALUE"]);
        return $date <= strtotime($_GET['date_to'] . ' 23:59:59');
    });
}
if (!empty($_GET['search'])) {
    $search = trim($_GET['search']);
    $filteredItems = array_filter($filteredItems, function($item) use ($search) {
        return stripos($item["NAME"], $search) !== false || stripos($item["DETAIL_TEXT"], $search) !== false;
    });
}
$arResult["ITEMS"] = $filteredItems
?>
<div class="news-filter">
  <div class="news-filter__search-input">
    <div class="input-group input-group-lg">
                <span class="input-group-text bg-white border-0 px-4">
                  <img src="<?=ASSETS_DIR?>/assets/images/icons/search-icon.svg" alt=""/>
                </span>
      <input type="search" id="news-search" class="form-control border-0" placeholder="Поиск по тексту"
        autocomplete="off"
      />
    </div>
  </div>
</div>


<div class="news-calendar-filters">
  <div class="news-calendar-filters__select-wrapper">

  <div class="mb-3">

    <select id="activity_format" name="activity_format" class="form-select news-calendar-filters__select"  required  >

        <option value="" >
          Выберите формат
        </option>

        <option value="Онлайн" >
          онлайн
        </option>

        <option value="Оффлайн" >
          оффлайн
        </option>

    </select>
  </div>

  </div>
  <div class="news-calendar-filters__select-wrapper">

  <div class="mb-3">

    <select id="activity_type" name="activity_type" class="form-select news-calendar-filters__select"  required  >

        <option value="" >
          Выберите тип
        </option>

        <option value="Выставка" >
          Выставка
        </option>

        <option value="Вебинар" >
          Вебинар
        </option>

        <option value="Мероприятие" >
          Мероприятие
        </option>

        <option value="Встреча" >
          Встреча
        </option>

    </select>
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
        <input type="date" class="form-control" data-range-from>
      </div>
      <div>
        <label class="form-label small">До</label>
        <input type="date" class="form-control" data-range-to>
      </div>
    </div>

    <div class="d-flex justify-content-end mt-3">
      <button class="btn btn-primary" data-range-apply>
        Подтвердить
      </button>
    </div>
  </div>
</div>

  </div>
</div>

<div class="news-filter-controls">
  <button class="btn btn-primary" data-events-filters-apply>
    Применить
  </button>
  <button class="btn btn-light" data-events-filters-reset>
    Сбросить фильтры
  </button>
</div>

    <div class="events-calendar-content">

  <!-- Десктоп версия: 2 слайда по 4 карточки (2×2) -->
  <div class="activities-desktop d-none d-md-block">
    
    
  <div id="gallery-activities-desktop" class="carousel carousel-bottom-buttons slide" data-bs-ride="carousel">
    
    <div class="carousel-inner">
      <?php
      $desktopChunks = array_chunk($arResult["ITEMS"], 4);
      $slideIndex = 0;
      foreach ($desktopChunks as $chunk):
        $activeClass = ($slideIndex == 0) ? 'active' : '';
        ?>
        <div class="carousel-item <?=$activeClass?>">
          <div class="activities-list">
            <?php foreach ($chunk as $arItem):
              $dateStart = $arItem["PROPERTIES"]["DATE_START"]["VALUE"];
              $format = $arItem["PROPERTIES"]["TYPE_ONLINE"]["VALUE"];
              $day = date('j', strtotime($dateStart));
              $month = getRussianMonth(date('m', strtotime($dateStart)));
              $weekday = getRussianWeekday(date('w', strtotime($dateStart)));
              $time = date('H:i', strtotime($dateStart));
              ?>
              <div class="activity-card">
                <div class="activity-card__header">
                  <div class="activity-card__date">
                    <div class="activity-card__day-number"><?=$day?></div>
                    <div class="activity-card__date-info">
                      <div class="activity-card__month"><?=$month?></div>
                      <div class="activity-card__weekday"><?=$weekday?></div>
                    </div>
                  </div>
                  <div class="activity-card__time-info">
                    <div class="activity-card__time"><?=$time?></div>
                    <div class="activity-card__format"><?=$format?></div>
                  </div>
                </div>
                <div class="activity-card__title">
                  <?=$arItem["NAME"]?>
                </div>
                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="btn btn-primary activity-card__btn">Подробнее</a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php
        $slideIndex++;
      endforeach;
      ?>
    </div>

    
    <div class="carousel-bottom-controls">
      <button class="carousel-control-prev bottom-button" type="button" data-bs-target="#gallery-activities-desktop" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Назад</span>
      </button>

      <div class="carousel-indicators">
        <?php for ($i = 0; $i < count($desktopChunks); $i++): ?>
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
  <!-- end-of Десктоп версия -->

  <!-- Мобильная версия: 8 слайдов по 1 карточке -->
  <div class="activities-mobile d-block d-md-none">
    
    
  <div id="gallery-activities-mobile" class="carousel carousel-bottom-buttons slide" data-bs-ride="carousel">
    
    <div class="carousel-inner">
      
      <?php
      $mobileChunks = array_chunk($arResult["ITEMS"], 1);
      $slideIndex = 0;
      foreach ($mobileChunks as $chunk):
        $activeClass = ($slideIndex == 0) ? 'active' : '';
        ?>
        <div class="carousel-item <?=$activeClass?>">
          <div class="activities-list">
            <?php foreach ($chunk as $arItem): 
              $dateStart = $arItem["PROPERTIES"]["DATE_START"]["VALUE"];
              $format = $arItem["PROPERTIES"]["TYPE_ONLINE"]["VALUE"];
              $day = date('j', strtotime($dateStart));
              $month = getRussianMonth(date('m', strtotime($dateStart)));
              $weekday = getRussianWeekday(date('w', strtotime($dateStart)));
              $time = date('H:i', strtotime($dateStart));
              ?>
              <div class="activity-card">
                <div class="activity-card__header">
                  <div class="activity-card__date">
                    <div class="activity-card__day-number"><?=$day?></div>
                    <div class="activity-card__date-info">
                      <div class="activity-card__month"><?=$month?></div>
                      <div class="activity-card__weekday"><?=$weekday?></div>
                    </div>
                  </div>
                  <div class="activity-card__time-info">
                    <div class="activity-card__time"><?=$time?></div>
                    <div class="activity-card__format"><?=$format?></div>
                  </div>
                </div>
                <div class="activity-card__title">
                  <?=$arItem["NAME"]?>
                </div>
                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="btn btn-primary activity-card__btn">Подробнее</a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php
        $slideIndex++;
      endforeach;
      ?>
    
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
  <!-- end-of Мобильная версия -->

</div>

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

<div class="news-page-content">
  <div class="news-page-image">
    <?php if ($arResult["DETAIL_PICTURE"]["SRC"]): ?>
        <img src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arResult["NAME"]?>">
    <?php elseif ($arResult["PREVIEW_PICTURE"]["SRC"]): ?>
        <img src="<?=$arResult["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arResult["NAME"]?>">
    <?php else: ?>
        <img src="<?=ASSETS_DIR?>/assets/images/acb65624d3155c7090ec9e5c620ad0525a829a95.png" alt="">
    <?php endif; ?>
  </div>
  <div class="news-page-text">

    <div class="activity-page__header">
      <?php
      $dateStart = $arResult["PROPERTIES"]["DATE_START"]["VALUE"];
      $format = $arResult["PROPERTIES"]["TYPE_ONLINE"]["VALUE"];
      $day = date('j', strtotime($dateStart));
      $month = getRussianMonth(date('m', strtotime($dateStart)));
      $weekday = getRussianWeekday(date('w', strtotime($dateStart)));
      $time = date('H:i', strtotime($dateStart));
      ?>
      <div class="activity-page__date">
        <div class="activity-page__day-number"><?=$day?></div>
        <div class="activity-page__date-info">
          <div class="activity-page__month"><?=$month?></div>
          <div class="activity-page__weekday"><?=$weekday?></div>
        </div>
      </div>
      <div class="activity-page__time-info">
        <div class="activity-page__time"><?=$time?></div>
        <div class="activity-page__format"><?=$format?></div>
      </div>
    </div>

    <div class="activity-details">
      <div class="activity-details__item">
        <div class="activity-details__item-title">
          Организатор
        </div>
        <div class="activity-details__item-description">
          <?=$arResult["PROPERTIES"]["ORGANIZER"]["VALUE"]?>
        </div>
      </div>

      <div class="activity-details__item">
        <div class="activity-details__item-title">
          Место проведения
        </div>
        <div class="activity-details__item-description">
          <?=$arResult["PROPERTIES"]["MESTO"]["VALUE"]?>
        </div>
      </div>
    </div>

    <div class="news-page-title">
      <?=$arResult["NAME"]?>
    </div>

    <div class="news-page-text-content">
      <?=$arResult["DETAIL_TEXT"]?>
    </div>
  </div>
</div>

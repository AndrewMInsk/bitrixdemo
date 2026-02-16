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
<form>
<div class="news-filter">
  <div class="news-filter__search-input">
    <div class="input-group input-group-lg">
                <span class="input-group-text bg-white border-0 px-4">
                  <img src="<?=ASSETS_DIR?>/assets/images/icons/search-icon.svg" alt=""/>
                </span>
      <input type="search" name="search" value="<?=@trim($_GET['search'])?>" id="news-search" class="form-control border-0" placeholder="Поиск по тексту"
        autocomplete="off"
      />
    </div>
  </div>
</div>

<div class="news-filter-controls">
  <button class="btn btn-primary" data-events-filters-apply>
    Применить
  </button>
  <button class="btn btn-light" data-events-filters-reset onclick="document.getElementById('news-search').value='';return true">
    Сбросить фильтры
  </button>
</div>
</form>
<div class="education-course-list">
<?

// Группируем элементы по разделам
foreach ($arResult['ITEMS'] as $item) {?>
      <section class="education-course">
  <div class="education-course__image">
    <img src="<?=$item['PREVIEW_PICTURE']['SRC'] ?>" alt="<?=$item['NAME']?>"/>
  </div>

  <div class="education-course__content">
    <h2 class="education-course__title"><?=$item['NAME']?></h2>
    <div class="education-course__text">
      <div class="education-course__description"><?=$item['~PREVIEW_TEXT']?>
      </div>
      <div class="education-course__button">
        <a
          href="#"
          class="btn btn-primary"
          data-bs-toggle="modal"
          data-bs-target="#courseDetailsModal<?=$item['ID']?>"
        >Подробнее</a>
      </div>
    </div>
  </div>
</section>

<?}?>
    </div>
<?foreach ($arResult['ITEMS'] as $item) {?>
  <div class="modal fade" id="courseDetailsModal<?=$item['ID']?>" tabindex="-1">
    <div class="modal-dialog
      modal-xl
      
      modal-dialog-scrollable
      modal-dialog-centered
    ">
      <div class="modal-content common-modal-container">
        <div class="common-modal-content">
        
      <div class="course-modal">
        <div class="course-modal__main-info">
          <div class="course-modal__main-info-image ">
            <img src="<?=$item['DETAIL_PICTURE']['SRC'] ?>" alt="<?=$item['NAME']?>"/>
          </div>
          <div class="course-modal__main-info-text">
            <div class="course-modal__main-info-title">
              <?=$item['NAME']?>
            </div>
            <?if (!empty($item['PROPERTIES']['PROGRAM_LINK']['VALUE'])) {?>
            <div class="course-modal__main-info-link">
              <a href="<?=$item['PROPERTIES']['PROGRAM_LINK']['VALUE']?>">Перейти на страницу курса
                  <img src="<?=ASSETS_DIR?>/assets/images/icons/link-external.svg" alt=""></a>
            </div>
            <?}?>
          </div>
        </div>
        <div class="course-modal__content">
<?=$item['~DETAIL_TEXT']?>
</div>
      </div>
    
        </div>

        
          <button type="button" class="modal-close-button" data-bs-dismiss="modal" aria-label="Закрыть">
            <img src="<?=ASSETS_DIR?>/assets/images/icons/close-big.svg" alt="Закрыть" />
          </button>
        
      </div>
    </div>
  </div>
<?}?>

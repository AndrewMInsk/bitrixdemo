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

\Bitrix\Main\Loader::includeModule('iblock');
$sections = [];
$res = CIBlockSection::GetList(
    array('SORT' => 'ASC', 'ID' => 'DESC'),
    array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'DEPTH_LEVEL' => 1),
    false,
    array('ID', 'NAME')
);
while ($section = $res->GetNext()) {
    $sections[] = $section;
}

foreach ($sections as $section):
?>                <div class="about-construction-section">
    <?php
    if (in_array($section['ID'], [8, 36])) {
        ?><h3 class="about-construction-section__title"><?=$section['NAME']?></h3><?php
        // Получить дочерние секции второго уровня
        $resChild = CIBlockSection::GetList(
            array('SORT' => 'ASC'),
            array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'SECTION_ID' => $section['ID']),
            false,
            array('ID', 'NAME')
        );
        $childSections = [];
        while ($child = $resChild->GetNext()) {
            $childSections[] = $child;
        }
        // Цикл по дочерним секциям
        foreach ($childSections as $childSection) {
            $sectionItems = array_filter($arResult['ITEMS'], function($item) use ($childSection) {
                return $item['IBLOCK_SECTION_ID'] == $childSection['ID'];
            });
            if($sectionItems):
                $carouselId = 'aboutConstructionCarousel' . $childSection['ID'];
                $sourceId = 'aboutConstructionCardsSource' . $childSection['ID'];
                ?>
                    <h4 class="about-construction-section__title"><?=$childSection['NAME']?></h4>
                    <div id="<?=$sourceId?>" style="display:none;">
                        <?php foreach ($sectionItems as $arItem): ?>
                            <?
                            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                            ?>
                            <div class="construction-card" data-id="<?=$arItem['ID']?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                                <div class="construction-card__image">
                                    <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>"/>
                                </div>
                                <div class="construction-card__title">
                                    <?=$arItem["PROPERTIES"]["EVENT_DATE"]["VALUE"]?><br/>
                                    <span style="font-weight: normal"><?=$arItem["NAME"]?></span>
                                </div>
                                <div class="construction-card__description">
                                    <?=$arItem["PREVIEW_TEXT"]?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div id="<?=$carouselId?>" class="carousel carousel-bottom-buttons slide" data-bs-ride="carousel">
                        <div class="carousel-inner"></div>

                        <div class="carousel-controls">
                            <div class="carousel-controls-inner">
                                <button class="carousel-control-prev bottom-button" type="button" data-bs-target="#<?=$carouselId?>" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Назад</span>
                                </button>

                                <div class="carousel-indicators-custom"></div>
                                <button class="carousel-control-next bottom-button" type="button" data-bs-target="#<?=$carouselId?>" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Вперед</span>
                                </button>
                            </div>
                        </div>
                    </div>

            <?php
            endif;
        }
    } else {
        // Обычная логика для секций первого уровня
        $sectionItems = array_filter($arResult['ITEMS'], function($item) use ($section) {
            return $item['IBLOCK_SECTION_ID'] == $section['ID'];
        });
        if($sectionItems):
            $carouselId = 'gallery' . $section['ID'];
            ?>
                <div class="about-construction-section__title"><?=$section['NAME']?></div>

                <div id="<?=$carouselId?>" class="carousel carousel-bottom-buttons slide" data-bs-ride="carousel">

                    <div class="carousel-inner">
                        <?php $first = true; foreach ($sectionItems as $index => $arItem): ?>
                            <div class="carousel-item <?php if ($first) { echo 'active'; $first = false; } ?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                                <?
                                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                                ?>
                                <div class="about-construction-carousel-card-large">
                                    <div class="left-section">
                                        <div class="left-section__title"><?=$arItem["NAME"]?></div>
                                        <div class="left-section__description">
                                            <?=$arItem["PREVIEW_TEXT"]?>
                                        </div>
                                    </div>
                                    <div class="right-section">
                                        <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>"/>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="carousel-bottom-controls">
                        <button class="carousel-control-prev bottom-button" type="button" data-bs-target="#<?=$carouselId?>" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Назад</span>
                        </button>

                        <div class="carousel-indicators ">
                            <?$counter = 0;?>
                            <?php foreach ($sectionItems as $index => $arItem): ?>
                                <button type="button" data-bs-target="#<?=$carouselId?>" data-bs-slide-to="<?=$counter?>"
                                    <?php if ($counter == 0) echo 'class="active" aria-current="true"'; ?>
                                        aria-label="Slide <?=($counter+1)?>"></button>
                                <?$counter++?>
                            <?php endforeach; ?>
                        </div>

                        <button class="carousel-control-next bottom-button" type="button" data-bs-target="#<?=$carouselId?>" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Вперед</span>
                        </button>
                    </div>


                </div>

        <?php
        endif;
    }
    ?></div><?php
endforeach;
?>

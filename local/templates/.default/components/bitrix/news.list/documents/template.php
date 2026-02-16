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

// Получаем все разделы инфоблока
if (!empty($arResult['ITEMS'])) {
    $sectionIds = [];
    foreach ($arResult['ITEMS'] as $item) {
        if ($item['IBLOCK_SECTION_ID']) {
            $sectionIds[] = $item['IBLOCK_SECTION_ID'];
        }
    }
    $sectionIds = array_unique($sectionIds);

    if (!empty($sectionIds)) {
        $sections = [];
        $rsSections = \Bitrix\Iblock\SectionTable::getList([
            'filter' => [
                'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                'ID' => $sectionIds,
                'ACTIVE' => 'Y',
            ],
            'select' => ['ID', 'NAME'],
            'order' => ['SORT' => 'DESC'],
        ]);
        while ($arSection = $rsSections->fetch()) {
			$arSection['ITEMS'] = [];
            $sections[$arSection['ID']] = $arSection;
        }
        $arResult['SECTIONS'] = $sections;
    }
}

// Группируем элементы по разделам
foreach ($arResult['ITEMS'] as $item) {
    $sectionId = $item['IBLOCK_SECTION_ID'];
	$arResult['SECTIONS'][$sectionId]['ITEMS'][] = $item;
}
?>

<?php if (!empty($arResult['SECTIONS'])): ?>
    <div class="accordion" id="accordionExample">
        <?php $collapseIndex = 1; ?>
        <?php foreach ($arResult['SECTIONS'] as $sectionId => $section): ?>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button
                            class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse<?=$collapseIndex; ?>"
                            aria-expanded="false"
                            aria-controls="collapse<?= $collapseIndex; ?>"
                    >
                        <?= htmlspecialchars($section['NAME']); ?>
                    </button>
                </h3>
                <div id="collapse<?=$collapseIndex; ?>" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        <div class="documents-list-2-columns">
                            <?php foreach ($section['ITEMS'] as $arItem): ?>
                                <?php
                                $file = $arItem['PROPERTIES']['FILE']['VALUE'];
                                if (is_array($file)) {
                                    $fileId = $file['ID'];
                                } else {
                                    $fileId = $file;
                                }
                                $fileArray = null;
                                $fileLink = $arItem['DETAIL_PAGE_URL'];
                                $fileName = '';
                                $fileSize = '';
                                if (!empty($fileId)) {
                                    $fileArray = CFile::GetFileArray($fileId);
                                    if ($fileArray) {
                                        $fileLink = $fileArray['SRC'];
                                        $fileName = $fileArray['ORIGINAL_NAME'];
                                        $fileSize = CFile::FormatSize($fileArray['FILE_SIZE']);
                                    }
                                }
                                ?>
                                <article class="document-card">
                                    <div class="document-card__icon">
                                        <img src="<?= ASSETS_DIR ?>/assets/images/icons/documents.svg" alt="Иконка документа" />
                                    </div>
                                    <div class="document-card__content">
                                        <div class="document-card__document-name">
                                            <?=$arItem['NAME']; ?>
                                            <?if($arItem['PREVIEW_TEXT']):?>
                                            <a
                                                    class="document-card__document-link"
                                                    href="<?php echo $fileLink; ?>"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                            >
                                                <?=htmlspecialchars($arItem['PREVIEW_TEXT']); ?>
                                            </a>
                                            <?else:?>
                                                <a
                                                        class="document-card__document-link"
                                                        href="<?php echo $fileLink; ?>"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                >
                                                    Открыть
                                                </a>
                                            <?endif;?>
                                            <span class="document-card__document-download">
                                                <a
                                                        class="document-card__document-download-link"
                                                        href="<?=$fileLink; ?>"
                                                        download="<?=htmlspecialchars($fileName); ?>"
                                                >
                                                    <img src="<?= ASSETS_DIR ?>/assets/images/icons/download-icon.svg" alt="Скачать" />
                                                    <?=$fileSize; ?>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php $collapseIndex++; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

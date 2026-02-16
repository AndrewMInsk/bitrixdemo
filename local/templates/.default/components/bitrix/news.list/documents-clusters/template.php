<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
Loader::includeModule('iblock');

if (!empty($arResult['ITEMS'])): ?>
<div class="accordion" id="accordionExample">
    <div class="accordion-item">
        <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                Документы
            </button>
        </h3>
        <div id="collapseOne" class="accordion-collapse collapse" style="">
            <div class="accordion-body">
                <div class="documents-list-2-columns">
                    <?foreach ($arResult['ITEMS'] as $arItem):?>
                        <?$file = CFile::GetFileArray($arItem['PROPERTIES']['FILE']['VALUE']);?>
                        <article class="document-card">
                            <div class="document-card__icon">
                                <img src="<?=ASSETS_DIR?>/assets/images/icons/documents.svg" alt="Иконка документа">
                            </div>
                            <div class="document-card__content">
                                <div class="document-card__document-name">
                                    <?=$arItem['NAME']?>
                                    <a class="document-card__document-link" href="<?=CFile::GetPath($arItem['PROPERTIES']['FILE']['VALUE'])?>" target="_blank" rel="noopener noreferrer">
                                        <?=$arItem['DETAIL_TEXT']?:'Открыть'?>
                                    </a>
                                    <span class="document-card__document-download">
                                        <a class="document-card__document-download-link" href="<?=CFile::GetPath($arItem['PROPERTIES']['FILE']['VALUE'])?>" download="<?=$file['ORIGINAL_NAME']?>">
                                            <img src="<?=ASSETS_DIR?>/assets/images/icons/download-icon.svg" alt="Скачать">
                                            <?=getFileTypeCustom($file['ORIGINAL_NAME'])?> <?=formatSizeCustom($file['FILE_SIZE'])?>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </article>
                    <?endforeach;?>
                </div>
            </div>
        </div>
    </div>
</div>
<?endif;?>

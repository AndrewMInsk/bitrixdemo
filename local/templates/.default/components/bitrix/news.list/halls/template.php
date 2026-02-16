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

<?foreach($arResult["ITEMS"] as $arItem):?>
    <?
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>
    <div class="infrastructure-page__cluster cluster-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <div class="cluster-item__left-section">
            <?
            $slider = $arItem["PROPERTIES"]["SLIDER"]["VALUE"];
            if (!empty($slider)):
                ?>
                <div id="gallery-<?=$arItem['ID']?>" class="carousel carousel-side-buttons slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?foreach($slider as $key => $fileId):?>
                            <?
                            $file = CFile::GetFileArray($fileId);
                            $src = CFile::GetPath($fileId);
                            if ($file && $file['WIDTH'] > 0) {
                                $newWidth = 800;
                                $newHeight = round($file['HEIGHT'] * $newWidth / $file['WIDTH']);
                                $resize = CFile::ResizeImageGet($fileId, array("width" => $newWidth, "height" => $newHeight), BX_RESIZE_IMAGE_EXACT, true);
                                $src = $resize['src'];
                            }
                            if ($src):
                                ?>
                                <div class="carousel-item <?if($key == 0):?>active<?endif;?> mist">
                                    <div class="carousel-image compressanda">
                                        <img src="<?=$src?>" alt="<?=$arItem["NAME"]?>"/>
                                    </div>
                                </div>
                            <?endif;?>
                        <?endforeach;?>
                    </div>
                    <?if(count($slider) > 1):?>
                        <div class="carousel-indicators">
                            <?foreach($slider as $key => $fileId):?>
                                <button type="button" data-bs-target="#gallery-<?=$arItem['ID']?>" data-bs-slide-to="<?=$key?>" class="<?if($key == 0):?>active<?endif;?>" aria-current="true" aria-label="Slide <?=($key+1)?>"></button>
                            <?endforeach;?>
                        </div>
                        <button class="carousel-control-prev side-button" type="button" data-bs-target="#gallery-<?=$arItem['ID']?>" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Назад</span>
                        </button>
                        <button class="carousel-control-next side-button" type="button" data-bs-target="#gallery-<?=$arItem['ID']?>" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Вперед</span>
                        </button>
                    <?endif;?>
                </div>
            <?endif;?>
        </div>
        <div class="cluster-item__right-section">
            <div class="cluster-item__congress-hall congress-hall">
                <div class="congress-hall__info-wrapper">
                    <div class="congress-hall__info">
                        <h2 class="congress-hall__cluster-title"><?=$arItem["NAME"]?></h2>
                        <?if($arItem["PREVIEW_TEXT"]):?>
                            <div class="congress-hall__cluster-area-badge"><?=$arItem["PREVIEW_TEXT"]?></div>
                        <?endif;?>
                    </div>
                    <?if($arItem["PROPERTIES"]["PRICE_INCLUDED"]["~VALUE"]):?>
                        <div class="congress-hall__info-details">
                            <?if($arItem['IBLOCK_SECTION_ID'] != '6'):?>
                            <div class="congress-hall__info-details-title">Стоимость включает в себя</div>
                            <?endif;?>
                            <ul class="congress-hall__details-list">

                                <?foreach($arItem["PROPERTIES"]["PRICE_INCLUDED"]["~VALUE"] as $item):?>
                                    <li><?=$item['TEXT']?></li>
                                <?endforeach;?>
                            </ul>
                        </div>
                    <?endif;?>
                    <?if($arItem["PROPERTIES"]["TECHNICAL"]["~VALUE"]):?>
                        <div class="congress-hall__info-details">
                            <div class="congress-hall__info-details-title--small">
                                Техническое оборудование зала:
                            </div>

                            <ul class="congress-hall__details-list">
                                <?foreach($arItem["PROPERTIES"]["TECHNICAL"]["~VALUE"] as $item):?>
                                    <li><?=$item['TEXT']?></li>
                                <?endforeach;?>
                            </ul>
                        </div>
                    <?endif;?>
                </div>
                <div class="congress-hall__order-wrapper">
                    <?if($arItem['IBLOCK_SECTION_ID'] == '6'):?>
                        <a class="btn btn-primary cluster-order-btn" href="https://i.moscow/intc_kongress">
                            Забронировать
                        </a>
                    <?else:?>
                        <a class="btn btn-primary cluster-order-btn" href="/reserve/?cluster-id=<?=$arItem['IBLOCK_SECTION_ID']?>&congress-hall-id=<?=$arItem['ID']?>">
                            Забронировать
                        </a>
                    <?endif;?>

                    <?if($arItem["PROPERTIES"]["PRICES"]["~VALUE"]):?>
                        <?if($arItem['IBLOCK_SECTION_ID'] != '6'):?>
                        <?foreach($arItem["PROPERTIES"]["PRICES"]["~VALUE"] as $price):?>
                            <?
                            $parts = explode("-", $price['TEXT']);
                            ?>
                            <div class="congress-hall__info-badge">
                                <h2 class="congress-hall__info-badge-title"><?=$parts[0]?></h2>
                                <div class="congress-hall__info-badge-description"><?=$parts[1]?></div>
                            </div>
                        <?endforeach;?>
                        <?endif;?>
                    <?endif;?>
                </div>
            </div>
        </div>
    </div>
<?endforeach;?>

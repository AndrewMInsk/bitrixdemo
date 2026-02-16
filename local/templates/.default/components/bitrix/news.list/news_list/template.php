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

$promoItem = $arResult['ITEMS'][0];
$listItems = array_slice($arResult['ITEMS'], 1);
?>

<div class="home-news">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="home-news__title">Новости</div>
        <div class="home-news__link">
            <a href="/news/" class="link-animated">
                <span class="link-animated__text">Все новости</span>
                <span class="link-animated__icon">
                    <img src="<?=ASSETS_DIR?>/assets/images/icons/arrow_right_2.svg" alt="" />
                </span>
            </a>
        </div>
    </div>

    <div class="d-flex home-news__content-wrapper">
        <div class="home-promo-news">
            <?php
            $image = $promoItem['PREVIEW_PICTURE'];
            if ($image) {
                $resizedImage = CFile::ResizeImageGet($image, array("width" => 500), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                $imagePath = $resizedImage['src'];
            } else {
                $imagePath = ASSETS_DIR . '/assets/images/image-73.jpg'; // Default image
            }
            ?>
            <div class="home-news-image">
                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($promoItem['NAME']) ?>" />
            </div>
            <div class="home-news-list">
                <div class="home-news-list__item">
                    <div class="home-news-list__item-date"><?= FormatDate("d.m.Y", MakeTimeStamp($promoItem["DISPLAY_ACTIVE_FROM"] ?: $promoItem["DATE_CREATE"])) ?></div>
                    <div class="home-news-list__item-title">
                        <a href="<?= $promoItem['DETAIL_PAGE_URL'] ?>">
                            <?= htmlspecialchars($promoItem['NAME']) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="home-news-list">
            <?php foreach ($listItems as $item): ?>
            <div class="home-news-list__item">
                <div class="home-news-list__item-date"><?= FormatDate("d.m.Y", MakeTimeStamp($item["DISPLAY_ACTIVE_FROM"] ?: $item["DATE_CREATE"])) ?></div>
                <div class="home-news-list__item-title">
                    <a href="<?= $item['DETAIL_PAGE_URL'] ?>">
                        <?= htmlspecialchars($item['NAME']) ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult['ITEMS'])) {
    ShowNote('Новости не найдены');
    return;
}
?>

<div class="news-list">
    <?php if ($arResult['CACHED']): ?>
      Из кэша
        </div>
    <?php else: ?>
      Без кэша
    <?php endif; ?>

    <div class="news-filters">
        <form method="get" action="">
            <div class="filter-row">
                <div class="filter-item">
                    <label>Дата от</label>
                    <input type="date" name="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>">
                </div>
                <div class="filter-item">
                    <label>Дата до</label>
                    <input type="date" name="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>">
                </div>
                <div class="filter-item">
                    <label>ID раздела</label>
                    <input type="text" name="section_id" value="<?= htmlspecialchars($_GET['section_id'] ?? '') ?>" placeholder="ID раздела">
                </div>
                <div class="filter-item">
                    <label>Название</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($_GET['name'] ?? '') ?>" placeholder="Название">
                </div>
                <div class="filter-item">
                    <button type="submit">Применить фильтр</button>
                </div>
            </div>
        </form>
    </div>

    <div class="news-items">
        <?php foreach ($arResult['ITEMS'] as $item): ?>
            <div class="news-item">
                <div class="news-image">
                    <?php if ($item['PREVIEW_PICTURE']): ?>
                        <img src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>" alt="<?= htmlspecialchars($item['NAME']) ?>">
                    <?php endif; ?>
                </div>
                <div class="news-content">
                    <h3 class="news-title"><a href="<?= $item['DETAIL_PAGE_URL'] ?>"><?= htmlspecialchars($item['NAME']) ?></a></h3>
                    <div class="news-date"><?= $item['DATE_ACTIVE_FROM'] ?></div>
                    <div class="news-preview"><?= htmlspecialchars($item['PREVIEW_TEXT']) ?></div>
                    <div class="news-section">
                        Раздел: 
                        <a href="#"><?= htmlspecialchars($item['IBLOCK_SECTION_ID']) ?></a>
                    </div>
                    <div class="news-author">
                        Автор: 
                        <?= htmlspecialchars($item['AUTHOR']) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="news-pagination">
        <?= $arResult['NAV_STRING'] ?>
    </div>
</div>
<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

<?if (!empty($arResult)):?>
<div class="sidebar-left upper">
  <ul>
  <?foreach($arResult as $arItem):?>
    <li<?= ($arItem["SELECTED"])?' class="active"':''?>>
      <a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
    </li>
  <?endforeach?>
  </ul>
</div>
<?endif?>

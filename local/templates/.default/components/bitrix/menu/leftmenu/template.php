<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

<?if (!empty($arResult)):?>
<nav class="page-tabs__navigation">
  <div class="nav page-tabs__nav">
	
<?foreach($arResult as $arItem):?>	  
		<a href="<?=$arItem["LINK"]?>" class="nav-link page-tabs__nav-link <?= ($arItem["SELECTED"])?'active':''?>">
		  <?= !empty($arItem["PARAMS"]["ALT_NAME"]) ? $arItem["PARAMS"]["ALT_NAME"] : $arItem["TEXT"] ?>
		</a>
<?endforeach?>	  
	
  </div>
</nav>
<?endif?>

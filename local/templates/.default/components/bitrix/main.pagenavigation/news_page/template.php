<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arParams */
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */

/** @var PageNavigationComponent $component */
$component = $this->getComponent();

$this->setFrameMode(true);
$pagePrev = max($arResult["CURRENT_PAGE"] - 1,1);
$pageNext = min($arResult["CURRENT_PAGE"] +1, $arResult["PAGE_COUNT"]);
?>
    <ul class="pagination">
      <li class="page-item"><a class="page-link" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($pagePrev))?>">
          <img src="<?=ASSETS_DIR?>/assets/images/icons/arrow-left-icon_grey.svg" alt=""></a></li>
      <li class="page-item">
	  <?if ($arResult["CURRENT_PAGE"]==1){?>
	  <span class="page-link active">1</span>
	  <?}else{?>
	  <a class="page-link" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate(1))?>">1</a>
	  <?}?>
	  </li>
	  <?if (($arResult["CURRENT_PAGE"]-1)>1){?>
      <li class="page-item">
        <span class="page-link page-link-spacer">...</span>
      </li>
	  <?}?>
	  <?if ($arResult["CURRENT_PAGE"]!=1 && $arResult["CURRENT_PAGE"]!=$arResult['PAGE_COUNT']){?>
      <li class="page-item"><span class="page-link active"><?=$arResult["CURRENT_PAGE"]?></span></li>
	  <?}?>
	  <?if (($arResult["PAGE_COUNT"]-$arResult["CURRENT_PAGE"])>1){?>
      <li class="page-item">
        <span class="page-link page-link-spacer">...</span>
      </li>
	  <?}?>
      <li class="page-item">
	  <?if ($arResult["CURRENT_PAGE"]==$arResult["PAGE_COUNT"]){?>
	  <span class="page-link active"><?=$arResult["PAGE_COUNT"]?></span>
	  <?}else{?>
	  <a class="page-link" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($arResult["PAGE_COUNT"]))?>"><?=$arResult["PAGE_COUNT"]?></a>
	  <?}?>
	  </li>
      <li class="page-item"><a class="page-link" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($pageNext))?>"><img src="<?=ASSETS_DIR?>/assets/images/icons/arrow-right-icon_grey.svg" alt="">
        </a></li>
    </ul>

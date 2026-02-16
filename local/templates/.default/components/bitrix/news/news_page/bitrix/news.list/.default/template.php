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
$this->setFrameMode(true);?>
<ul>
	<li>
		<div class="option-item" data-option-id="0">
			<div class="option-item__image">
        <div class="charity-card__logo-wrap">
          <img src="<?=SITE_TEMPLATE_PATH?>/images/mklogo.jpg" alt="" class="charity-card__logo" />
        </div>
			</div>
			<span class="option-item__title">Помочь всем</span>
		</div>
	</li>
	<?foreach($arResult["ITEMS"] as $arItem):?>
		<li>
            <div class="option-item" data-option-id="<?=$arItem['ID']?>">
				<div class="option-item__image">
          <div class="charity-card__logo-wrap">
            <?if(@trim($arItem["PREVIEW_PICTURE"]["SRC"]) != ''):?>
              <img
                src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                alt="<?=$arItem["NAME"]?>"
                class="charity-card__logo"
              />
            <?else:?>
              <img
                src="<?=SITE_TEMPLATE_PATH?>/images/mklogo.jpg"
                alt="Нет изображения"
                class="charity-card__logo"
              />
            <?endif;?>
          </div>
				</div>
				<span class="option-item__title"><?=$arItem["NAME"]?></span>
			</div>
		</li>
	<?endforeach?>
</ul>

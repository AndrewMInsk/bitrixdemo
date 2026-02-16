<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>

<menu>
  <ul class=" ">
<?
$previousLevel = 0;
foreach($arResult as $arItem):
?>
    <?if ($previousLevel && $arItem["DEPTH_LEVEL"] <= $previousLevel):?>
        <?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
    <?endif?>

    <?if ($arItem["IS_PARENT"]):?>
        <li class="has-child <?if ($arItem["SELECTED"]):?>active<?endif?>">
            <a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
            <ul class="submenu"> <!-- Начало второго уровня -->
    <?else:?>
        <li class="<?if ($arItem["SELECTED"]):?>active<?endif?>">
            <a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
        </li>
    <?endif?>

    <?$previousLevel = $arItem["DEPTH_LEVEL"];?>
<?endforeach?>

<?if ($previousLevel > 1)://закрытие последнего пункта
    echo str_repeat("</ul></li>", ($previousLevel-1));
endif?>
  </ul>
</menu>
<?endif?>

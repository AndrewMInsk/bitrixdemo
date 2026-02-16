<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>

<nav class="footer__nav" aria-label="Footer navigation">
<?
$previousLevel = 0;
foreach($arResult as $arItem):
?>
	<?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
		<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
	<?endif?>

	<?if ($arItem["IS_PARENT"]):?>
                <a class="footer__nav-link" href="<?=$arItem["LINK"]?>" data-bs-toggle="dropdown" data-bs-auto-close="outside"><?=$arItem["TEXT"]?></a>

	<?else:?>

		<?if ($arItem["PERMISSION"] > "D"):?>
                    <a class="footer__nav-link" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
		<?endif?>

	<?endif?>

	<?$previousLevel = $arItem["DEPTH_LEVEL"];?>

<?endforeach?>

<?if ($previousLevel > 1)://close last item tags?>
	<?=str_repeat("</ul></li>", ($previousLevel-1) );?>
<?endif?>

</nav>
<?endif?>

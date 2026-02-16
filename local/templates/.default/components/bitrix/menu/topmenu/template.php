<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
<menu>
  <ul>
    <?foreach($arResult as $arItem):?>
      <?if ($arItem["PERMISSION"] > "D"):?>
        <li<?if($arItem["SELECTED"]):?> class="active"<?endif?>>
          <a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
        </li>
      <?endif?>
    <?endforeach?>
  </ul>
</menu>
<?endif?>

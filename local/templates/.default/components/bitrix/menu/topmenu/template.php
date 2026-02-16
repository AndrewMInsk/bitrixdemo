<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>

<ul class="navbar-nav gap-2">
    <?
    $previousLevel = 0;
    foreach($arResult as $arItem):
    ?>
    <?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
        <?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
    <?endif?>

    <?if ($arItem["IS_PARENT"]):?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle <?= ($arItem["CHILD_SELECTED"] || $arItem["SELECTED"])?'active':''?>" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
        <ul class="dropdown-menu">



            <?else:?>

                <?if ($arItem["PERMISSION"] > "D"):?>

                    <?if ($arItem["DEPTH_LEVEL"] == 1):?>
                        <li class="nav-item">
                            <a class="nav-link <?= ($arItem["CHILD_SELECTED"] || $arItem["SELECTED"])?'active':''?>" href="<?=$arItem["LINK"]?>"">

                                <?=$arItem["TEXT"]?></a>
                        </li>
                    <?else:?>
                      <li>
                            <a class="dropdown-item" href="<?=$arItem["LINK"]?>">

                               <?if ($arItem['PARAMS']['ICON']):?>
                      <span class="dropdown-item__icon">
                        <img src="<?=ASSETS_DIR . $arItem['PARAMS']['ICON']?>" alt="" />
                      </span>
                                <?endif;?>

                                <?=$arItem["TEXT"]?></a>
                        </li>
                    <?endif?>


                <?endif?>

            <?endif?>

            <?$previousLevel = $arItem["DEPTH_LEVEL"];?>

            <?endforeach?>

            <?if ($previousLevel > 1)://close last item tags?>
                <?=str_repeat("</ul></li>", ($previousLevel-1) );?>
            <?endif?>
          
		  <!-- Социальные сети для мобильных -->
          <li class="nav-item social-items">
            <span class="d-lg-none">
              Мы в соцсетях:
            </span>
            <a
              class="header__social-button header__social-button-mobile"
              href="<?=TELEGRAM_LINK?>"
              aria-label="Telegram"
              rel="nofollow noopener noreferrer"
              target="_blank"
            >
              <img src="<?=ASSETS_DIR?>/assets/images/icons/telegram.svg" alt="Telegram"/>
            </a>

            <a
              class="header__social-button header__social-button-mobile"
              href="<?=VK_LINK?>"
              aria-label="VK"
              rel="nofollow noopener noreferrer"
              target="_blank"
            >
              <img src="<?=ASSETS_DIR?>/assets/images/icons/vk.svg" alt="VK"/>
            </a>
          </li>

        </ul>
        <?endif?>

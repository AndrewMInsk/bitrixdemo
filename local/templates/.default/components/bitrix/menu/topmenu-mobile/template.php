<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>

<ul class="mobile-nav">
    <?
    $previousLevel = 0;
    foreach($arResult as $arItem):
    ?>
    <?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
        <?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
    <?endif?>

    <?if ($arItem["IS_PARENT"]):?>
    <li class="mobile-nav-item has-submenu">
        <div class="mobile-nav-link-wrapper">
            <a class="mobile-nav-link <?= ($arItem["CHILD_SELECTED"] || $arItem["SELECTED"])?'active':''?>" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
            <button class="mobile-submenu-toggle" type="button" aria-label="Открыть подменю" aria-expanded="false">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </button>
        </div>
        <ul class="mobile-submenu">



            <?else:?>

                <?if ($arItem["PERMISSION"] > "D"):?>

                    <?if ($arItem["DEPTH_LEVEL"] == 1):?>
                        <li class="mobile-nav-item">
                            <a class="mobile-nav-link <?= ($arItem["CHILD_SELECTED"] || $arItem["SELECTED"])?'active':''?>" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
                        </li>
                    <?else:?>
                      <li>
                            <a class="mobile-submenu-item" href="<?=$arItem["LINK"]?>">
                               <?if ($arItem['PARAMS']['ICON']):?>
                      <span class="mobile-submenu-icon">
                        <img src="<?=ASSETS_DIR . $arItem['PARAMS']['ICON']?>" alt="" />
                      </span>
                                <?endif;?>
                                <span class="mobile-submenu-text"><?=$arItem["TEXT"]?></span>
                            </a>
                        </li>
                    <?endif?>


                <?endif?>

            <?endif?>

            <?$previousLevel = $arItem["DEPTH_LEVEL"];?>

            <?endforeach?>

            <?if ($previousLevel > 1)://close last item tags?>
                <?=str_repeat("</ul></li>", ($previousLevel-1) );?>
            <?endif?>

		  <!-- Login button for mobile -->
          <li class="mobile-nav-item">
            <?$APPLICATION->IncludeComponent("osinit:login_button", "mobile", array(), false);?>
          </li>

		  <!-- Социальные сети для мобильных -->
          <li class="mobile-nav-item social-items-mobile">
            <span class="social-label">Мы в соцсетях:</span>
            <div class="social-buttons">
              <a
                class="social-button"
                href="https://t.me/sticmsu"
                aria-label="Telegram"
                rel="nofollow noopener noreferrer"
                target="_blank"
              >
                <img src="<?=ASSETS_DIR?>/assets/images/icons/telegram.svg" alt="Telegram"/>
              </a>
              <a
                class="social-button"
                href="https://vk.com/sticmsu"
                aria-label="VK"
                rel="nofollow noopener noreferrer"
                target="_blank"
              >
                <img src="<?=ASSETS_DIR?>/assets/images/icons/vk.svg" alt="VK"/>
              </a>
            </div>
          </li>

        </ul>
        <?endif?>

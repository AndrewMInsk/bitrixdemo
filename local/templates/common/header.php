<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Page\Asset;
CModule::IncludeModule('iblock');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <? $APPLICATION->ShowMeta("description") ?>
    <title><? $APPLICATION->ShowTitle() ?></title>
    <? $APPLICATION->ShowHead(); ?>
    <?
    Asset::getInstance()->addCss(ASSETS_DIR . '/assets/stylesheets/bootstrap.min.css');
    Asset::getInstance()->addCss(ASSETS_DIR . '/assets/stylesheets/screen.css');
    Asset::getInstance()->addCss(ASSETS_DIR . '/slick/slick.css');
    Asset::getInstance()->addCss(ASSETS_DIR . '/assets/stylesheets/custom.css');
    
    ?>
</head>
<body>
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>

    <div class="page<?if($APPLICATION->GetCurPage() != '/'):?> inner<?endif?>">
        <?php
        // Получаем свойство баннера текущего раздела
        $banner = $APPLICATION->GetDirProperty("BANNER_IMAGE");
        // Если свойство не задано, используем баннер по умолчанию
        if (empty($banner)) {
            $banner = ASSETS_DIR . "/img/page-1-banner.png";
        }
        ?>
        <?if($APPLICATION->GetCurPage() != '/'):?>
        <img src="<?=$banner?>" class="banner">
        <?endif?>
      <header>
        <div class="container">
          <div class="row">
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xl-10 no-padding contacts-column">
              <div class="contact-content">
                <div class="contacts-wrap address">
                  <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                      "AREA_FILE_SHOW" => "file",
                      "PATH" => SITE_DIR."include/address.php",
                      "EDIT_TEMPLATE" => ""
                    )
                  );?>
                </div>
                <div class="contacts-wrap mail">
                  <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                      "AREA_FILE_SHOW" => "file",
                      "PATH" => SITE_DIR."include/mail.php",
                      "EDIT_TEMPLATE" => ""
                    )
                  );?>
                </div>
                <div class="contacts-wrap phone">
                  <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                      "AREA_FILE_SHOW" => "file",
                      "PATH" => SITE_DIR."include/phone.php",
                      "EDIT_TEMPLATE" => ""
                    )
                  );?>
                </div>
              </div>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2 no-padding call-column">
              <a href="#" class="call upper">заказать звонок</a>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2 no-padding"><div class="logo">ЛЕСА<span>. 71</span></div></div>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xl-10 no-padding">
              <?$APPLICATION->IncludeComponent(
                "bitrix:menu",
                "topmenu",
                Array(
                  "ROOT_MENU_TYPE" => "top",
                  "MAX_LEVEL" => "2",
                  "CHILD_MENU_TYPE" => "left",
                  "USE_EXT" => "N",
                  "DELAY" => "N",
                  "ALLOW_MULTI_SELECT" => "N",
                  "MENU_CACHE_TYPE" => "A",
                  "MENU_CACHE_TIME" => "3600",
                  "MENU_CACHE_USE_GROUPS" => "Y",
                  "MENU_CACHE_GET_VARS" => ""
                )
              );?>
            </div>
          </div>
        </div>
      </header>

      <?if($APPLICATION->GetCurPage() == '/'):?>
      <div class="slider">
        <div>
          <img src="<?=ASSETS_DIR?>/img/slide-1.png" />
          <div class="container">
            <div class="text-wrap upper">
              <h2>профессиональные строительные леса</h2>
              <h1 class="black">аренда и продажа</h1>
            </div>
          </div>
        </div>
        <div>
          <img src="<?=ASSETS_DIR?>/img/slide-2.png" />
          <div class="container">
            <div class="text-wrap upper">
              <h2>строительные леса б/у</h2>
              <h1>быстро. качественно.</h1>
            </div>
          </div>
        </div>
        <div>
          <img src="<?=ASSETS_DIR?>/img/slide-3.png" />
          <div class="container">
            <div class="text-wrap upper third">
              <h2>аренда строительных лесов</h2>
              <h1>аренда вышки туры</h1>
            </div>
          </div>
        </div>
      </div>
      <?else:?>
          <div class="breadcrumbs">
              <div class="container">
                  <ul>
                      <li><a href="#">Главная</a></li>
                      <li><a href="#">Услуги и цены</a></li>
                      <li>Продажа строительных лесов</li>
                  </ul>
              </div>
          </div>
      <?endif;?>
      <div class="container">
        <div class="page-content">
          <div class="sidebar-left upper">
            <?$APPLICATION->IncludeComponent(
              "bitrix:menu",
              "left",
              Array(
                "ROOT_MENU_TYPE" => "left",
                "MENU_CACHE_TYPE" => "A",
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "MENU_CACHE_GET_VARS" => "",
                "MAX_LEVEL" => "1",
                "CHILD_MENU_TYPE" => "",
                "USE_EXT" => "N",
                "DELAY" => "N",
                "ALLOW_MULTI_SELECT" => "N"
              )
            );?>
          </div>

          <div class="content-wrapper">

            <h1><? $APPLICATION->ShowTitle(false) ?></h1>

 

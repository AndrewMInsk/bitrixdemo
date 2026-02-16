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

    
    ?>
</head>
<body>
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>

    <div class="page">

      <header>
        <div class="container">
          <div class="row">
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xl-10 no-padding contacts-column">
              <div class="contact-content">
                <div class="contacts-wrap address">
                  <div class="title">Адрес</div>
                  <div class="contact">г. Тула, ул. Октябрьская, 6</div>
                </div>
                <div class="contacts-wrap mail">
                  <div class="title">Почта</div>
                  <div class="contact">lesa71@mail.ru</div>
                </div>
                <div class="contacts-wrap phone">
                  <div class="title">Телефон</div>
                  <div class="contact">+7 800 700-8000</div>
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
              <menu>
                <ul>
                  <li><a href="#">Главная</a></li>
                  <li><a href="#">О компании</a></li>
                  <li class="active"><a href="#">Услуги и цены</a></li>
                  <li><a href="#">Фотогалерея</a></li>
                  <li><a href="#">Контакты</a></li>
                </ul>
              </menu>
            </div>
          </div>
        </div>
      </header>


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
      <div class="container">
        <div class="page-content">
          <div class="sidebar-left upper">
            <ul>
              <li><a href="#">аренда<br>строительных лесов</a></li>
              <li class="active"><a href="#">продажа<br>строительных лесов</a></li>
              <li><a href="#">леса<br>строительные б/у</a></li>
              <li><a href="#">аренда вышки туры</a></li>
            </ul>
          </div>

          <div class="content-wrapper">

            <h1><? $APPLICATION->ShowTitle(false) ?></h1>

 

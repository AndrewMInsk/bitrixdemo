<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Page\Asset;
?>
    </div>
          <div class="clearfix"></div>
        </div>
      </div>

      <div class="areas-of-use">
        <div class="container">
          <h1>Области применения</h1>
          <div class="row">
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2 constraction red"><i></i><a href="#">Строительство</a></div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2 rails"><i></i><a href="#">Железные дороги</a></div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2 aviation red"><i></i><a href="#">Авиация</a></div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2 industrial"><i></i><a href="#">Промышленные объекты</a></div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2 shipbuilding red"><i></i><a href="#">Кораблестроение</a></div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2 highway"><i></i><a href="#">Авто дороги</a></div>
          </div>
        </div>
        <footer>
          <div class="container">
            <div class="row">
              <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2 no-padding"><div class="logo">ЛЕСА<span>. 71</span></div></div>
              <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xl-10 no-padding">
                  <?$APPLICATION->IncludeComponent(
                          "bitrix:menu",
                          "topmenu",
                          Array(
                                  "ROOT_MENU_TYPE" => "top",
                                  "MAX_LEVEL" => "1",
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
          </div>
        </footer>
      </div>

   </div>

<!-- Модальное окно для формы "Заказать звонок" -->
<div class="modal fade" id="callbackModal" tabindex="-1" role="dialog" aria-labelledby="callbackModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="callbackModalLabel">Заказать звонок</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?$APPLICATION->IncludeComponent(
          "bitrix:form.result.new",
          "callback",
          Array(
            "WEB_FORM_ID" => "1",
            "IGNORE_CUSTOM_TEMPLATE" => "N",
            "USE_EXTENDED_ERRORS" => "Y",
            "SEF_MODE" => "N",
            "CACHE_TYPE" => "A",
          "AJAX_MODE" => "Y",
          "AJAX_OPTION_SHADOW" => "N",
          "AJAX_OPTION_JUMP" => "N",
          "AJAX_OPTION_STYLE" => "Y",
          "AJAX_OPTION_HISTORY" => "N",
            "CACHE_TIME" => "3600",
            "LIST_URL" => "",
            "EDIT_URL" => "",
            "SUCCESS_URL" => "",
            "CHAIN_ITEM_TEXT" => "",
            "CHAIN_ITEM_LINK" => "",
            "VARIABLE_ALIASES" => array(
              "WEB_FORM_ID" => "WEB_FORM_ID",
              "RESULT_ID" => "RESULT_ID",
            )
          )
        );?>
      </div>
    </div>
  </div>
</div>

 <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
       <?Asset::getInstance()->addJs('https://api-maps.yandex.ru/2.1/?lang=ru_RU');?>
<?$APPLICATION->AddHeadString('<script type="text/javascript">      ymaps.ready(init);      function init() {          var map = new ymaps.Map("map", {              center: [54.209107, 37.620142],              zoom: 15, controls: ["largeMapDefaultSet"] });          var placemark = new ymaps.Placemark([54.209107, 37.620142], {              balloonContentBody: "г. Тула, ул. Октябрьская, 30",               placemarkName: "корпус 6, офис 407"          });          map.geoObjects.add(placemark);      }
    </script>');?>

      <?Asset::getInstance()->addJs('https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js');?>
      <?Asset::getInstance()->addJs(ASSETS_DIR.'/js/bootstrap.min.js');?>
      <?Asset::getInstance()->addJs(ASSETS_DIR.'/slick/slick.min.js');?>
      <?Asset::getInstance()->addJs(ASSETS_DIR.'/js/main.js');?>
 
<?php
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2024 Bitrix
 */

use Bitrix\Main\Web\Json;

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?
if(!empty($arParams["~AUTH_RESULT"]["MESSAGE"])):
	$text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
?>
	<div class="alert <?=($arParams["~AUTH_RESULT"]["TYPE"] == "OK"? "alert-success":"alert-danger")?>"><?=nl2br($text)?></div>
<?endif?>

<?if($arResult["SHOW_EMAIL_SENT_CONFIRMATION"]):?>
	<div class="alert alert-success"><?echo GetMessage("AUTH_EMAIL_SENT")?></div>
<?endif?>

<?if(!$arResult["SHOW_EMAIL_SENT_CONFIRMATION"] && $arResult["USE_EMAIL_CONFIRMATION"] === "Y"):?>
	<div class="alert alert-warning"><?echo GetMessage("AUTH_EMAIL_WILL_BE_SENT")?></div>
<?endif?>

<form method="post" action="<?=$arResult["AUTH_URL"]?>" class="auth-form" id="registration-form">
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="REGISTRATION" />
	<div class="inputs-container">
	  <input
		class="form-input"
		placeholder="Имя"
		type="text"
		name="USER_NAME"
		value="<?=$arResult['USER_NAME']?>"
		required
	  />
	  <input
		class="form-input"
		placeholder="Фамилия"
		type="text"
		name="USER_LAST_NAME"
		value="<?=$arResult["USER_LAST_NAME"]?>"
		required
	  />
	  <input
		class="form-input"
		placeholder="Email"
		type="email"
		name="USER_LOGIN" value="<?=$arResult["USER_LOGIN"]?>"
		required
	  />

	  <div class="switch-container">
		<label class="form-switch">
		  <input type="checkbox" name="ANONYMITY" value="1"/>
		  <span class="form-switch__slider"></span>
		</label>
		<div>
		  <span>Анонимность</span>
		  <span>
			Ваше имя будет скрыто, и вы сможете делать пожертвования
			анонимно.
		  </span>
		</div>
	  </div>
	  <div class="divider" style="margin: 16px 0"></div>
	  <div class="checkbox">
		<input type="checkbox" id="check-offer" name="check-offer" required />

		<label for="check-offer"
		  >Согласен с
		  <a
			target="_blank"
			href="/terms/"
			style="text-decoration: underline; cursor: pointer"
			>политикой конфиденциальности</a
		  ></label
		>
	  </div>
	</div>

	<button
	  type="submit"
	  class="button auth-button"
	  style="margin-top: 16px"
	>
	  Зарегистрироваться
	</button>
</form>
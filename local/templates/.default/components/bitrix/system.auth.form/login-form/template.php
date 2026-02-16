<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

CJSCore::Init();
?>

<div class="container login-form">
	<h1 class="login-form__title">Вход в личный кабинет</h1>
	<div class="login-form__content-wrapper ">
		<section class="login-form__form-section">

<?
if ($arResult['SHOW_ERRORS'] === 'Y' && $arResult['ERROR'] && !empty($arResult['ERROR_MESSAGE']))
{
	ShowMessage($arResult['ERROR_MESSAGE']);
}
?>

<?if($arResult["FORM_TYPE"] == "login"):?>

<form id="loginForm" class="login-form__form" name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<?if($arResult["BACKURL"] <> ''):?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?endif?>
<?foreach ($arResult["POST"] as $key => $value):?>
	<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
<?endforeach?>
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="AUTH" />

	<div class="">
		<input type="text" class="form-control" name="USER_LOGIN" id="loginLogin" maxlength="50" value="" placeholder="Логин" required="required" />
		<script>
			BX.ready(function() {
				var loginCookie = BX.getCookie("<?=CUtil::JSEscape($arResult["~LOGIN_COOKIE_NAME"])?>");
				if (loginCookie)
				{
					var form = document.forms["system_auth_form<?=$arResult["RND"]?>"];
					var loginInput = form.elements["USER_LOGIN"];
					loginInput.value = loginCookie;
				}
			});
		</script>
	</div>
	<div class="mt-4">
<?if($arResult["SECURE_AUTH"]):?>
		<span class="bx-auth-secure" id="bx_auth_secure<?=$arResult["RND"]?>" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
			<div class="bx-auth-secure-icon"></div>
		</span>
		<noscript>
		<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
			<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
		</span>
		</noscript>
<script>
document.getElementById('bx_auth_secure<?=$arResult["RND"]?>').style.display = 'inline-block';
</script>
<?endif?>
		<input type="password" class="form-control" name="USER_PASSWORD" id="loginPassword" maxlength="255" autocomplete="off" placeholder="Пароль" required="required" />
	</div>

<?if ($arResult["CAPTCHA_CODE"]):?>
	<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
	<div class="mt-4">
		<?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:<br />
		<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /><br /><br />
		<input type="text" name="captcha_word" maxlength="50" value="" />
	</div>
<?endif?>


	<div class="mt-4 d-flex justify-content-end">
		<a class="login-form__recover-password-link" onclick="window.location='<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>'" rel="nofollow">Забыли пароль?</a>
	</div>


	<div class="login-form__button-wrapper">
		<button type="submit" class="btn btn-primary w-100" name="Login">Войти</button>
	</div>

<?if($arResult["NEW_USER_REGISTRATION"] == "Y"):?>
	<noindex>
		<div class="mt-3 text-center">
			<a href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_REGISTER")?></a>
		</div>
	</noindex>
<?endif?>


</form>


		</section>
	</div>
</div>
<?endif?>
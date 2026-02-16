<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
    die();
}

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

//one css for all system.auth.* forms
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");
?>
<div class="container login-form">
<?
if(!empty($arParams["~AUTH_RESULT"]["MESSAGE"])):
    $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
    ?>
    <div class="alert <?=($arParams["~AUTH_RESULT"]["TYPE"] == "OK"? "alert-success":"alert-danger")?>"><?=nl2br(htmlspecialcharsbx($text))?></div>
<?endif?>

<h1 class="login-form__title">Забыли пароль?</h1>

<div class="login-form__content-wrapper">

<p class="text"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></p>

<section class="login-form__form-section">

<form name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>"  class="login-form__form" id="loginForm">
    <?if($arResult["BACKURL"] <> ''):?>
        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
    <?endif?>
    <input type="hidden" name="AUTH_FORM" value="Y">
    <input type="hidden" name="TYPE" value="SEND_PWD">
    <div class="">
        <input type="email" name="USER_EMAIL" placeholder="Email" class="form-control" maxlength="255" data-error-position="right" required
                value="<?= $arResult["USER_EMAIL"] ?>"/>
    </div>


    <?if($arResult["PHONE_REGISTRATION"]):?>
        <div class="bx-authform-formgroup-container">
            <div class="bx-authform-label-container"><?echo GetMessage("forgot_pass_phone_number")?></div>
            <div class="bx-authform-input-container">
                <input type="text" name="USER_PHONE_NUMBER" maxlength="255" value="<?=$arResult["USER_PHONE_NUMBER"]?>" />
            </div>
            <div class="bx-authform-note-container"><?echo GetMessage("forgot_pass_phone_number_note")?></div>
        </div>
    <?endif?>

    <?if ($arResult["USE_CAPTCHA"]):?>
        <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />

        <div class="bx-authform-formgroup-container">
            <div class="bx-authform-label-container"><?echo GetMessage("system_auth_captcha")?></div>
            <div class="bx-captcha"><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /></div>
            <div class="bx-authform-input-container">
                <input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off"/>
            </div>
        </div>

    <?endif?>
    <div class="login-form__button-wrapper">
        <button type="submit" class="btn btn-primary w-100" name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>">Отправить ссылку</button>
    </div>
</form>

</section>
</div>
</div>

<script>
    document.form_auth.USER_EMAIL.focus();
</script>

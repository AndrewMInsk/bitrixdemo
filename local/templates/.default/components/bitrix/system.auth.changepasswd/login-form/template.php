<?php

use Bitrix\Main\Web\Json;

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

if($arResult["PHONE_REGISTRATION"])
{
    CJSCore::Init('phone_auth');
}
?>


<?if($arResult["SHOW_FORM"]):?>

<div class="container login-form">

    <p class="text"><?=GetMessage("AUTH_FORGOT_PASSWORD_3")?></p>

    <h1 class="login-form__title">Изменение пароля</h1>

    <div class="login-form__content-wrapper">

    <section class="login-form__form-section">

    <?if (!empty($arParams["~AUTH_RESULT"]["MESSAGE"])):
        $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
        ?>
        <div class="alert <?= ($arParams["~AUTH_RESULT"]["TYPE"] == "OK" ? "alert-success" : "alert-danger") ?>"><?= nl2br(htmlspecialcharsbx($text)) ?></div>
    <? endif ?>
    <form method="post" action="<?=$arResult["AUTH_URL"]?>" name="form_auth" class="login-form__form" id="loginForm">
        <?if ($arResult["BACKURL"] <> ''): ?>
            <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
        <? endif ?>
        <input type="hidden" name="AUTH_FORM" value="Y">
        <input type="hidden" name="TYPE" value="CHANGE_PWD">

        <?if($arResult["PHONE_REGISTRATION"]):?>
            <div class="bx-authform-formgroup-container">
                <div class="bx-authform-label-container"><?echo GetMessage("change_pass_phone_number")?></div>
                <div class="bx-authform-input-container">
                    <input type="text" value="<?=htmlspecialcharsbx($arResult["USER_PHONE_NUMBER"])?>" disabled="disabled" />
                    <input type="hidden" name="USER_PHONE_NUMBER" value="<?=htmlspecialcharsbx($arResult["USER_PHONE_NUMBER"])?>" />
                </div>
            </div>
            <div class="bx-authform-formgroup-container">
                <div class="bx-authform-input-container">
                    <input type="text" name="USER_CHECKWORD" maxlength="255" value="<?=$arResult["USER_CHECKWORD"]?>" autocomplete="off" />
                </div>
            </div>
        <?else:?>

            <input type="hidden" name="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" />

            <?
            if($arResult["USE_PASSWORD"]):
                ?>
                <div class="bx-authform-formgroup-container">
                    <div class="bx-authform-label-container"><?echo GetMessage("system_change_pass_current_pass")?></div>
                    <div class="bx-authform-input-container1">
                        <?if($arResult["SECURE_AUTH"]):?>
                            <div class="bx-authform-psw-protected" id="bx_auth_secure_pass" style="display:none"><div class="bx-authform-psw-protected-desc"><span></span><?echo GetMessage("AUTH_SECURE_NOTE")?></div></div>

                            <script>
                                document.getElementById('bx_auth_secure_pass').style.display = '';
                            </script>
                        <?endif?>
                        <input placeholder="Текущий пароль" class="form-control" type="password" style="" name="USER_CURRENT_PASSWORD" maxlength="255" value="<?=$arResult["USER_CURRENT_PASSWORD"]?>" autocomplete="new-password" />
                    </div>
                </div>
            <?
            else:
                ?>
                <input type="hidden" name="USER_CHECKWORD" maxlength="255" value="<?=$arResult["USER_CHECKWORD"]?>" autocomplete="off" />


            <?
            endif;
            ?>
        <?endif?>


        <?if($arResult["SECURE_AUTH"]):?>


            <script>
                document.getElementById('bx_auth_secure').style.display = '';
            </script>
        <?endif?>
        <div class="inputs-container">
            <div class="form-password">
                <input name="USER_PASSWORD" type="password" maxlength="255" class="form-control" required="" placeholder="Новый пароль" value="<?=$arResult["USER_PASSWORD"]?>" autocomplete="new-password" >
                <span class="form-password__eye js-show-password"></span>
            </div>




            <?if($arResult["SECURE_AUTH"]):?>
                <div class="bx-authform-psw-protected" id="bx_auth_secure_conf" style="display:none"><div class="bx-authform-psw-protected-desc"><span></span><?echo GetMessage("AUTH_SECURE_NOTE")?></div></div>

                <script>
                    document.getElementById('bx_auth_secure_conf').style.display = '';
                </script>
            <?endif?>
            <div class="form-password">
                <input  name="USER_CONFIRM_PASSWORD" type="password" class="form-control" required="" placeholder="Повторите пароль" maxlength="255" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" autocomplete="new-password">
                <span class="form-password__eye js-show-password"></span>
            </div>
        </div>


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

            <button type="submit" class="btn btn-primary w-100" name="change_pwd"  >Сменить пароль</button>

        </div>

    </form>

    </section>

    </div>

    </div>

    <script>
        document.form_auth.USER_CHECKWORD.focus();
    </script>

    <?if($arResult["PHONE_REGISTRATION"]):?>

        <script>
            new BX.PhoneAuth({
                containerId: 'bx_chpass_resend',
                errorContainerId: 'bx_chpass_error',
                interval: <?=$arResult["PHONE_CODE_RESEND_INTERVAL"]?>,
                data:
                    <?= Json::encode([
                        'signedData' => $arResult["SIGNED_DATA"]
                    ]) ?>,
                onError:
                    function(response)
                    {
                        var errorNode = BX('bx_chpass_error');
                        errorNode.innerHTML = '';
                        for(var i = 0; i < response.errors.length; i++)
                        {
                            errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br />';
                        }
                        errorNode.style.display = '';
                    }
            });
        </script>

        <div class="alert alert-danger" id="bx_chpass_error" style="display:none"></div>

        <div id="bx_chpass_resend"></div>

    <?endif?>

<?else:?>
    <p>Пароль успешно изменен</p>
    <?=GetMessage("password_changed")?>
<?endif;?>

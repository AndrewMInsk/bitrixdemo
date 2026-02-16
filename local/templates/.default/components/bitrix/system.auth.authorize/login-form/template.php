<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 */

//one css for all system.auth.* forms
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");
?>
<?if(!empty($arParams["~AUTH_RESULT"]["MESSAGE"])):
	$text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
?>
	<div class="alert alert-danger"><?=nl2br(htmlspecialcharsbx($text))?></div>
<?endif?>

<?if (!empty($arResult['ERROR_MESSAGE'])):
	$text = str_replace(array("<br>", "<br />"), "\n", $arResult['ERROR_MESSAGE']);
?>
	<div class="alert alert-danger"><?=nl2br(htmlspecialcharsbx($text))?></div>
<?endif?>
<div class="container login-form">
    <h1 class="login-form__title">Вход в личный кабинет</h1>
    <div class="login-form__content-wrapper ">
      <section class="login-form__form-section">
        <form id="loginForm" name="form_auth" class="login-form__form" method="POST" action="<?=$arResult["AUTH_URL"]?>">
<input type="hidden" name="AUTH_FORM" value="Y" />
<input type="hidden" name="TYPE" value="AUTH" />
          <div class="">
            <input type="text" class="form-control" name="USER_LOGIN" id="loginLogin" placeholder="Логин" required="required"  value="<?=$arResult["LAST_LOGIN"]?>"/>
          </div>
          <div class="mt-4">
              <div class="input-group password-field-wrapper">
                  <input type="password" class="form-control password-input" autocomplete="current-password" aria-describedby="togglePassword"
                         name="USER_PASSWORD" id="loginPassword" placeholder="Пароль" required="required"/>
                  <button class="btn btn-outline-secondary toggle-password" type="button" id="togglePassword" aria-label="Показать пароль">
                      <i class="bi bi-eye"></i>
                  </button>
              </div>

          </div>
          <div class="mt-4 d-flex justify-content-end">
            <a class="login-form__recover-password-link" href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>">Забыли пароль?</a>
          </div>
          <div class="login-form__button-wrapper">
            <button type="submit" class="btn btn-primary w-100">Войти</button>
          </div>
        </form>
      </section>
    </div>
  </div>

<script>
<?if ($arResult["LAST_LOGIN"] <> ''):?>
try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
<?else:?>
try{document.form_auth.USER_LOGIN.focus();}catch(e){}
<?endif?>
</script>

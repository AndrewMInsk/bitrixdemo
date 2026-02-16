<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <? if ($arResult["isFormNote"] === "Y"): ?>
                <div class="alert alert-success alert-dismissible  show" role="alert">
                    <h4 class="alert-heading">Спасибо!</h4>
                    <p class="mb-0">Ваша заявка успешно принята.</p>
                </div>
            <? else: ?>
                <div class="card shadow-sm mb-4">
                 
                    <div class="card-body">
                        <?=$arResult["FORM_HEADER"]?>
                        <input type="hidden" name="web_form_submit" value="Y">
                        
                        <? if ($arResult["isFormErrors"] === "Y"): ?>
                            <div class="alert alert-danger alert-dismissible  show" role="alert">
                                <?=$arResult["FORM_ERRORS_TEXT"]?>
                            </div>
                        <? endif; ?>
                        <br>
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <?=$arResult["QUESTIONS"]['NAME']['CAPTION']?>
                                <? if ($arResult["QUESTIONS"]['NAME']['REQUIRED'] === 'Y'): ?>
                                    <span class="text-danger">*</span>
                                <? endif; ?>
                            </label>
                            <?=$arResult["QUESTIONS"]['NAME']['HTML_CODE']?>
                        </div>
                        <br>
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <?=$arResult["QUESTIONS"]['PHONE']['CAPTION']?>
                                <? if ($arResult["QUESTIONS"]['PHONE']['REQUIRED'] === 'Y'): ?>
                                    <span class="text-danger">*</span>
                                <? endif; ?>
                            </label>
                            <?=$arResult["QUESTIONS"]['PHONE']['HTML_CODE']?>
                        </div>
                        <br>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <?=$arResult["arForm"]["BUTTON"]?>
                            </button>
                        </div>
                        <?$arResult['funcGetInputHtml']($arResult["QUESTIONS"]['NAME'], $arResult['arrVALUES'])?>
                        <?=$arResult["FORM_FOOTER"]?>
                    </div>
                </div>
            <? endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('.card-body form');
    if (form) {
        var inputs = form.querySelectorAll('input[type="text"], input[type="tel"], input[type="email"], textarea');
        inputs.forEach(function(input) {
            input.classList.add('form-control');
        });
    }
});
</script>

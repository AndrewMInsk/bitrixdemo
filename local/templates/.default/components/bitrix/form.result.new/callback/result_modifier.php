<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arResult["FORM_HEADER"] = str_replace('<form', '<form class="faq-form"', $arResult["FORM_HEADER"]);
$arResult['funcGetInputHtml'] = function($question, $arrVALUES) {
    $id = $question['STRUCTURE'][0]['ID'];
    $type = $question['STRUCTURE'][0]['FIELD_TYPE'];
    $name = "form_{$type}_{$id}";
    $value = isset($arrVALUES[$name]) ? htmlentities($arrVALUES[$name]) : '';
    $required = $question['REQUIRED'] === 'Y' ? 'required' : '';
    $class = ' ' . $question['STRUCTURE'][0]['FIELD_PARAM'];
     
    switch ($type)
    {
        case 'textarea':
            $input = "<textarea class=\"form-message {$class}\" name=\"{$name}\" {$required}>{$value}</textarea>";
            break;
         
        // case 'text':
        default:
            $input = "<input class=\"tes88  call__form-input {$class}\" type=\"text\" name=\"{$name}\" value=\"{$value}\" {$required}>";
            break;
    }
     
    return $input;
};
?>

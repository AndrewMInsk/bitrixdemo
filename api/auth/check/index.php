<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: application/json; charset=' . LANG_CHARSET);
$result = apiAuthCheck();
echo \Bitrix\Main\Web\Json::encode($result);

<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_after.php");
header('Content-Type: application/json; charset=' . LANG_CHARSET);
$result = apiAuthLogin();
echo \Bitrix\Main\Web\Json::encode($result);

<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arComponentDescription = array(
    "NAME" => GetMessage("MENU_TOP_NAME"),
    "DESCRIPTION" => GetMessage("MENU_TOP_DESC"),
    "ICON" => "/images/menu.gif",
    "SORT" => 10,
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "utility",
    ),
);
?>
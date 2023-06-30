<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var array $arParams
 * @var array $arResult
 * @var array $arCurSection
 */

$APPLICATION->IncludeComponent(
    "derevyanko:catalog.list",
    ".default",
    array(
        "COMPONENT_TEMPLATE" => ".default",
        "BRAND" => $arResult['VARIABLES']['BRAND'],
        "MODEL" => $arResult['VARIABLES']['MODEL'],
        "BRAND_URL" => $arResult['FOLDER'] . $arResult['URL_TEMPLATES']['brand'],
        "MODEL_URL" => $arResult['FOLDER'] . $arResult['URL_TEMPLATES']['model'],
        "NOTEBOOK_URL" => $arResult['FOLDER'] . $arResult['URL_TEMPLATES']['notebook'],
        "BRAND_VAR" => "#BRAND#",
        "MODEL_VAR" => "#MODEL#",
        "NOTEBOOK_VAR" => "#NOTEBOOK#"
    ),
    $component
);
<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var array $arParams
 * @var array $arResult
 * @var array $arCurSection
 */

$APPLICATION->IncludeComponent(
    "derevyanko:catalog.detail",
    "",
    Array(
        "NOTEBOOK" => $arResult['VARIABLES']['NOTEBOOK']
    ),
    $component
);?>
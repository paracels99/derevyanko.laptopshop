<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */

/** @var CBitrixComponent $component */

use Bitrix\Main\Localization\Loc;

\Bitrix\Main\UI\Extension::load("ui.bootstrap4");

echo '<div class="container">';

if ($arParams['BRAND'] > 0 || $arParams['MODEL'] > 0) {
    echo '<h2>' . Loc::getMessage('COMPONENT_CATALOG_LIST_SUBTITLE') . '</h2><p>';
    if ($arParams['BRAND'] > 0) {
        echo '<b>' . Loc::getMessage(
                'COMPONENT_CATALOG_LIST_MANUFACTURER_ID',
                ['#ID#' => $arResult['MANUFACTURER']['ID'],'#NAME#' => $arResult['MANUFACTURER']['NAME']]
            ) . '</b><br>';
    }
    if ($arParams['MODEL'] > 0) {
        echo '<b>' . Loc::getMessage(
                'COMPONENT_CATALOG_LIST_MODEL_ID',
                ['#ID#' => $arResult['MODEL']['ID'],'#NAME#' => $arResult['MODEL']['NAME']]
            ) . '</b><br>';
    }
    if ($arParams['BRAND'] > 0 && $arParams['MODEL'] > 0) {
        echo '<i>' . Loc::getMessage('COMPONENT_CATALOG_LIST_NOTEBOOKS') . '</i><br>';
        $arList = [
            'NOTEBOOK' => Loc::getMessage('ENTITY_LIST_NOTEBOOK')
        ];
    } elseif ($arParams['BRAND'] > 0) {
        echo '<i>' . Loc::getMessage('COMPONENT_CATALOG_LIST_MODELS') . '</i><br>';
        $arList = [
            'MODEL' => Loc::getMessage('ENTITY_LIST_MODEL'),
            'NOTEBOOK' => Loc::getMessage('ENTITY_LIST_NOTEBOOK')
        ];
    } else {
        echo '<i>' . Loc::getMessage('COMPONENT_CATALOG_LIST_BRANDS') . '</i><br>';
        $arList = [
            'MANUFACTURER' => Loc::getMessage('ENTITY_LIST_MANUFACTURER'),
            'MODEL' => Loc::getMessage('ENTITY_LIST_MODEL'),
            'NOTEBOOK' => Loc::getMessage('ENTITY_LIST_NOTEBOOK')
        ];
    }
    echo '</p>';
} else {
    echo '<h2>' . Loc::getMessage('COMPONENT_CATALOG_LIST_SUBTITLE_CLEAR') . '</h2>';
    $arList = [
        'MANUFACTURER' => Loc::getMessage('ENTITY_LIST_MANUFACTURER'),
        'MODEL' => Loc::getMessage('ENTITY_LIST_MODEL'),
        'NOTEBOOK' => Loc::getMessage('ENTITY_LIST_NOTEBOOK')
    ];
}

$APPLICATION->IncludeComponent(
    'bitrix:main.ui.filter',
    '',
    [
        'FILTER_ID' => $arResult['FILTER_ID'],
        'GRID_ID' => $arResult['GRID_ID'],
        'FILTER' => [
            [
                'id' => 'ENTITY',
                'name' => Loc::getMessage('ENTITY_LIST_LABEL'),
                'type' => 'list',
                'items' => $arList,
                'default' => true
            ],
        ],
        'ENABLE_LABEL' => false,
        'FILTER_PRESETS' => [],
        'ENABLE_FIELDS_SEARCH' => false,
        'ENABLE_LIVE_SEARCH' => false
    ],
    $this->getComponent()
);

$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '',
    [
        'GRID_ID' => $arResult['GRID_ID'],
        'COLUMNS' => $arResult['COLUMNS'],
        'ROWS' => $arResult['ROWS'],
        'SHOW_ROW_CHECKBOXES' => false,
        'NAV_OBJECT' => $arResult['NAV'],
        'TOTAL_ROWS_COUNT' => $arResult['TOTAL_ROWS_COUNT'],
        'AJAX_MODE' => 'Y',
        'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
        'PAGE_SIZES' => $arResult['PAGE_SIZES'],
        'AJAX_OPTION_JUMP' => 'N',
        'SHOW_CHECK_ALL_CHECKBOXES' => false,
        'SHOW_ROW_ACTIONS_MENU' => false,
        'SHOW_GRID_SETTINGS_MENU' => true,
        'SHOW_NAVIGATION_PANEL' => true,
        'SHOW_PAGINATION' => true,
        'SHOW_SELECTED_COUNTER' => false,
        'SHOW_TOTAL_COUNTER' => true,
        'SHOW_PAGESIZE' => true,
        'SHOW_ACTION_PANEL' => true,
        'ALLOW_COLUMNS_SORT' => true,
        'ALLOW_COLUMNS_RESIZE' => true,
        'ALLOW_HORIZONTAL_SCROLL' => true,
        'ALLOW_SORT' => true,
        'ALLOW_PIN_HEADER' => false,
        'AJAX_OPTION_HISTORY' => 'N'
    ],
    $this->getComponent()
);

echo '</div>';

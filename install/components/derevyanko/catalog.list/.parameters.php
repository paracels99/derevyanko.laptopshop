<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

$arComponentParameters = [
    'GROUPS' => [],
    'PARAMETERS' => [
        'BRAND' => [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('COMPONENT_CATALOG_LIST_BRAND'),
            'TYPE' => 'STRING'
        ],
        'MODEL' => [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('COMPONENT_CATALOG_LIST_MODEL'),
            'TYPE' => 'STRING'
        ],
        'BRAND_URL' => [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('COMPONENT_CATALOG_LIST_BRAND_URL'),
            'TYPE' => 'STRING',
            'DEFAULT' => '/#BRAND#/'
        ],
        'MODEL_URL' => [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('COMPONENT_CATALOG_LIST_MODEL_URL'),
            'TYPE' => 'STRING',
            'DEFAULT' => '/#BRAND#/#MODEL#/'
        ],
        'NOTEBOOK_URL' => [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('COMPONENT_CATALOG_LIST_NOTEBOOK_URL'),
            'TYPE' => 'STRING',
            'DEFAULT' => '/detail/#NOTEBOOK#/'
        ],
        'BRAND_VAR' => [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('COMPONENT_CATALOG_LIST_BRAND_VAR'),
            'TYPE' => 'STRING',
            'DEFAULT' => '#BRAND#'
        ],
        'MODEL_VAR' => [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('COMPONENT_CATALOG_LIST_MODEL_VAR'),
            'TYPE' => 'STRING',
            'DEFAULT' => '#MODEL#'
        ],
        'NOTEBOOK_VAR' => [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('COMPONENT_CATALOG_LIST_NOTEBOOK_VAR'),
            'TYPE' => 'STRING',
            'DEFAULT' => '#NOTEBOOK#'
        ]
    ]
];
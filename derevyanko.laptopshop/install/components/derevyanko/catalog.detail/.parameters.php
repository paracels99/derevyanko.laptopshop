<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

$arComponentParameters = [
    'GROUPS' => [],
    'PARAMETERS' => [
        'NOTEBOOK' => [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('COMPONENT_CATALOG_DETAIL_NOTEBOOK'),
            'TYPE' => 'STRING',
            'DEFAULT' => '',
            'VALUES' => ''
        ]
    ]
];
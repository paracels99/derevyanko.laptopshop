<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

$arComponentParameters = [
    'GROUPS' => [],
    'PARAMETERS' => [
        'VARIABLE_ALIASES' => [
            'BRAND' => [
                'NAME' => Loc::getMessage('CP_BC_VARIABLE_ALIASES_BRAND')
            ],
            'MODEL' => [
                'NAME' => Loc::getMessage('CP_BC_VARIABLE_ALIASES_MODEL')
            ],
            'NOTEBOOK' => [
                'NAME' => Loc::getMessage('CP_BC_VARIABLE_ALIASES_NOTEBOOK')
            ],
        ],
        'SEF_MODE' => [
            'brands' => [
                'NAME' => Loc::getMessage('BRANDS_PAGE'),
                'DEFAULT' => '',
                'VARIABLES' => []
            ],
            'brand' => [
                'NAME' => Loc::getMessage('BRAND_PAGE'),
                'DEFAULT' => '#BRAND#/',
                'VARIABLES' => ['BRAND']
            ],
            'model' => [
                'NAME' => Loc::getMessage('MODEL_PAGE'),
                'DEFAULT' => '#BRAND#/#MODEL#/',
                'VARIABLES' => ['BRAND', 'MODEL']
            ],
            'notebook' => [
                'NAME' => Loc::getMessage('NOTEBOOK_PAGE'),
                'DEFAULT' => 'detail/#NOTEBOOK#/',
                'VARIABLES' => ['NOTEBOOK']
            ]
        ]
    ]
];

if (($arCurrentValues['SEF_MODE'] ?? 'N') === 'Y') {
    $arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"] = [];
    $arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"]["BRAND"] = [
        "NAME" => Loc::getMessage("CP_BC_VARIABLE_ALIASES_BRAND"),
        "TEMPLATE" => "#BRAND#"
    ];
    $arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"]["MODEL"] = [
        "NAME" => Loc::getMessage("CP_BC_VARIABLE_ALIASES_MODEL"),
        "TEMPLATE" => "#MODEL#"
    ];
    $arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"]["NOTEBOOK"] = [
        "NAME" => Loc::getMessage("CP_BC_VARIABLE_ALIASES_NOTEBOOK"),
        "TEMPLATE" => "#NOTEBOOK#"
    ];
}
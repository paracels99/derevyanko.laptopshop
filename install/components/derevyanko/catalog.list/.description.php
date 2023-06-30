<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

$arComponentDescription = array(
    'NAME' => Loc::getMessage('DEREVYANKO_CATALOG_LIST_NAME'),
    'DESCRIPTION' => Loc::getMessage('DEREVYANKO_CATALOG_LIST_DESCRIPTION'),
    'SORT' => '20',
    'CACHE_PATH' => 'Y',
    'PATH' => [
        'ID' => 'derevyanko',
        'NAME' => Loc::getMessage('DEREVYANKO_PARENT_SECTION')
    ]
);

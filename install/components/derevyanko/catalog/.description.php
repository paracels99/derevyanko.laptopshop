<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

$arComponentDescription = array(
    'NAME' => Loc::getMessage('DEREVYANKO_CATALOG_NAME'),
    'DESCRIPTION' => Loc::getMessage('DEREVYANKO_CATALOG_DESCRIPTION'),
    'SORT' => '10',
    'CACHE_PATH' => 'Y',
    'PATH' => [
        'ID' => 'derevyanko',
        'NAME' => Loc::getMessage('DEREVYANKO_PARENT_SECTION')
    ],
    'COMPLEX' => 'Y'
);

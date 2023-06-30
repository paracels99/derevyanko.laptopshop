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

$item = $arResult['ITEM'];
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4"><?php echo Loc::getMessage(
                'COMPONENT_CATALOG_DETAIL_TITLE',
                ['#LAPTOP#'=>$item->getName()]) ?></h1>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <h2><?php echo Loc::getMessage('COMPONENT_CATALOG_DETAIL_MAIN') ?></h2>
            <p class="h5"><?php
                echo $arResult['COLUMNS']['MANUFACTURER']
                    . ": <span class='badge bg-secondary'>{$item->getManufacturer()->getName()}</span><br/>"
                    . $arResult['COLUMNS']['MODEL']
                    . ": <span class='badge bg-secondary'>{$item->getModel()->getName()}</span><br/>"
                    . $arResult['COLUMNS']['YEAR']
                    . ": <span class='badge bg-secondary'>{$item->getYear()}</span><br/>"
                    . $arResult['COLUMNS']['PRICE']
                    . ": <span class='badge bg-secondary'>{$item->getPrice()}</span><br/>";
                ?></p>
        </div>
        <div class="col-12 col-sm-6 col-md-8">
            <h2><?php echo Loc::getMessage('COMPONENT_CATALOG_DETAIL_OPTIONS') ?></h2>
            <ul class="list-group">
                <?php foreach ($item->getOption() as $option): ?>
                    <li class="list-group-item"><?php echo $option->getName(); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Main\Loader;

class CDerevyankoCatalogDetail extends CBitrixComponent
{
    protected function checkModules()
    {
        if (!Loader::includeModule('derevyanko.laptopshop'))
            throw new SystemException(Loc::getMessage('MODULE_NOT_INSTALLED'));
    }

    protected function checkRights()
    {
        global $APPLICATION;
        $moduleAccessLevel = $APPLICATION->GetGroupRight('derevyanko.laptopshop');
        if ($moduleAccessLevel < 'R') {
            throw new SystemException(Loc::getMessage('MODULE_NO_ACCESS'));
        }
    }

    public function onPrepareComponentParams($arParams)
    {
        $arParams['NOTEBOOK'] = intval($arParams['NOTEBOOK']);

        return $arParams;
    }

    public function executeComponent()
    {
        try {
            $this->checkModules();
            $this->checkRights();
            $this->getResult();
        } catch (SystemException $e) {
            ShowError($e->getMessage());
            define('ERROR_404', 'Y');
            \CHTTP::setStatus('404 Not Found');
        }
    }

    protected function getResult()
    {
        if ($this->arParams['NOTEBOOK'] > 0) {
            $this->arResult['ITEM'] = Derevyanko\Laptopshop\LaptopTable::getByPrimary(
                $this->arParams['NOTEBOOK'],
                ['select' => ['*', 'MODEL', 'MANUFACTURER', 'OPTION']]
            )->fetchObject();

            $this->arResult['COLUMNS'] = [];
            $arMap = Derevyanko\Laptopshop\LaptopTable::getMap();
            foreach ($arMap as $v) {
                $this->arResult['COLUMNS'][$v->getName()] =  $v->getTitle();
            }

            if (!$this->arResult['ITEM']) {
                throw new SystemException(Loc::getMessage('CATALOG_DETAIL_NOTEBOOK_NOT_FOUND'));
            }
        } else {
            throw new SystemException(Loc::getMessage('CATALOG_DETAIL_MUST_SET_PARAM'));
        }


        $this->IncludeComponentTemplate();
    }
}
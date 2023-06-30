<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Main\Loader;

class CDerevyankoCatalogList extends CBitrixComponent
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
        $arParams['BRAND'] = intval($arParams['BRAND']);
        $arParams['MODEL'] = intval($arParams['MODEL']);
        $arParams['BRAND_URL'] = $arParams['BRAND_URL'] ?? '';
        $arParams['MODEL_URL'] = $arParams['MODEL_URL'] ?? '';
        $arParams['NOTEBOOK_URL'] = $arParams['NOTEBOOK_URL'] ?? '';
        $arParams['BRAND_VAR'] = $arParams['BRAND_VAR'] ?? '';
        $arParams['MODEL_VAR'] = $arParams['MODEL_VAR'] ?? '';
        $arParams['NOTEBOOK_VAR'] = $arParams['NOTEBOOK_VAR'] ?? '';

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

    /**
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     */
    protected function getResult()
    {
        $this->arResult = [
            'ROWS' => [],
            'COLUMNS' => [],
            'FILTER_ID' => 'CATALOG_LIST_FILTER_ID',
            'GRID_ID' => 'CATALOG_LIST_GRID_ID',
            'NAV_ID' => 'CATALOG_LIST_NAV'
        ];
        //
        if ($this->arParams['BRAND'] > 0) {
            $this->arResult['MANUFACTURER'] = Derevyanko\Laptopshop\ManufacturerTable::getByPrimary(
                $this->arParams['BRAND']
            )->fetch();

            if (!$this->arResult['MANUFACTURER']) {
                throw new SystemException(Loc::getMessage('CATALOG_LIST_BRAND_NOT_FOUND'));
            }
        }
        if ($this->arParams['MODEL'] > 0) {
            $this->arResult['MODEL'] = Derevyanko\Laptopshop\ModelTable::getByPrimary(
                $this->arParams['MODEL']
            )->fetch();

            if (!$this->arResult['MODEL']) {
                throw new SystemException(Loc::getMessage('CATALOG_LIST_MODEL_NOT_FOUND'));
            }
        }
        // Grid Options
        $oGrid = new Bitrix\Main\Grid\Options($this->arResult["GRID_ID"]);
        $arOptions = $oGrid->getCurrentOptions();
        if(!isset($arOptions['page_size'])) {
            $oGrid->setPageSize(20); // По умолчанию 20 записей на странице
            $arOptions = $oGrid->getCurrentOptions();
        }
        // Navigation
        $this->arResult['NAV'] = new \Bitrix\Main\UI\PageNavigation($this->arResult['NAV_ID']);
        $this->arResult['NAV']->setPageSize($arOptions['page_size'])
            ->allowAllRecords(false)
            ->initFromUri();
        // Filter
        $arFilter = [];
        $filterOption = new Bitrix\Main\UI\Filter\Options($this->arResult['FILTER_ID']);
        $filterData = $filterOption->getFilter([]);
        // Entity
        if ($this->arParams['BRAND'] > 0 && $this->arParams['MODEL'] > 0) { // model.php
            $arEntities = ['NOTEBOOK'];
        } else if ($this->arParams['BRAND'] > 0) { // brand.php
            $arEntities = ['NOTEBOOK', 'MODEL'];
        } else { // brands.php
            $arEntities = ['MODEL', 'NOTEBOOK', 'MANUFACTURER'];
        }

        if (in_array($filterData['ENTITY'], $arEntities)) {
            $this->arResult['ENTITY'] = $filterData['ENTITY'];
        } else {
            $this->arResult['ENTITY'] = '';
        }

        switch ($this->arResult['ENTITY']) {
            case 'MANUFACTURER':
                $class = Derevyanko\Laptopshop\ManufacturerTable::class;
                $arSelect = ['*'];
                break;

            case 'MODEL':
                $class = Derevyanko\Laptopshop\ModelTable::class;
                $arSelect = ['*', 'MANUFACTURER_NAME' => 'MANUFACTURER.NAME'];
                if ($this->arParams['BRAND'] > 0) {
                    $arFilter['=MANUFACTURER_ID'] = $this->arParams['BRAND'];
                }
                break;

            case 'NOTEBOOK':
                $class = Derevyanko\Laptopshop\LaptopTable::class;
                $arSelect = [
                    '*',
                    'MODEL_NAME' => 'MODEL.NAME',
                    'MANUFACTURER_NAME' => 'MANUFACTURER.NAME',
                    'MANUFACTURER_ID' => 'MANUFACTURER.ID'
                ];
                if ($this->arParams['BRAND'] > 0) {
                    $arFilter['=MODEL.MANUFACTURER_ID'] = $this->arParams['BRAND'];
                }
                if ($this->arParams['MODEL'] > 0) {
                    $arFilter['=MODEL_ID'] = $this->arParams['MODEL'];
                }
                break;

            default:
                $class = false;
                $arSelect = ['*'];
                break;
        }
        //
        if ($class != false) {
            // Columns
            $arColumns = [];
            $arMap = $class::getMap();
            foreach ($arMap as $v) {
                $code = $v->getName();

                if (in_array($code, ['MANUFACTURER_ID', 'MODEL_ID', 'OPTION'])) {
                    continue;
                }

                $this->arResult['COLUMNS'][] = [
                    'id' => $code,
                    'name' => $v->getTitle(),
                    'default' => true,
                    'sort' => (in_array($code, ['ID', 'PRICE', 'YEAR']) ? $code : '') // Сортировка только для полей Цены и Года
                ];
                $arColumns[] = $code;
            }
            // Sort
            $arSort = $oGrid->GetSorting(['sort' => ['ID' => 'ASC']])['sort'];
            $code = array_key_first($arSort);
            if (!in_array($code, $arColumns)) { // Если сортировочной колонки в гриде нет - возвращаемся к ID
                $arSort = ['ID' => 'ASC'];
            }
            // Data
            $query = $class::getList([
                'filter' => $arFilter,
                'offset' => $this->arResult['NAV']->getOffset(),
                'limit' => $this->arResult['NAV']->getLimit(),
                'order' => $arSort,
                'select' => $arSelect
            ]);

            while ($result = $query->fetch()) {
                // Незначительные переименования чтобы данные соответствовали столбцам
                if (isset($result['MANUFACTURER_NAME'])) {
                    $result['MANUFACTURER'] = $this->getUrl(
                        $result['MANUFACTURER_NAME'],
                        $this->arParams['BRAND_URL'],
                        $result['MANUFACTURER_ID']
                    );
                }
                if (isset($result['MODEL_NAME'])) {
                    $result['MODEL'] = $this->getUrl(
                        $result['MODEL_NAME'],
                        $this->arParams['MODEL_URL'],
                        $result['MANUFACTURER_ID'],
                        $result['MODEL_ID']
                    );
                }
                // Генерация ссылок
                switch ($this->arResult['ENTITY']) {
                    case 'MANUFACTURER':
                        $result['NAME'] = $this->getUrl(
                            $result['NAME'],
                            $this->arParams['BRAND_URL'],
                            $result['ID']
                        );
                        break;

                    case 'MODEL':
                        $result['NAME'] = $this->getUrl(
                            $result['NAME'],
                            $this->arParams['MODEL_URL'],
                            $result['MANUFACTURER_ID'],
                            $result['ID']
                        );
                        break;

                    case 'NOTEBOOK':
                        $result['NAME'] = $this->getUrl(
                            $result['NAME'],
                            $this->arParams['NOTEBOOK_URL'],
                            $result['MANUFACTURER_ID'],
                            $result['MODEL_ID'],
                            $result['ID']
                        );
                        break;

                    default: break;
                }
                //
                $this->arResult['ROWS'][] = [
                    'id' => $result['ID'],
                    'columns' => $result
                ];
            }
            // Count
            $this->arResult['TOTAL_ROWS_COUNT'] = $class::getCount($arFilter);
            $this->arResult['NAV']->setRecordCount($this->arResult['TOTAL_ROWS_COUNT']);
            // Page size
            $this->arResult['PAGE_SIZES'] = [
                ['NAME' => '10', 'VALUE' => '10'],
                ['NAME' => '20', 'VALUE' => '20'],
                ['NAME' => '50', 'VALUE' => '50']
            ];
        }
        //
        $this->IncludeComponentTemplate();
    }

    /**
     * @param $name
     * @param $content
     * @param $brand
     * @param $model
     * @param $notebook
     * @return string
     */
    public function getUrl($name, $content, $brand='', $model='', $notebook=''): string
    {
        if (!empty($content)) {
            $url = str_replace(
                [$this->arParams['BRAND_VAR'], $this->arParams['MODEL_VAR'], $this->arParams['NOTEBOOK_VAR'], '"'],
                [$brand, $model, $notebook, "'"],
                $content
            );
            $url = trim($url);

            if (!empty($url)) {
                return "<span onclick='window.location.href=\"{$url}\"' class='link'>{$name}</span>";
            }
        }

        return $name;
    }
}
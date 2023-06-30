<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Main\Loader;
use Bitrix\Main\Application;

class CDerevyankoCatalog extends CBitrixComponent
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
        $arParams['VARIABLE_ALIASES'] ??= [];

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
        }
    }

    protected function getResult()
    {
        $arDefaultUrlTemplates404 = array(
            "brands" => "",
            "brand" => "#BRAND#/",
            "notebook" => "detail/#NOTEBOOK#/",
            "model" => "#BRAND#/#MODEL#/"
        );

        $arDefaultVariableAliases404 = array();

        $arDefaultVariableAliases = array();

        $arComponentVariables = array(
            "BRAND",
            "MODEL",
            "NOTEBOOK"
        );

        $request = Application::getInstance()->getContext()->getRequest();
        $arVariables = array();
        if ($this->arParams["SEF_MODE"] === "Y") {
            $engine = new CComponentEngine($this);
            $arUrlTemplates = CComponentEngine::makeComponentUrlTemplates(
                $arDefaultUrlTemplates404,
                $this->arParams["SEF_URL_TEMPLATES"]
            );
            $arVariableAliases = CComponentEngine::makeComponentVariableAliases(
                $arDefaultVariableAliases404,
                $this->arParams["VARIABLE_ALIASES"]
            );

            $componentPage = $engine->guessComponentPath(
                $this->arParams["SEF_FOLDER"],
                $arUrlTemplates,
                $arVariables
            );

            $b404 = false;
            if (!$componentPage) {
                $componentPage = "brands";
                $b404 = true;
            }

            if ($componentPage == "brand") {
                $b404 |= (intval($arVariables["BRAND"]) . "" !== $arVariables["BRAND"]);
            }

            if ($b404) {
                $folder404 = str_replace("\\", "/", $this->arParams["SEF_FOLDER"]);
                if ($folder404 != "/")
                    $folder404 = "/" . trim($folder404, "/ \t\n\r\0\x0B") . "/";
                if (mb_substr($folder404, -1) == "/")
                    $folder404 .= "index.php";

                if ($folder404 != $request->getRequestedPage()) {
                    define('ERROR_404', 'Y');
                    \CHTTP::setStatus('404 Not Found');
                }
            }

            CComponentEngine::initComponentVariables(
                $componentPage,
                $arComponentVariables,
                $arVariableAliases,
                $arVariables
            );
            $this->arResult = array(
                "FOLDER" => $this->arParams["SEF_FOLDER"],
                "URL_TEMPLATES" => $arUrlTemplates,
                "VARIABLES" => $arVariables,
                "ALIASES" => $arVariableAliases
            );
        } else {
            $arVariableAliases = CComponentEngine::makeComponentVariableAliases(
                $arDefaultVariableAliases,
                $this->arParams["VARIABLE_ALIASES"]
            );
            CComponentEngine::initComponentVariables(
                false,
                $arComponentVariables,
                $arVariableAliases,
                $arVariables
            );

            if (isset($arVariables["NOTEBOOK"]) && intval($arVariables["NOTEBOOK"]) > 0)
                $componentPage = "notebook";
            elseif (isset($arVariables["BRAND"])
                &&
                intval($arVariables["BRAND"]) > 0
                &&
                isset($arVariables["MODEL"])
                &&
                intval($arVariables["MODEL"]) > 0)
                $componentPage = "model";
            elseif (isset($arVariables["BRAND"]) && intval($arVariables["BRAND"]) > 0)
                $componentPage = "brand";
            else
                $componentPage = "brands";

            $currentPage = htmlspecialcharsbx($request->getRequestedPage()) . "?";
            $this->arResult = array(
                "FOLDER" => "",
                "URL_TEMPLATES" => array(
                    "brand" => $currentPage . $arVariableAliases["BRAND"] . "=#BRAND#",
                    "model" => $currentPage . $arVariableAliases["BRAND"] . "=#BRAND#&"
                        . $arVariableAliases["MODEL"] . "=#MODEL#",
                    "notebook" => $currentPage . $arVariableAliases["NOTEBOOK"] . "=#NOTEBOOK#"
                ),
                "VARIABLES" => $arVariables,
                "ALIASES" => $arVariableAliases
            );
        }

        $this->arResult["VARIABLES"]["BRAND"] ??= 0;
        $this->arResult['VARIABLES']['MODEL'] ??= 0;
        $this->arResult['VARIABLES']['NOTEBOOK'] ??= 0;
        $this->IncludeComponentTemplate($componentPage);
    }
}
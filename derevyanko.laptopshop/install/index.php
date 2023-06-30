<?php

use Bitrix\Main\{
    Localization\Loc,
    Application,
    Loader,
    ModuleManager
};
use Derevyanko\Laptopshop\{
    ManufacturerTable,
    ModelTable,
    LaptopTable,
    OptionTable,
    LaptopOptionTable
};

Loc::loadMessages(__FILE__);

class derevyanko_laptopshop extends CModule
{
    public $MODULE_ID;
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;

    public $errors;

    public function __construct()
    {
        $arModuleVersion = array();

        include_once(__DIR__ . "/version.php");

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_ID = str_replace("_", ".", get_class($this));
        $this->MODULE_NAME = Loc::getMessage("LAPTOPSHOP_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("LAPTOPSHOP_DESCRIPTION");
        $this->PARTNER_NAME = Loc::getMessage('LAPTOPSHOP_PARTNER_NAME');
    }

    /**
     * @throws Exception
     */
    public function InstallDB()
    {
        $request = Application::getInstance()->getContext()->getRequest();

        $bDeleteTables = ($request->getQuery("delete_tables") ?? "") == "Y";

        if ($bDeleteTables) {
            // Удаление старых таблиц
            $this->executeSqlFromFile(__DIR__ . '/db/mysql/drop.sql');
        }

        // Создание таблиц
        $this->executeSqlFromFile(__DIR__ . '/db/mysql/create.sql');

        if($this->errors) {
            return false;
        }
        // Создание демо записей
        global $arData;
        $file = __DIR__ . '/data/demo.php';
        if(!file_exists(__DIR__ . '/data/demo.php'))
        {
            $this->errors[] = Loc::getMessage('LAPTOPSHOP_FILE_NOT_FIND', ['#FILE#' => $file]);
            return false;
        }

        ModuleManager::registerModule($this->MODULE_ID);
        Loader::includeModule($this->MODULE_ID);

        require_once $file;
        // Создание производителей
        Derevyanko\Laptopshop\ManufacturerTable::addMulti($arData['MANUFACTURERS']);
        // Создание моделей
        $this->prepareInsertData(
            Derevyanko\Laptopshop\ManufacturerTable::class,
            $arData['MODELS'],
            'MANUFACTURER_ID',
            $arData['MANUFACTURERS_ID']
        );
        Derevyanko\Laptopshop\ModelTable::addMulti($arData['MODELS']);
        // Создание ноутбуков
        $this->prepareInsertData(
            Derevyanko\Laptopshop\ModelTable::class,
            $arData['LAPTOPS'],
            'MODEL_ID',
            $arData['MODELS_ID']
        );
        Derevyanko\Laptopshop\LaptopTable::addMulti($arData['LAPTOPS']);
        // Создание опций
        Derevyanko\Laptopshop\OptionTable::addMulti($arData['OPTIONS']);
        // Создание связей опций и ноутбуков
        $arOptionsId = $arLaptopOptions = [];
        $query = Derevyanko\Laptopshop\OptionTable::getList();
        while ($result = $query->fetch()) {
            $arOptionsId[] = $result['ID'];
        }

        $query = Derevyanko\Laptopshop\LaptopTable::getList();
        while ($result = $query->fetch()) {
            $arLaptopOptionsId = [];
            $cnt = rand(1, 10); // Выбираем случайное кол-во свойств для ноутбука
            while ($cnt > 0) {
                $key = array_rand($arOptionsId); // Выбираем случайное свойство
                $arLaptopOptionsId[] = $arOptionsId[$key];
                $cnt--;
            }

            $arLaptopOptionsId = array_unique($arLaptopOptionsId);
            foreach ($arLaptopOptionsId as $id) {
                $arLaptopOptions[] = ['OPTION_ID' => $id, 'LAPTOP_ID' => $result['ID']];
            }
        }
        Derevyanko\Laptopshop\LaptopOptionTable::addMulti($arLaptopOptions);

        return true;
    }

    public function UnInstallDB($arParams = array())
    {
        $request = Application::getInstance()->getContext()->getRequest();

        $bSaveTables = ($request->getQuery("save_tables") ?? "") == "Y";
        // Удаление старых таблиц
        if (!$bSaveTables) {
            $this->executeSqlFromFile(__DIR__ . '/db/mysql/drop.sql');
        }

        if($this->errors) {
            return false;
        }

        return true;
    }

    public function InstallFiles()
    {
        CopyDirFiles(
            __DIR__ . "/components",
            Application::getDocumentRoot() . "/local/components",
            true,
            true
        );
    }

    public function UnInstallFiles()
    {
        DeleteDirFilesEx("/local/components/derevyanko");
    }

    public function DoInstall()
    {
        global $APPLICATION, $step, $obModule;
        $obModule = $this;
        $step = intval($step);

        if ($step < 2)
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage("LAPTOPSHOP_INSTALL_TITLE", ["#MODULE#" => $this->MODULE_ID]),
                __DIR__ . "/step1.php"
            );
        elseif ($step == 2) {
            if ($this->InstallDB()) {
                $this->InstallFiles();
            }
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage("LAPTOPSHOP_INSTALL_TITLE", ["#MODULE#" => $this->MODULE_ID]),
                __DIR__ . "/step2.php"
            );
        }
    }

    public function DoUninstall()
    {
        global $APPLICATION, $step, $obModule;
        $obModule = $this;
        $step = intval($step);

        if ($step < 2)
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage("LAPTOPSHOP_UNINSTALL_TITLE", ["#MODULE#" => $this->MODULE_ID]),
                __DIR__ . "/unstep1.php"
            );
        elseif ($step == 2) {
            $this->UnInstallDB();
            $this->UnInstallFiles();
            ModuleManager::unRegisterModule($this->MODULE_ID);
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage("LAPTOPSHOP_UNINSTALL_TITLE", ["#MODULE#" => $this->MODULE_ID]),
                __DIR__ . "/unstep2.php"
            );
        }
    }

    /**
     * @param $class
     * @param $arSubject
     * @param $key
     * @param $arSearch
     * @return void
     */
    public function prepareInsertData($class, &$arSubject, $key, $arSearch)
    {
        $arId = [];
        $query = $class::getList();
        while ($result = $query->fetch()) {
            $arId[] = $result['ID'];
        }
        foreach ($arSubject as $k => $v) {
            $arSubject[$k][$key] = str_replace($arSearch, $arId, $v[$key]);
        }
    }

    public function executeSqlFromFile($file)
    {
        $connect = Application::getConnection();
        $sql = file_get_contents($file);
        if ($sql) {
            $connect->executeSqlBatch($sql);
        } else {
            $this->errors[] = Loc::getMessage('LAPTOPSHOP_FILE_NOT_FIND', ['#FILE#' => $file]);
        }
    }
}
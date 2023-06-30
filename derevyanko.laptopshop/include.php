<?php
Bitrix\Main\Loader::registerAutoLoadClasses(
    "derevyanko.laptopshop",
    [
        "Derevyanko\\Laptopshop\\LaptopTable" => "lib/laptop.php",
        "Derevyanko\\Laptopshop\\LaptopOptionTable" => "lib/laptopoption.php",
        "Derevyanko\\Laptopshop\\ManufacturerTable" => "lib/manufacturer.php",
        "Derevyanko\\Laptopshop\\ModelTable" => "lib/model.php",
        "Derevyanko\\Laptopshop\\OptionTable" => "lib/option.php"
    ]
);

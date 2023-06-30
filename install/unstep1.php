<?php

global $APPLICATION;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

global $obModule;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
?>
<form action="<?php echo $request->getRequestedPage() ?>" name="form1">
    <?php echo bitrix_sessid_post() ?>
    <input type="hidden" name="lang" value="<?php echo LANG ?>">
    <input type="hidden" name="id" value="<?php echo $obModule->MODULE_ID; ?>">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="2">
    <table cellpadding="3" cellspacing="0" border="0" width="0%">
        <tr>
            <td>
                <table cellpadding="3" cellspacing="0" border="0">
                    <tr>
                        <td><input type="checkbox" name="save_tables" id="save_tables" value="Y"></td>
                        <td>
                            <p>
                                <label
                                    for="save_tables"><?php echo Loc::getMessage("LAPTOPSHOP_INSTALL_SAVE_TABLES_EXIST") ?></label>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <input type="submit" name="inst" value="<?php echo Loc::getMessage("LAPTOPSHOP_INSTALL_NEXT") ?>">
</form>
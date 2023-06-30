<?php

use Bitrix\Main\Localization\Loc;

global $obModule;

if (!check_bitrix_sessid() || !isset($obModule) || !is_object($obModule)) {
    return;
}

Loc::loadMessages(__FILE__);

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if (!empty($obModule->errors) && is_array($obModule->errors)) {
    CAdminMessage::ShowMessage(array(
        "TYPE" => "ERROR",
        "MESSAGE" => Loc::getMessage("MOD_UNINST_ERR"),
        "DETAILS" => implode("<br>", $obModule->errors),
        "HTML" => true
    ));
} else {
    CAdminMessage::ShowNote(Loc::getMessage("MOD_UNINST_OK"));
}
?>
<form action="<?php echo $request->getRequestedPage() ?>">
    <input type="hidden" name="lang" value="<?php echo LANG ?>">
    <input type="submit" name="" value="<?php echo Loc::getMessage("MOD_BACK") ?>">
</form>

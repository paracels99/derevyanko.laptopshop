<?php
/** @global CMain $APPLICATION */

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

$module_id = 'derevyanko.laptopshop';
$moduleAccessLevel = $APPLICATION->GetGroupRight($module_id);
if ($moduleAccessLevel >= 'R') {
    Loader::includeModule('derevyanko.laptopshop');
    Loc::loadMessages(__FILE__);

    $aTabs = [
        [
            "DIV" => "edit1",
            "TAB" => Loc::getMessage("CO_TAB_RIGHTS"),
            "TITLE" => Loc::getMessage("CO_TAB_RIGHTS_TITLE")
        ]
    ];
    $tabControl = new CAdminTabControl("derevyankoTabControl", $aTabs, true, true);

    if (
        $_SERVER['REQUEST_METHOD'] == "GET"
        && !empty($_GET['RestoreDefaults'])
        && $moduleAccessLevel == "W"
        && check_bitrix_sessid()
    ) {
        $z = CGroup::GetList('id', 'asc', array("ACTIVE" => "Y", "ADMIN" => "N"));
        while ($zr = $z->Fetch())
            $APPLICATION->DelGroupRight($module_id, array($zr["ID"]));

        LocalRedirect($APPLICATION->GetCurPage() . '?lang=' . LANGUAGE_ID . '&mid=' . $module_id);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $moduleAccessLevel == "W" && check_bitrix_sessid()) {
        if (isset($_POST['Update']) && $_POST['Update'] === 'Y') {
            ob_start();
            require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/admin/group_rights.php');
            ob_end_clean();

            LocalRedirect($APPLICATION->GetCurPage() . '?lang=' . LANGUAGE_ID . '&mid=' . $module_id . '&' . $tabControl->ActiveTabParam());
        }
    }


    $tabControl->Begin();
    ?>
    <form method="POST" action="<? echo $APPLICATION->GetCurPage() ?>?lang=<? echo LANGUAGE_ID ?>&mid=<?= $module_id ?>"
          name="laptopshop_settings">
        <? echo bitrix_sessid_post();
        $tabControl->BeginNextTab();

        require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights.php");

        $tabControl->Buttons(); ?>
        <script type="text/javascript">
            function RestoreDefaults() {
                if (confirm('<? echo CUtil::JSEscape(Loc::getMessage("CUR_OPTIONS_BTN_HINT_RESTORE_DEFAULT_WARNING")); ?>'))
                    window.location = "<?echo $APPLICATION->GetCurPage()?>?lang=<? echo LANGUAGE_ID; ?>&mid=<? echo $module_id; ?>&RestoreDefaults=Y&<?=bitrix_sessid_get()?>";
            }
        </script>
        <input type="submit"<?= ($moduleAccessLevel < 'W' ? ' disabled' : ''); ?> name="Update"
               value="<?= Loc::getMessage('CUR_OPTIONS_BTN_SAVE') ?>" class="adm-btn-save"
               title="<?= Loc::getMessage('CUR_OPTIONS_BTN_SAVE_TITLE'); ?>">
        <input type="hidden" name="Update" value="Y">
        <input type="reset" name="reset" value="<?= Loc::getMessage('CUR_OPTIONS_BTN_RESET') ?>"
               title="<?= Loc::getMessage('CUR_OPTIONS_BTN_RESET_TITLE'); ?>">
        <input type="button"<?= ($moduleAccessLevel < 'W' ? ' disabled' : ''); ?>
               title="<?= Loc::getMessage("CUR_OPTIONS_BTN_HINT_RESTORE_DEFAULT") ?>" onclick="RestoreDefaults();"
               value="<?= Loc::getMessage('CUR_OPTIONS_BTN_RESTORE_DEFAULT'); ?>">
    </form>
    <? $tabControl->End();
}
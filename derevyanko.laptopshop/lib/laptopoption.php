<?php

namespace Derevyanko\Laptopshop;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Main\ORM\Fields\IntegerField;

Loc::loadMessages(__FILE__);

/**
 * Class LaptopOptionTable
 *
 * Fields:
 * <ul>
 * <li> OPTION_ID int mandatory
 * <li> LAPTOP_ID int mandatory
 * </ul>
 *
 **/
class LaptopOptionTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_derevyanko_laptopshop_laptop_option';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     * @throws SystemException
     */
    public static function getMap()
    {
        return [
            (new IntegerField('ID',
                []
            ))->configureTitle(Loc::getMessage('LAPTOPSHOP_LAPTOP_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            (new IntegerField('OPTION_ID',
                []
            ))->configureTitle(Loc::getMessage('LAPTOPSHOP_LAPTOP_OPTION_ENTITY_OPTION_ID_FIELD'))
                ->configureRequired(true),
            (new IntegerField('LAPTOP_ID',
                []
            ))->configureTitle(Loc::getMessage('LAPTOPSHOP_LAPTOP_OPTION_ENTITY_LAPTOP_ID_FIELD'))
                ->configureRequired(true)
        ];
    }
}

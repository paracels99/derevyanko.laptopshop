<?php

namespace Derevyanko\Laptopshop;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Main\ORM\Fields\{
    IntegerField,
    StringField,
    Validators\LengthValidator
};

Loc::loadMessages(__FILE__);

/**
 * Class ManufacturerTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> NAME string(255) mandatory
 * </ul>
 *
 **/
class ManufacturerTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_derevyanko_laptopshop_manufacturer';
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
            ))->configureTitle(Loc::getMessage('LAPTOPSHOP_MANUFACTURER_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            (new StringField('NAME',
                [
                    'validation' => [__CLASS__, 'validateName']
                ]
            ))->configureTitle(Loc::getMessage('LAPTOPSHOP_MANUFACTURER_ENTITY_NAME_FIELD'))
                ->configureRequired(true),
        ];
    }

    /**
     * Returns validators for NAME field.
     *
     * @return array
     */
    public static function validateName()
    {
        return [
            new LengthValidator(null, 255),
        ];
    }
}
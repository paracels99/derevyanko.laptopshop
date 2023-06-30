<?php

namespace Derevyanko\Laptopshop;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Main\ORM\Fields\{IntegerField, Relations\ManyToMany, StringField, Validators\LengthValidator};

Loc::loadMessages(__FILE__);

/**
 * Class OptionTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> NAME string(255) mandatory
 * </ul>
 *
 **/

class OptionTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_derevyanko_laptopshop_option';
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
            ))->configureTitle(Loc::getMessage('LAPTOPSHOP_OPTION_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            (new StringField('NAME',
                [
                    'validation' => [__CLASS__, 'validateName']
                ]
            ))->configureTitle(Loc::getMessage('LAPTOPSHOP_OPTION_ENTITY_NAME_FIELD'))
                ->configureRequired(true),
            (new ManyToMany('LAPTOP',
                LaptopTable::class))
                ->configureTitle(Loc::getMessage('LAPTOPSHOP_OPTION_ENTITY_LAPTOP_FIELD'))
                ->configureTableName('b_derevyanko_laptopshop_laptop_option')
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


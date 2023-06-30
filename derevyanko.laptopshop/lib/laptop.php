<?php

namespace Derevyanko\Laptopshop;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;
use Bitrix\Main\ORM\Fields\{IntegerField,
    Relations\ManyToMany,
    Relations\Reference,
    StringField,
    FloatField,
    Validators\LengthValidator};

Loc::loadMessages(__FILE__);

/**
 * Class LaptopTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> NAME string(255) mandatory
 * <li> YEAR int mandatory
 * <li> PRICE double mandatory
 * <li> MODEL_ID int mandatory
 * </ul>
 *
 **/
class LaptopTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_derevyanko_laptopshop_laptop';
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
            (new StringField('NAME',
                [
                    'validation' => [__CLASS__, 'validateName']
                ]
            ))->configureTitle(Loc::getMessage('LAPTOPSHOP_LAPTOP_ENTITY_NAME_FIELD'))
                ->configureRequired(true),
            (new IntegerField('YEAR',
                []
            ))->configureTitle(Loc::getMessage('LAPTOPSHOP_LAPTOP_ENTITY_YEAR_FIELD'))
                ->configureRequired(true),
            (new FloatField('PRICE',
                []
            ))->configureTitle(Loc::getMessage('LAPTOPSHOP_LAPTOP_ENTITY_PRICE_FIELD'))
                ->configureRequired(true),
            (new IntegerField('MODEL_ID',
                []
            ))->configureTitle(Loc::getMessage('LAPTOPSHOP_LAPTOP_ENTITY_MODEL_ID_FIELD'))
                ->configureRequired(true),
            (new Reference('MODEL',
                ModelTable::class,
                Join::on('this.MODEL_ID', 'ref.ID')
            ))->configureTitle(Loc::getMessage('LAPTOPSHOP_LAPTOP_ENTITY_MODEL_FIELD'))
                ->configureJoinType('inner'),
            (new Reference('MANUFACTURER',
                ManufacturerTable::class,
                Join::on('this.MODEL.MANUFACTURER_ID', 'ref.ID')
            ))->configureTitle(Loc::getMessage('LAPTOPSHOP_LAPTOP_ENTITY_MANUFACTURER_FIELD'))
                ->configureJoinType('inner'),
            (new ManyToMany('OPTION',
                OptionTable::class))
                ->configureTitle(Loc::getMessage('LAPTOPSHOP_LAPTOP_ENTITY_OPTION_FIELD'))
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

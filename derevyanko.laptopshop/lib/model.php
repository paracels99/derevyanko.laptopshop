<?php

namespace Derevyanko\Laptopshop;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;
use Bitrix\Main\ORM\Fields\{IntegerField, Relations\Reference, StringField, Validators\LengthValidator};

Loc::loadMessages(__FILE__);

/**
 * Class ModelTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> NAME string(255) mandatory
 * <li> MANUFACTURER_ID int mandatory
 * </ul>
 *
 **/
class ModelTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_derevyanko_laptopshop_model';
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
            ))->configureTitle(Loc::getMessage('LAPTOPSHOP_MODEL_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            (new StringField('NAME',
                [
                    'validation' => [__CLASS__, 'validateName']
                ]
            ))->configureTitle(Loc::getMessage('LAPTOPSHOP_MODEL_ENTITY_NAME_FIELD'))
                ->configureRequired(true),
            (new IntegerField('MANUFACTURER_ID',
                []
            ))->configureTitle(Loc::getMessage('LAPTOPSHOP_MODEL_ENTITY_MANUFACTURER_ID_FIELD'))
                ->configureRequired(true),
            (new Reference('MANUFACTURER',
                ManufacturerTable::class,
                Join::on('this.MANUFACTURER_ID', 'ref.ID')
            ))->configureTitle(Loc::getMessage('LAPTOPSHOP_MODEL_ENTITY_MANUFACTURER_FIELD'))
                ->configureJoinType('inner')
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

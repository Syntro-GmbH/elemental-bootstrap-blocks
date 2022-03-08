<?php

namespace Syntro\ElementalBootstrapBlocks\Extension;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;

/**
 * Extends an element with the option to control the width of
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class SplitRatioExtension extends DataExtension
{
    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'LeftSideWidth' => 'Int',
    ];

    /**
     * Add default values to database
     *  @var array
     */
    private static $defaults = [
        'LeftSideWidth' => '7'
    ];


    /**
     * updateCMSFields
     *
     * @param  FieldList $fields the original fields
     * @return FieldList
     */
    public function updateCMSFields($fields)
    {
        $fields->removeByName([
            'LeftSideWidth'
        ]);
        $widthField = DropdownField::create(
            'LeftSideWidth',
            _t(__CLASS__ . '.FIELDTITLE', 'Columns size ratio'),
            [
                '3' => '3:9',
                '4' => '4:8',
                '5' => '5:7',
                '6' => '6:6',
                '7' => '7:5',
                '8' => '8:4',
                '9' => '9:3'
            ]
        );
        $fields->addFieldToTab(
            'Root.Settings',
            $widthField,
            'Style'
        );
        return $fields;
    }

    /**
     * getLeftWidth - get the width of the left element in the bootstrap grid
     *
     * @param  int $adjust = 0 adjust the size
     * @return int
     */
    public function getLeftWidth($adjust = 0)
    {
        return ($this->getOwner()->LeftSideWidth) + $adjust;
    }

    /**
     * getRightWidth - get the width of the right element in the bootstrap grid
     *
     * @param  int $adjust = 0 adjust the size
     * @return int
     */
    public function getRightWidth($adjust = 0)
    {
        return (12 - $this->getOwner()->LeftSideWidth) + $adjust;
    }
}

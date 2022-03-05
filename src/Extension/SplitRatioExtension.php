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
     * CMS Fields
     * @return FieldList
     */
    public function updateCMSFields($fields)
    {
        $fields->removeByName([
            'LeftSideWidth'
        ]);
        $widthField = DropdownField::create(
            'LeftSideWidth',
            _t(__CLASS__.'.FIELDTITLE', 'Columns size ratio'),
            [
                '5' => '5:7',
                '6' => '6:6',
                '7' => '7:5',
            ]
        );
        $fields->addFieldToTab(
            'Root.Settings',
            $widthField,
            'Style'
        );
        return $fields;
    }

    public function getLeftWidth($adjust = 0)
    {
        return ($this->getOwner()->LeftSideWidth) + $adjust;
    }

    public function getRightWidth($adjust = 0)
    {
        return (12 - $this->getOwner()->LeftSideWidth) + $adjust;
    }
}

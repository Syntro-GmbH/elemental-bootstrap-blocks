<?php

namespace Syntro\ElementalBootstrapBlocks\Extension;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\CheckboxField;

/**
 * Extends an element with the option to display items in a carousel
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class UseCarouselExtension extends DataExtension
{
    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'UseCarousel' => 'Boolean',
    ];

    /**
     * Add default values to database
     *  @var array
     */
    private static $defaults = [
        'UseCarousel' => true
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
            'UseCarousel'
        ]);
        $carouselField = CheckboxField::create(
            'UseCarousel',
            _t(__CLASS__ . '.USECAROUSEL', 'Display as Carousel')
        );
        $carouselField->setDescription(
            _t(__CLASS__ . '.USECAROUSELDESC', 'If enabled, cards that would be displayed in a new row are displayed in a carousel.')
        );
        $fields->insertAfter('Title', $carouselField);
        return $fields;
    }
}

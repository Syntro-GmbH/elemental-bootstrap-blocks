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
     * @config
     * @var array
     */
    private static $db = [
        'UseCarousel' => 'Boolean',
        'UseCarouselAutoplay' => 'Boolean'
    ];

    /**
     * Add default values to database
     * @config
     *  @var array
     */
    private static $defaults = [
        'UseCarousel' => true,
        'UseCarouselAutoplay' => false,
    ];

    /**
     * @config
     */
    private static $disable_carousel_for_styles = [];

    /**
     * updateCMSFields
     *
     * @param  FieldList $fields the original fields
     * @return FieldList
     */
    public function updateCMSFields($fields)
    {
        $fields->removeByName([
            'UseCarousel',
            'UseCarouselAutoplay'
        ]);
        $owner = $this->getOwner();
        $hiddenInStyles = $owner->config()->get('disable_carousel_for_styles');
        if (is_null($hiddenInStyles) || !in_array($owner->Style, $hiddenInStyles)) {
            $carouselField = CheckboxField::create(
                'UseCarousel',
                _t(__CLASS__ . '.USECAROUSEL', 'Display as Carousel')
            );
            $carouselField->setDescription(
                _t(__CLASS__ . '.USECAROUSELDESC', 'If enabled, cards that would be displayed in a new row are displayed in a carousel.')
            );
            $fields->insertAfter('Title', $carouselField);

            $autoplayField = CheckboxField::create(
                'UseCarouselAutoplay',
                _t(__CLASS__ . '.AUTOPLAY', 'Enable Autoplay')
            );
            $autoplayField->setDescription(
                _t(__CLASS__ . '.AUTOPLAYDESC', 'If enabled, the carousel will automatically rotate.')
            );
            $autoplayField->displayIf('UseCarousel')->isChecked();
            $fields->insertAfter('UseCarousel', $autoplayField);
        }
        return $fields;
    }
}

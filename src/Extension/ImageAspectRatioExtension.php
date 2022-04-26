<?php

namespace Syntro\ElementalBootstrapBlocks\Extension;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Core\Config\Configurable;

/**
 * Adds helper fields to an element which allow the user to change
 * the aspect ratio of the rendered image.
 *
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class ImageAspectRatioExtension extends DataExtension
{
    use Configurable;

    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'ImageAspectRatio' => 'Varchar',
        'ImageCropMethod' => 'Enum("fill,pad", "fill")',
        'ImageOrientation' => 'Enum("landscape,portrait", "landscape")',
    ];

    /**
     * Add default values to database
     *  @var array
     */
    private static $defaults = [
        'ImageAspectRatio' => 'original',
        'ImageCropMethod' => 'fill',
        'ImageOrientation' => 'landscape'
    ];

    /**
     * @config
     */
    private static $available_aspect_ratios = [
        '1 x 1' => [1, 1],
        '2 x 1' => [2, 1],
        '3 x 2' => [3, 2],
        '4 x 3' => [4, 3],
        '5 x 4' => [5, 4],
        '7 x 5' => [7, 5],
        '11 x 8.5' => [11, 8.5],
        '16 x 9' => [16, 9],
        '16 x 10' => [16, 10],
    ];

    public function getAspectRatioField()
    {
        $options = [
            'original' => 'Original'
        ];
        foreach ($this->config()->get('available_aspect_ratios') as $key => $value) {
            $options[$key] = $key;
        }
        $options['custom'] = 'Custom';
        $field = DropdownField::create(
            'ImageAspectRatio',
            'Aspect Ratio',
            $options
        );
        return $field;
    }

    /**
     * updateCMSFields
     *
     * @param  FieldList $fields the original fields
     * @return FieldList
     */
    public function updateCMSFields($fields)
    {
        $fields->removeByName([
            'ImageAspectRatio',
            'ImageCropMethod',
            'ImageOrientation',
        ]);

        $fields->addFieldToTab(
            'Root.ImageSettings',
            $this->getAspectRatioField()
        );

        return $fields;
    }
}

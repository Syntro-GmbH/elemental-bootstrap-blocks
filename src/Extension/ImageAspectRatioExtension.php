<?php

namespace Syntro\ElementalBootstrapBlocks\Extension;

use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\ValidationResult;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Core\Config\Configurable;
use UncleCheese\DisplayLogic\Forms\Wrapper;

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
        'ImageAspectCustomHeightRatio' => 'Int',
        'ImageAspectCustomWidthRatio' => 'Int',
    ];

    /**
     * Add default values to database
     *  @var array
     */
    private static $defaults = [
        'ImageAspectRatio' => 'original',
        'ImageCropMethod' => 'fill',
        'ImageOrientation' => 'landscape',
        'ImageAspectCustomHeightRatio' => 1,
        'ImageAspectCustomWidthRatio' => 1
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

    /**
     * getAspectRatioField
     *
     * @return DropdownField
     */
    public function getAspectRatioField()
    {
        $options = [
            'original' => _t(__CLASS__ . '.FieldAspectRatioOptionOriginal', 'Original')
        ];
        foreach ($this->config()->get('available_aspect_ratios') as $key => $value) {
            $options[$key] = $key;
        }
        $options['custom'] = _t(__CLASS__ . '.FieldAspectRatioOptionCustom', 'Custom');
        $field = DropdownField::create(
            'ImageAspectRatio',
            _t(__CLASS__ . '.FieldAspectRatioTitle', 'Aspect Ratio'),
            $options
        );
        return $field;
    }

    /**
     * getCropMethodField
     *
     * @return OptionsetField
     */
    public function getCropMethodField()
    {
        $field = OptionsetField::create(
            'ImageCropMethod',
            _t(__CLASS__ . '.FieldImageCropMethodTitle', 'Crop Method'),
            [
                'fill' => _t(__CLASS__ . '.FieldImageCropMethodOptionFill', 'Fill'),
                'pad' => _t(__CLASS__ . '.FieldImageCropMethodOptionPad', 'Pad'),
            ]
        );
        return $field;
    }

    /**
     * getImageOrientationField
     *
     * @return OptionsetField
     */
    public function getImageOrientationField()
    {
        $field = OptionsetField::create(
            'ImageOrientation',
            _t(__CLASS__ . '.FieldImageOrientationTitle', 'Orientation'),
            [
                'landscape' => _t(__CLASS__ . '.FieldImageOrientationOptionLandscape', 'Landscape'),
                'portrait' => _t(__CLASS__ . '.FieldImageOrientationOptionPortrait', 'Portrait'),
            ]
        )->setDescription(_t(__CLASS__ . '.FieldImageOrientationDescription', 'This option does not rotate the image, but sets the orientation of the longer edge.'));
        return $field;
    }

    /**
     * getCustomAspectRatioField
     *
     * @return FieldGroup
     */
    public function getCustomAspectRatioField()
    {
        $heightField = NumericField::create(
            'ImageAspectCustomHeightRatio',
            _t(__CLASS__ . '.FieldImageCustomHeightTitle', 'Height')
        );
        $widthField = NumericField::create(
            'ImageAspectCustomWidthRatio',
            _t(__CLASS__ . '.FieldImageCustomWidthTitle', 'Width')
        );
        return FieldGroup::create($heightField, $widthField)
            ->setTitle(_t(__CLASS__ . '.FieldImageCustomRatioHolderTitle', 'Aspect Ratio'))
            ->setDescription(_t(__CLASS__ . '.FieldImageCustomRatioHolderDescription', 'a value like <code>1 x 7</code> is valid. You are setting a ratio, not pixel size.'));
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
            'ImageAspectCustomHeightRatio',
            'ImageAspectCustomWidthRatio',
        ]);

        // Add Image Setup Tab
        // -------------------
        $MapSettingsTab = $fields->findOrMakeTab('Root.ImageAspectRatio');
        $fields->removeByName([
            'ImageAspectRatio',
        ]);
        $fields->insertBefore('Settings', $MapSettingsTab);
        // Rename the "MapSettings" tab
        $fields->fieldByName('Root.ImageAspectRatio')
            ->setTitle(_t(__CLASS__ . '.ImageAspectRatioTabLabel', 'Image Aspect Ratio'));

        // Add Image Fields
        // -------------------
        $fields->addFieldsToTab(
            'Root.ImageAspectRatio',
            [
                $this->getAspectRatioField(),
                $cropMethodField = Wrapper::create($this->getCropMethodField()),
                $orientationField = Wrapper::create($this->getImageOrientationField()),
                $customRatioField = Wrapper::create($this->getCustomAspectRatioField())
            ]
        );
        $cropMethodField->displayUnless('ImageAspectRatio')->isEqualTo('original');
        $orientationField
            ->displayUnless('ImageAspectRatio')->isEqualTo('original')
            ->orIf('ImageAspectRatio')->isEqualTo('custom');
        $customRatioField->displayIf('ImageAspectRatio')->isEqualTo('custom');
        return $fields;
    }

    /**
     * validate
     *
     * @param  ValidationResult ValidationResult $result original result
     * @return ValidationResult
     */
    public function validate(ValidationResult $result)
    {
        $owner = $this->getOwner();
        if ($owner->ImageAspectRatio == 'custom') {
            if (is_null($owner->ImageAspectCustomHeightRatio) || $owner->ImageAspectCustomHeightRatio <= 0) {
                $result->addFieldError('ImageAspectCustomHeightRatio', _t(__CLASS__ . '.ErrorHeightNotSet', 'Please enter a valid height which must be greater than 0.'));
            }
            if (is_null($owner->ImageAspectCustomWidthRatio) || $owner->ImageAspectCustomWidthRatio <= 0) {
                $result->addFieldError('ImageAspectCustomWidthRatio', _t(__CLASS__ . '.ErrorWidthNotSet', 'Please enter a valid width which must be greater than 0.'));
            }
        }
        return $result;
    }
}

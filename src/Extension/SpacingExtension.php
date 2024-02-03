<?php

namespace Syntro\ElementalBootstrapBlocks\Extension;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FieldGroup;

/**
 * Extends an element with an option to let the user choose between spacing
 * options before and after an element.
 *
 * The options are given similar to the styles, as arrays where the keys are
 * the stored value and the values are the default description. You can give
 * options for before and after the element:
 *
 * - $spacing_before
 * - $spacing_after
 *
 * The values can be accessed in the template via
 * - SpacingBefore
 * - SpacingAfter
 * where you can render appropriate spacing classes. The fields of a new object
 * default to empty, so you will have to provide a default spacing in the holder
 * template.
 *
 * Translations of option labels can be acieved by adding a key in the
 * form '<AFTER|BEFORE>LABEL_<key>' under the 'Syntro\ElementalBootstrapBlocks\Extension\SpacingExtension'
 * key.
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class SpacingExtension extends DataExtension
{
    /**
     * Database fields
     * @config
     * @var array
     */
    private static $db = [
        'SpacingBefore' => 'Varchar(255)',
        'SpacingAfter' => 'Varchar(255)',
    ];

    /**
     * updateCMSFields
     *
     * @param  FieldList $fields the original fields
     * @return FieldList
     */
    public function updateCMSFields($fields)
    {
        $fields->removeByName(['SpacingBefore', 'SpacingAfter']);
        $translatedFields = [];
        $spacing_before = $this->getOwner()->config()->get('spacing_before');
        if ($spacing_before && count($spacing_before) > 0) {
            $spacing_before_options = [];
            foreach ($spacing_before as $key => $value) {
                $spacing_before_options[$key] = _t(__CLASS__ . '.BEFORELABEL_' . $key, $value);
            }
            $spacingBeforeDropdown = DropdownField::create('SpacingBefore', _t(__CLASS__ . '.SPACINGBEFORETITLE', 'Spacing Before'), $spacing_before_options);
            // $fields->insertBefore($spacingBeforeDropdown, 'ExtraClass');
            $spacingBeforeDropdown->setEmptyString(_t(__CLASS__ . '.DEFAULT', 'Default'));
            $translatedFields[] = $spacingBeforeDropdown;
        }
        $spacing_after = $this->getOwner()->config()->get('spacing_after');
        if ($spacing_after && count($spacing_after) > 0) {
            $spacing_after_options = [];
            foreach ($spacing_after as $key => $value) {
                $spacing_after_options[$key] = _t(__CLASS__ . '.AFTERLABEL_' . $key, $value);
            }
            $spacingAfterDropdown = DropdownField::create('SpacingAfter', _t(__CLASS__ . '.SPACINGAFTERTITLE', 'Spacing After'), $spacing_after_options);
            // $fields->insertBefore($spacingAfterDropdown, 'ExtraClass');
            $spacingAfterDropdown->setEmptyString(_t(__CLASS__ . '.DEFAULT', 'Default'));
            $translatedFields[] = $spacingAfterDropdown;
        }
        if (count($translatedFields) > 0) {
            // $fields->insertAfter(
            //     HeaderField::create(
            //         'SpacingOptions',
            //         _t(__CLASS__.'.TOGGLETITLE', 'Spacing')
            //     ),
            //     'ExtraClass'
            // );
            $fields->insertAfter(
                FieldGroup::create(
                    _t(__CLASS__ . '.TOGGLETITLE', 'Spacing'),
                    $translatedFields
                ),
                'ExtraClass'
            );
            // foreach (array_reverse($translatedFields) as $field) {
            //     $fields->insertAfter(
            //         $field,
            //         'SpacingOptions'
            //     );
            // }
        }
        return $fields;
    }
}

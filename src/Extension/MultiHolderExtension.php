<?php

namespace Syntro\ElementalBootstrapBlocks\Extension;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;

/**
 * Extends an element with the option to manually switch its holder template,
 * allowing for diffrent background styles without polluting the styles list.
 *
 * set the '$holder_styles' array on a class or via yaml config:
 * holder_styles:
 *   default: 'Default white background'
 *   dark: 'Dark background'
 *   shape: 'A shape in the background'
 *
 * Setting this to non-empty array will display a select field in the 'settings'
 * tab.
 *
 * During templating, the element will look for the holder template:
 * 'DNADesign/Elemental/Layout/<$controller_template>_<$holder_style>
 * and ultimatively fall back to the regular template.
 *
 *
 * Translations of option labels can be acieved by adding a key in the
 * form 'LABEL_<key>' under the 'Syntro\ElementalBootstrapBlocks\Extension\MultiHolderExtension'
 * key.
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class MultiHolderExtension extends DataExtension
{
    /**
     * Database fields
     * @config
     * @var array
     */
    private static $db = [
        'HolderStyle' => 'Varchar(255)',
    ];

    /**
     * updateCMSFields
     *
     * @param  FieldList $fields the original fields
     * @return FieldList
     */
    public function updateCMSFields($fields)
    {
        $fields->removeByName('HolderStyle');
        $styleDropdown = null;

        $holder_styles = $this->getOwner()->config()->get('holder_styles');
        if ($holder_styles && count($holder_styles) > 0) {
            $holder_styles_options = [];
            foreach ($holder_styles as $key => $value) {
                $holder_styles_options[$key] = _t(__CLASS__ . '.LABEL_' . $key, $value);
            }
            $styleDropdown = DropdownField::create('HolderStyle', _t(__CLASS__ . '.HOLDERSTYLE', 'Holder Style variation'), $holder_styles_options);
            // $fields->insertBefore($styleDropdown, 'ExtraClass');
            $styleDropdown->setEmptyString(_t(__CLASS__ . '.DEFAULT', 'Default'));
        }
        if (!is_null($styleDropdown)) {
            /** @var DropdownField|null $defaultStyleField */
            $defaultStyleField = $fields->fieldByName('Root.Settings.Style');
            $fields->insertBefore(
                HeaderField::create(
                    'StyleOptions',
                    _t(__CLASS__ . '.TOGGLETITLE', 'Style'),
                ),
                !is_null($defaultStyleField) ? 'Style' : 'ExtraClass'
            );
            $fields->insertAfter(
                $styleDropdown,
                !is_null($defaultStyleField) ? 'Style' : 'StyleOptions',
            );
        }
        return $fields;
    }
}

<?php

namespace Syntro\ElementalBootstrapBlocks\Extension;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\CheckboxField;

/**
 * Allows the styles dropdown of an element to be translated by adding
 * a key <class>.STYLE_<stylekey> to the translation file.
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class TranslatedStylesExtension extends DataExtension
{
    /**
     * updateCMSFields
     *
     * @param  FieldList $fields the original fields
     * @return FieldList
     */
    public function updateCMSFields($fields)
    {
        $owner = $this->getOwner();
        /** @var DropdownField|null */
        $styleField = $fields->fieldByName('Root.Settings.Style');
        if ($styleField) {
            $sourceArray = $styleField->getSource();
            $translatedArray = [];
            foreach ($sourceArray as $key => $value) {
                $translatedArray[$key] = _t(ClassInfo::class_name($owner) . '.STYLE_' . $key, $value);
            }
            $styleField->setSource($translatedArray);
        }
        return $fields;
    }
}

<?php

namespace Syntro\ElementalBootstrapBlocks\Extension;

use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\CheckboxField;

/**
 * Extends an element with the option to display a content field only for specific
 * styles (e.g. if you want to allow a 1:1 split with images / content for a gallery
 * block)
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class OptionalContentExtension extends DataExtension
{
    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'OptionalContent' => 'HTMLText',
    ];

    /**
     * @config
     */
    private static $enable_content_for_styles = [];

    /**
     * updateCMSFields
     *
     * @param  FieldList $fields the original fields
     * @return FieldList
     */
    public function updateCMSFields($fields)
    {
        $fields->removeByName([
            'OptionalContent',
        ]);
        $owner = $this->getOwner();
        $showInStyles = $owner->config()->get('enable_content_for_styles');
        if (!is_null($showInStyles) && in_array($owner->Style, $showInStyles)) {
            $contentField = HTMLEditorField::create(
                'OptionalContent',
                _t(__CLASS__ . '.OPTIONALCONTENT', 'Content')
            );
            $fields->insertAfter('Title', $contentField);
        }
        return $fields;
    }
}

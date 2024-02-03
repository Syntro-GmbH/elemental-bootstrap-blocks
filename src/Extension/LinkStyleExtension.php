<?php

namespace Syntro\ElementalBootstrapBlocks\Extension;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Core\ClassInfo;

/**
 * Extends an element with the option to switch the style of the link in the
 * template. This extension adds a dropdown to the CMS fields, listing the
 * options given in the configuration
 *
 * set the '$link_styles' array on a class or via yaml config:
 * link_styles:
 *   link: 'Link with text'
 *   primary: 'Primary color button'
 *   secondary: 'Secondary color button'
 *
 * In the template, use 'LinkStyle' to render the desired link markup
 *
 *
 * Translations of option labels can be acieved by adding a key in the
 * form of '<class>.LINKSTYLE_<key>'.
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class LinkStyleExtension extends DataExtension
{

    /**
     * @config
     * @var string
     */
    private static $link_field_name = 'Link';

    /**
     * Database fields
     * @config
     * @var array
     */
    private static $db = [
        'LinkStyle' => 'Varchar(255)',
    ];

    /**
     * updateCMSFields
     *
     * @param  FieldList $fields the original fields
     * @return FieldList
     */
    public function updateCMSFields($fields)
    {
        $fields->removeByName('LinkStyle');
        $styleDropdown = null;
        $owner = $this->getOwner();
        $link_styles = $owner->config()->get('link_styles');
        $link_field = $owner->config()->get('link_field_name');


        if ($link_styles && count($link_styles) > 0) {
            $link_styles_options = [];
            foreach ($link_styles as $key => $value) {
                $link_styles_options[$key] = _t(ClassInfo::class_name($owner) . '.LINKSTYLE_' . $key, $value);
            }
            $styleDropdown = DropdownField::create('LinkStyle', _t(__CLASS__ . '.LINKSTYLE', 'Link Style'), $link_styles_options);
            // $fields->insertBefore($styleDropdown, 'ExtraClass');
            $styleDropdown->setEmptyString(_t(__CLASS__ . '.DEFAULT', 'Default'));
        }

        if (!is_null($styleDropdown)) {
            $fields->insertBefore(
                $styleDropdown,
                'ExtraClass',
            );
        }
        return $fields;
    }
}

<?php

namespace Syntro\ElementalBootstrapBlocks\Model;

use SilverStripe\Forms\TextField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use Syntro\SilverStripeElementalBaseitem\Model\BaseItem;
use gorriecoe\Link\Models\Link;
use gorriecoe\LinkField\LinkField;
use Syntro\ElementalBootstrapBlocks\Element\TabSetBlock;

/**
 * A tab in a tabset
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class Tab extends BaseItem
{
    /**
     * Defines the database table name
     *  @var string
     */
    private static $table_name = 'BlockTabSet_Tab';

    /**
     * @config
     *
     * @var boolean
     */
    private static $displays_title_in_template = false;

    /**
     * @config
     *
     * @var array
     */
    private static $db = [
        'Content' => 'HTMLText',
    ];

    /**
     * @config
     * Add default values to database
     *  @var array
     */
    private static $defaults = [];

    /**
     * @config
     *
     * @var array
     */
    private static $has_one = [
        'Section' => TabSetBlock::class,
        'Image' => Image::class
    ];

    /**
     * duplicate relations
     * @config
     *  @var array
     */
    private static $cascade_duplicates = [
    ];


    /**
     * Defines summary fields commonly used in table columns
     * as a quick overview of the data for this dataobject
     * @config
     * @var array
     */
    private static $summary_fields = [
        'Title' => 'Title'
    ];

    /**
     * Relationship version ownership
     * @config
     * @var array
     */
    private static $owns = [
        'Image'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $contentField = $fields->fieldByName('Root.Main.Content');
        $contentField->setTitle(_t(__CLASS__ . '.CONTENTTITLE', 'Content'));


        $fields->addFieldToTab(
            'Root.Main',
            $imageField = UploadField::create(
                'Image',
                _t(__CLASS__ . '.IMAGETITLE', 'Image')
            )
        );
        $imageField->setFolderName('Elements/Tabset');
        $imageField->setAllowedExtensions(['png','jpg','jpeg']);
        $imageField->setDescription(_t(__CLASS__ . '.IMAGEDESCRIPTION', 'Uploading an image will display two 1:1 columns with the Image in the right one.'));

        $fields->removeByName([
            'SectionID',
        ]);


        return $fields;
    }
}

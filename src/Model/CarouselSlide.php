<?php

namespace Syntro\ElementalBootstrapBlocks\Model;

use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use Syntro\SilverStripeElementalBaseitem\Model\BaseItem;
use gorriecoe\Link\Models\Link;
use gorriecoe\LinkField\LinkField;
use Syntro\ElementalBootstrapBlocks\Element\CarouselBlock;

/**
 * A carousel slide
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class CarouselSlide extends BaseItem
{
    /**
     * Defines the database table name
     * @config
     *  @var string
     */
    private static $table_name = 'BlockCarousel_Slide';

    /**
     * @config
     *
     * @var boolean
     */
    private static $displays_title_in_template = true;

    /**
     * @config
     *
     * @var array
     */
    private static $db = [
        'Content' => 'HTMLText',
    ];

    /**
     * Add default values to database
     * @config
     *  @var array
     */
    private static $defaults = [];

    /**
     * @config
     *
     * @var array
     */
    private static $has_one = [
        'Section' => CarouselBlock::class,
        'Image' => Image::class,
        'Link' => Link::class
    ];

    /**
     * duplicate relations
     * @config
     *  @var array
     */
    private static $cascade_duplicates = [
        'Link'
    ];


    /**
     * Defines summary fields commonly used in table columns
     * as a quick overview of the data for this dataobject
     * @config
     * @var array
     */
    private static $summary_fields = [
        'Image.StripThumbnail' => 'Icon',
        'Title' => 'Title'
    ];

    /**
     * Relationship version ownership
     * @config
     * @var array
     */
    private static $owns = [
        'Image',
        'Link'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $contentField = $fields->fieldByName('Root.Main.Content');
        $contentField->setTitle(_t(__CLASS__ . '.CONTENTTITLE', 'Content'));

        $fields->removeByName([
            'SectionID',
            'LinkID'
        ]);

        $fields->addFieldToTab(
            'Root.Main',
            $imageField = UploadField::create(
                'Image',
                _t(__CLASS__ . '.IMAGETITLE', 'Image')
            ),
            'Title'
        );
        $imageField->setFolderName('Elements/Carousel');
        $imageField->setAllowedExtensions(['png','jpg','jpeg']);

        $fields->addFieldToTab(
            'Root.Main',
            LinkField::create(
                'Link',
                _t(__CLASS__ . '.LINKTITLE', 'Link'),
                $this
            )
        );

        return $fields;
    }
}

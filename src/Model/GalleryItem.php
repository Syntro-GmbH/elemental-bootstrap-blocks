<?php

namespace Syntro\ElementalBootstrapBlocks\Model;

use SilverStripe\ORM\ValidationResult;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use Syntro\SilverStripeElementalBaseitem\Model\BaseItem;
use gorriecoe\Link\Models\Link;
use gorriecoe\LinkField\LinkField;
use Syntro\ElementalBootstrapBlocks\Element\GalleryBlock;

/**
 * An employee
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class GalleryItem extends BaseItem
{
    /**
     * Defines the database table name
     *  @var string
     */
    private static $table_name = 'BlockGallery_Item';

    private static $displays_title_in_template = false;

    private static $db = [
        'IsVideo' => 'Boolean',
        'VideoURL' => 'Varchar'
    ];

    /**
     * Add default values to database
     *  @var array
     */
    private static $defaults = [];


    private static $has_one = [
        'Section' => GalleryBlock::class,
        'Image' => Image::class
    ];


    /**
     * Defines summary fields commonly used in table columns
     * as a quick overview of the data for this dataobject
     * @var array
     */
    private static $summary_fields = [
        'Image.StripThumbnail' => 'Portrait',
        'Title' => 'Caption'
    ];

    /**
     * Relationship version ownership
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

        $fields->removeByName([
            'SectionID',
            'IsVideo',
            'VideoURL'
        ]);

        $contentField = $fields->fieldByName('Root.Main.Title');
        $contentField->setTitle(_t(__CLASS__ . '.TITLETITLE', 'Caption'));


        $fields->addFieldToTab(
            'Root.Main',
            $imageField = UploadField::create(
                'Image',
                _t(__CLASS__ . '.PORTRAITTITLE', 'Image')
            ),
            'Title'
        );
        $imageField->setFolderName('Elements/Gallery');
        $imageField->setAllowedExtensions(['png','jpg','jpeg']);

        if ($this->Section->config()->get('enable_youtube_links')) {
            $fields->addFieldsToTab(
                'Root.Main',
                [
                    $isVideoField = CheckboxField::create(
                        'IsVideo',
                        _t(__CLASS__ . '.ISVIDEOTITLE', 'Is a Video')
                    ),
                    $videoURLField = TextField::create(
                        'VideoURL',
                        _t(__CLASS__ . '.VIDEOTITLE', 'Video URL')
                    )
                ],
                'Title'
            );
            $videoURLField->setDescription(_t(__CLASS__ . '.VIDEODESC', 'Paste the video URL here. It should look like this: <code>https://www.youtube.com/watch?v=XXXXXXXXXXX</code>'));
            $videoURLField->displayIf('IsVideo')->isChecked();
        }

        return $fields;
    }

    /**
     * validate
     *
     * @return ValidationResult
     */
    public function validate()
    {
        $result = parent::validate();
        if (!$this->ImageID) {
            $result->addFieldError('Image', _t(__CLASS__ . '.IMAGENOTSET', 'Please upload an image'));
        }
        if ($this->Section->config()->get('enable_youtube_links') && $this->IsVideo && !$this->VideoURL) {
            $result->addFieldError('VideoURL', _t(__CLASS__ . '.VIDEOURLNOTSET', 'Please enter a video URL'));
        }
        return $result;
    }
}

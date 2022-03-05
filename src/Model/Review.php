<?php

namespace Syntro\ElementalBootstrapBlocks\Model;

use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use Syntro\SilverStripeElementalBaseitem\Model\BaseItem;
use gorriecoe\Link\Models\Link;
use gorriecoe\LinkField\LinkField;
use Syntro\ElementalBootstrapBlocks\Element\CustomerReviewBlock;

/**
 * A customer review
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class Review extends BaseItem
{
    /**
     * Defines the database table name
     *  @var string
     */
    private static $table_name = 'BlockCustomerReview_Review';

    private static $displays_title_in_template = false;

    private static $db = [
        'Position' => 'Varchar',
        'Comment' => 'Text',
        'Score' => 'Int',
    ];

    /**
     * Add default values to database
     *  @var array
     */
    private static $defaults = [];

    private static $has_one = [
        'Section' => CustomerReviewBlock::class,
        'Portrait' => Image::class
    ];


    /**
     * Defines summary fields commonly used in table columns
     * as a quick overview of the data for this dataobject
     * @var array
     */
    private static $summary_fields = [
        'Portrait.StripThumbnail' => 'Portrait',
        'Title' => 'Name'
    ];

    /**
     * Relationship version ownership
     * @var array
     */
    private static $owns = [
        'Portrait'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $contentField = $fields->fieldByName('Root.Main.Title');
        $contentField->setTitle(_t(__CLASS__ . '.TITLETITLE', 'Name'));

        $commentField = $fields->fieldByName('Root.Main.Comment');
        $commentField->setTitle(_t(__CLASS__ . '.COMMENTTITLE', 'Comment'));

        $fields->removeByName([
            'SectionID',
        ]);

        $scoreField = DropdownField::create(
            'Score',
            _t(__CLASS__ . '.SCORETITLE', 'Score'),
            [
                '1' => '1/5',
                '2' => '2/5',
                '3' => '3/5',
                '4' => '4/5',
                '5' => '5/5'
            ]
        );
        $scoreField->setHasEmptyDefault(true);
        $scoreField->setEmptyString('...');
        $fields->addFieldToTab(
            'Root.Main',
            $scoreField,
            'Comment'
        );

        $fields->addFieldToTab(
            'Root.Main',
            $imageField = UploadField::create(
                'Portrait',
                _t(__CLASS__ . '.PORTRAITTITLE', 'Portrait')
            ),
            'Title'
        );
        $imageField->setFolderName('Elements/CustomerReview');
        $imageField->setAllowedExtensions(['png','jpg','jpeg']);

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

        if (!$this->Score) {
            $result->addFieldError('Score', _t(__CLASS__ . '.SCORENOTSET', 'Please select a score'));
        }

        if (!$this->Title) {
            $result->addFieldError('Title', _t(__CLASS__ . '.TITLENOTSET', 'Please enter a name'));
        }
        return $result;
    }
}

<?php

namespace Syntro\ElementalBootstrapBlocks\Model;

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
use Syntro\ElementalBootstrapBlocks\Element\EmployeesBlock;

/**
 * An employee
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class Employee extends BaseItem
{
    /**
     * Defines the database table name
     *  @var string
     */
    private static $table_name = 'BlockEmployees_Employee';

    private static $displays_title_in_template = false;

    private static $db = [
        'Position' => 'Varchar',
        'SMLLinkedin' => 'Varchar',
        'SMLFacebook' => 'Varchar',
        'SMLTwitter' => 'Varchar',
        'SMLInstagram' => 'Varchar',
    ];

    /**
     * Add default values to database
     *  @var array
     */
    private static $defaults = [];


    private static $has_one = [
        'Section' => EmployeesBlock::class,
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

        $fields->removeByName([
            'SectionID',
            'SMLLinkedin',
            'SMLFacebook',
            'SMLTwitter',
            'SMLInstagram',
        ]);
        $contentField = $fields->fieldByName('Root.Main.Title');
        $contentField->setTitle(_t(__CLASS__ . '.TITLETITLE', 'Name'));

        $contentField = $fields->fieldByName('Root.Main.Position');
        $contentField->setTitle(_t(__CLASS__ . '.POSITIONTITLE', 'Position'));

        if ($this->Section->config()->get('enable_social_media_links')) {
            $introText = _t(__CLASS__ . '.SOMEINTRO', 'If you enter any link, the respective profile will appear on the page.');
            $fieldLinkedin = TextField::create(
                'SMLLinkedin',
                'Linkedin'
            );
            $fieldFacebook = TextField::create(
                'SMLFacebook',
                'Facebook'
            );
            $fieldTwitter = TextField::create(
                'SMLTwitter',
                'Twitter'
            );
            $fieldInstagram = TextField::create(
                'SMLInstagram',
                'Instagram'
            );

            $fields->addFieldToTab(
                'Root.Main',
                ToggleCompositeField::create(
                    'SoMeProfiles',
                    _t(__CLASS__ . '.SOMETITLE', 'Social Profiles'),
                    [
                        LiteralField::create('SOMEINTRO', "<div class=\"mx-2 alert alert-info\">{$introText}</div>"),
                        $fieldLinkedin,
                        $fieldFacebook,
                        $fieldTwitter,
                        $fieldInstagram,
                    ]
                )
            );
        }


        $fields->addFieldToTab(
            'Root.Main',
            $imageField = UploadField::create(
                'Portrait',
                _t(__CLASS__ . '.PORTRAITTITLE', 'Portrait')
            ),
            'Title'
        );
        $imageField->setFolderName('Elements/Employees');
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

        if (!$this->Title) {
            $result->addFieldError('Title', _t(__CLASS__ . '.TITLENOTSET', 'Please enter a name'));
        }
        if (!$this->PortraitID) {
            $result->addFieldError('Portrait', _t(__CLASS__ . '.PORTRAITNOTSET', 'Please upload a portrait'));
        }
        return $result;
    }

    /**
     * RenderSocialProfiles - used in the template to render the overlay
     *
     * @return bool
     */
    public function RenderSocialProfiles()
    {
        $configValue = $this->Section->config()->get('enable_social_media_links');
        return $configValue && (
            $this->SMLLinkedin ||
            $this->SMLFacebook ||
            $this->SMLTwitter ||
            $this->SMLInstagram
        );
    }

}

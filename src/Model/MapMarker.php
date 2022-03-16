<?php

namespace Syntro\ElementalBootstrapBlocks\Model;

use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\NumericField;
use SilverStripe\ORM\ValidationResult;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use Syntro\SilverStripeElementalBaseitem\Model\BaseItem;
use gorriecoe\Link\Models\Link;
use gorriecoe\LinkField\LinkField;
use Syntro\ElementalBootstrapBlocks\Element\MapBlock;

/**
 * A marker to deploy on the map
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class MapMarker extends BaseItem
{
    /**
     * Defines the database table name
     *  @var string
     */
    private static $table_name = 'BlockMap_MapMarker';

    /**
     * Singular name for CMS
     *  @var string
     */
    private static $singular_name = 'Marker';

    /**
     * Plural name for CMS
     *  @var string
     */
    private static $plural_name = 'Markers';

    private static $displays_title_in_template = false;

    private static $db = [
        'Lat' => 'Double',
        'Long' => 'Double',
        'Info' => 'HTMLText',
        'ShowInfoOnLoad' => 'Boolean',
        // 'MarkerColor' => 'Varchar'
    ];

    /**
     * Add default values to database
     *  @var array
     */
    private static $defaults = [];


    private static $has_one = [
        'Section' => MapBlock::class,
    ];


    /**
     * Defines summary fields commonly used in table columns
     * as a quick overview of the data for this dataobject
     * @var array
     */
    private static $summary_fields = [
        'Title' => 'Title',
        'Lat' => 'Latitude',
        'Long' => 'Longitude'
    ];

    /**
     * Relationship version ownership
     * @var array
     */
    private static $owns = [];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            'SectionID',
            'Lat',
            'Long'
        ]);

        $SettingsTab = $fields->findOrMakeTab('Root.Settings');
        $fields->removeByName([
            'Settings',
        ]);
        $fields->insertAfter('Main', $SettingsTab);
        // Rename the "Settings" tab
        $fields->fieldByName('Root.Settings')
            ->setTitle(_t(__CLASS__ . '.SettingsTabLabel', 'Settings'));

        $fields->addFieldsToTab(
            'Root.Settings',
            [
                $showInfoField = CheckboxField::create(
                    'ShowInfoOnLoad',
                    _t(__CLASS__ . '.SHOWINFOTITLE', 'Show info bubble on load')
                )
            ]
        );
        $showInfoField->setDescription(_t(__CLASS__ . '.SHOWINFODESC', 'If selected, the bubble is displayed when the map is loaded. Otherwise, the user has to click on it'));

        $fields->fieldByName('Root.Main.Info')
            ->setTitle(_t(__CLASS__ . '.INFOTITLE', 'Info'));

        $latDescContent = _t(__CLASS__ . '.LATLONGDESC', 'Open Google Maps and right-click on the place you want this Marker to be set. Select the coordinates and paste them here. The format is (Latitude,Longitude).');
        $fields->addFieldsToTab(
            'Root.Main',
            [
                $latLongGroupField = FieldGroup::create(_t(__CLASS__ . '.LATLONGTITLE', 'Coordinates'), [
                    $latField = NumericField::create(
                        'Lat',
                        _t(__CLASS__ . '.LATTITLE', 'Latitude')
                    ),
                    $longField = NumericField::create(
                        'Long',
                        _t(__CLASS__ . '.LONGTITLE', 'Longitude')
                    )
                ])
            ],
            'Info'
        );
        $latField->setScale(12)->setHTML5(true);
        $longField->setScale(12)->setHTML5(true);
        $latLongGroupField->setDescription($latDescContent);

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
        if (is_null($this->Lat)) {
            $result->addFieldError('Lat', _t(__CLASS__ . '.LATNOTSET', 'Please set a Latitude'));
        }
        if (is_null($this->Long)) {
            $result->addFieldError('Long', _t(__CLASS__ . '.LONGNOTSET', 'Please set a Longitude'));
        }
        return $result;
    }
}

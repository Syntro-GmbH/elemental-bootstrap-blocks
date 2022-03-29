<?php
namespace Syntro\ElementalBootstrapBlocks\Element;

use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\NumericField;
use SilverStripe\View\Requirements;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use DNADesign\Elemental\Models\BaseElement;
use Syntro\SilverStripeElementalBaseitem\Forms\GridFieldConfig_ElementalChildren;
use Syntro\ElementalBootstrapBlocks\Model\MapMarker;

/**
 * An element which renders a map with marker(s)
 *
 * @author Matthias Leutenegger
 */
class MapBlock extends BaseElement
{

    /**
     * @config
     */
    private static $google_maps_api_key = '';
    /**
     * Defines the database table name
     *  @var string
     */
    private static $table_name = 'BlockMap';

    /**
     * Singular name for CMS
     *  @var string
     */
    private static $singular_name = 'Map Block';

    /**
     * Plural name for CMS
     *  @var string
     */
    private static $plural_name = 'Map Blocks';

    /**
     * @var bool
     */
    private static $inline_editable = false;

    /**
     * Display a show title button
     *
     * @config
     * @var boolean
     */
    private static $displays_title_in_template = false;

    /**
     * @var string
     */
    private static $icon = 'font-icon-block-globe';

    /**
     * available holder styles
     * @var array
     */
    private static $holder_styles = [];

    /**
     * available styles
     * @var array
     */
    private static $styles = [];

    /**
     * Ensures that the methods are wrapped in the correct type and
     * values are safely escaped while rendering in the template.
     * @var array
     */
    private static $casting = [
        'GoogleJS' => 'HTMLVarchar'
    ];

    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'Content' => 'HTMLText',
        'DefaultZoom' => 'Int',
        'MapStyle' => 'Text',
        'FitToMarkers' => 'Boolean'
    ];

    /**
     * Add default values to database
     *  @var array
     */
    private static $defaults = [
        'DefaultZoom' => 15,
        'FitToMarkers' => true
    ];

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = [];

    /**
     * Has_many relationship
     * @var array
     */
    private static $has_many = [
        'Markers' => MapMarker::class,
    ];

    /**
     * Relationship version ownership
     * @var array
     */
    private static $owns = [
        'Markers'
    ];

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $MapSettingsTab = $fields->findOrMakeTab('Root.MapSettings');
        $fields->removeByName([
            'MapSettings',
        ]);
        $fields->insertBefore('Settings', $MapSettingsTab);
        // Rename the "MapSettings" tab
        $fields->fieldByName('Root.MapSettings')
            ->setTitle(_t(__CLASS__ . '.MapSettingsTabLabel', 'Map Settings'));

        $fields->addFieldsToTab(
            'Root.MapSettings',
            [
                $zoomField = NumericField::create(
                    'DefaultZoom',
                    _t(__CLASS__ . '.ZOOMTITLE', 'Default Zoom Level')
                ),
                $styleField = TextareaField::create(
                    'MapStyle',
                    _t(__CLASS__ . '.STYLETITLE', 'Map Style')
                ),
                $fitMarkerField = CheckboxField::create(
                    'FitToMarkers',
                    _t(__CLASS__ . '.FITMARKERSTITLE', 'Fit zoom to markers')
                )
            ]
        );
        $zoomField->setDescription(_t(__CLASS__ . '.ZOOMDESC', 'Enter a number between 0 and 18, where 18 is the most zoomed in.'));
        $styleField->setDescription(_t(__CLASS__ . '.STYLEDESC', 'JSON formatted map style. visit <a href="https://mapstyle.withgoogle.com/" target="_blank">the docs</a> for information.'));
        $fitMarkerField->setDescription(_t(__CLASS__ . '.FITMARKERSDESC', 'If you only set one marker, this is automatically disabled.'));

        $fields->fieldByName('Root.Main.Content')
            ->setTitle(_t(__CLASS__ . '.CONTENTTITLE', 'Content'));

        if ($this->ID) {
            /** @var GridField $griditems */
            $griditems = $fields->fieldByName('Root.Markers.Markers');
            $griditems->setConfig(GridFieldConfig_ElementalChildren::create());
            $fields->fieldByName('Root.Markers')
                ->setTitle(_t(__CLASS__ . '.MarkerTabLabel', 'Marker ({count})', ['count' => $this->Markers()->count()]));
        } else {
            $fields->removeByName([
                'Markers',
                'Root.Markers.Markers'
            ]);
        }
        return $fields;
    }


    /**
     * getType
     *
     * @return string
     */
    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Map');
    }

    /**
     * getMarkerAttr
     *
     * @return string
     */
    public function getMarkerAttr()
    {
        $markers = [];
        foreach ($this->Markers() as $marker) {
            $markers[] = [
                'lat' => $marker->Lat,
                'lng' => $marker->Long,
                'info' => $marker->Info ? $marker->obj('Info')->RAWURLATT() : null,
                'showInfo' => $marker->ShowInfoOnLoad
            ];
        }
        return 'data-marker='.json_encode($markers);
    }

    /**
     * getMapoptionsAttr
     *
     * @return string
     */
    public function getMapoptionsAttr()
    {
        $options = [
            'fit' => $this->FitToMarkers,
            'zoom' => $this->DefaultZoom,
            'styles' => json_decode($this->MapStyle)
        ];
        return 'data-mapoptions='.json_encode($options);
    }

    /**
     * getController - update the requirements
     *
     * @return string
     */
    public function getGoogleJS()
    {
        $apikey = $this->config()->get('google_maps_api_key');
        // Requirements::javascript('syntro/elemental-bootstrap-blocks:client/dist/mapblock/bundle.js');
        // Requirements::javascript("https://maps.googleapis.com/maps/api/js?key=$apikey&libraries=places&callback=initMap");
        return "https://maps.googleapis.com/maps/api/js?key=$apikey&libraries=places&callback=initMap";
    }
}

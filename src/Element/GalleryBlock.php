<?php
namespace Syntro\ElementalBootstrapBlocks\Element;

use SilverStripe\Forms\HTMLEditor\HtmlEditorField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Assets\Image;
use DNADesign\Elemental\Models\BaseElement;
use Syntro\SilverStripeElementalBaseitem\Forms\GridFieldConfig_ElementalChildren;
use Syntro\ElementalBootstrapBlocks\Model\GalleryItem;

/**
 * An element which renders an image / video gallery
 *
 * @author Matthias Leutenegger
 */
class GalleryBlock extends BaseElement
{
    /**
     * Defines the database table name
     *  @var string
     */
    private static $table_name = 'BlockGallery';

    /**
     * Singular name for CMS
     *  @var string
     */
    private static $singular_name = 'Gallery Block';

    /**
     * Plural name for CMS
     *  @var string
     */
    private static $plural_name = 'Gallery Blocks';

    /**
     * @var bool
     */
    private static $inline_editable = false;

    /**
     * @var bool
     */
    private static $enable_youtube_links = true;

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
    private static $icon = 'font-icon-block-layout-4';

    /**
     * available holder styles
     * @var array
     */
    private static $holder_styles = [
        'colored' => 'Slight color'
    ];

    /**
     * available styles
     * @var array
     */
    private static $styles = [];

    /**
     * Database fields
     * @var array
     */
    private static $db = [];

    /**
     * Add default values to database
     *  @var array
     */
    private static $defaults = [];

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
        'Items' => GalleryItem::class,
    ];

    /**
     * Relationship version ownership
     * @var array
     */
    private static $owns = [
        'Items'
    ];

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        if ($this->ID) {
            /** @var GridField $griditems */
            $griditems = $fields->fieldByName('Root.Items.Items');
            $griditems->setConfig(GridFieldConfig_ElementalChildren::create());
            $fields->removeByName([
                'Items',
                'Root.Items.Items'
            ]);
            $fields->addFieldToTab(
                'Root.Main',
                $griditems
            );
        } else {
            $fields->removeByName([
                'Items',
                'Root.Items.Items'
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
        return _t(__CLASS__ . '.BlockType', 'Gallery');
    }
}

<?php
namespace Syntro\ElementalBootstrapBlocks\Element;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\AssetAdmin\Forms\UploadField;
use DNADesign\Elemental\Models\ElementContent;

/**
 * Description
 *
 * @author Matthias Leutenegger
 */
class HeroBlock extends ElementContent
{
    /**
     * Defines the database table name
     * @config
     *  @var string
     */
    private static $table_name = 'BlockHero';

    /**
     * Singular name for CMS
     * @config
     *  @var string
     */
    private static $singular_name = 'Hero';

    /**
     * Plural name for CMS
     * @config
     *  @var string
     */
    private static $plural_name = 'Heroes';

    /**
     * @config
     * @var bool
     */
    private static $inline_editable = true;

    /**
     * Display a show title button
     *
     * @config
     * @var boolean
     */
    private static $displays_title_in_template = false;

    /**
     * @config
     * @var string
     */
    private static $icon = 'font-icon-block-promo-2';

    /**
     * Database fields
     * @config
     * @var array
     */
    private static $db = [
        'Subtitle' => 'Varchar'
    ];

    /**
     * Add default values to database
     * @config
     *  @var array
     */
    private static $defaults = [];

    /**
     * Has_one relationship
     * @config
     * @var array
     */
    private static $has_one = [
        'Image' => Image::class,
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
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        /** @var UploadField $imageField */
        $imageField = $fields->fieldByName('Root.Main.Image');
        $imageField->setFolderName('Elements/Hero');
        return $fields;
    }

    /**
     * getType
     *
     * @return string
     */
    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Hero');
    }
}

<?php
namespace Syntro\ElementalBootstrapBlocks\Element;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\AssetAdmin\Forms\UploadField;
use DNADesign\Elemental\Models\ElementContent;
use Syntro\ElementalBootstrapBlocks\Extension\SplitRatioExtension;

/**
 * Description
 *
 * @author Matthias Leutenegger
 */
class ContentImageSplitBlock extends ElementContent
{
    /**
     * Defines the database table name
     *  @var string
     */
    private static $table_name = 'BlockContentImageSplit';

    /**
     * Singular name for CMS
     *  @var string
     */
    private static $singular_name = 'Content & Image Split Block';

    /**
     * Plural name for CMS
     *  @var string
     */
    private static $plural_name = 'Content & Image Split Blocks';

    /**
     * Defines extension names and parameters to be applied
     *  to this object upon construction.
     *  @var array
     */
    private static $extensions = [
        SplitRatioExtension::class
    ];

    /**
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
     * @var string
     */
    private static $icon = 'font-icon-block-promo-3';

    /**
     * available styles
     * @var array
     */
    private static $styles = [
        'leftimage' => 'Image on the left',
        'rightimage' => 'Image on the right'
    ];

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
    private static $has_one = [
        'Image' => Image::class,
    ];

    /**
     * Relationship version ownership
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
        $imageField->setTitle(_t(__CLASS__ . '.IMAGETITLE', 'Image'));
        $imageField->setFolderName('Elements/ContentImageSplit');
        $fields->addFieldToTab(
            'Root.Main',
            $imageField,
            'HTML'
        );
        return $fields;
    }

    /**
     * getType
     *
     * @return string
     */
    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Image & Content Split');
    }
}

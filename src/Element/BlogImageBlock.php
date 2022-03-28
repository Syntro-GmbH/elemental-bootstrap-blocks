<?php
namespace Syntro\ElementalBootstrapBlocks\Element;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\AssetAdmin\Forms\UploadField;
use DNADesign\Elemental\Models\BaseElement;
use Syntro\ElementalBootstrapBlocks\Extension\SplitRatioExtension;

/**
 * Adds an image block
 *
 * @author Matthias Leutenegger
 */
class BlogImageBlock extends BaseElement
{
    /**
     * Defines the database table name
     *  @var string
     */
    private static $table_name = 'BlockBlogImage';

    /**
     * Singular name for CMS
     *  @var string
     */
    private static $singular_name = 'Image Block';

    /**
     * Plural name for CMS
     *  @var string
     */
    private static $plural_name = 'Image Blocks';

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
    private static $icon = 'font-icon-block-file';

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
        $imageField->setFolderName('Elements/BlogImage');
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
        return _t(__CLASS__ . '.BlockType', 'Image');
    }
}

<?php
namespace Syntro\ElementalBootstrapBlocks\Element;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\AssetAdmin\Forms\UploadField;
use DNADesign\Elemental\Models\ElementContent;
use Syntro\ElementalBootstrapBlocks\Extension\SplitRatioExtension;

/**
 * Adds a block-specifc content block
 *
 * @author Matthias Leutenegger
 */
class BlogContentBlock extends ElementContent
{
    /**
     * Defines the database table name
     *  @var string
     */
    private static $table_name = 'BlockBlogContent';

    /**
     * Singular name for CMS
     *  @var string
     */
    private static $singular_name = 'Content Block';

    /**
     * Plural name for CMS
     *  @var string
     */
    private static $plural_name = 'Content Blocks';

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
    private static $displays_title_in_template = true;


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
     * Relationship version ownership
     * @var array
     */
    private static $owns = [];


    /**
     * getType
     *
     * @return string
     */
    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Content');
    }
}

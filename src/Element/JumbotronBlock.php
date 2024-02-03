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
class JumbotronBlock extends ElementContent
{
    /**
     * Defines the database table name
     * @config
     *  @var string
     */
    private static $table_name = 'BlockJumbotron';

    /**
     * Singular name for CMS
     * @config
     *  @var string
     */
    private static $singular_name = 'Jumbotron Block';

    /**
     * Plural name for CMS
     * @config
     *  @var string
     */
    private static $plural_name = 'Jumbotron Blocks';

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
    private static $displays_title_in_template = true;

    /**
     * @config
     * @var string
     */
    private static $icon = 'font-icon-block-rocket';


    /**
     * available styles
     * @config
     * @var array
     */
    private static $styles = [];

    /**
     * Database fields
     * @config
     * @var array
     */
    private static $db = [];

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
    private static $has_one = [];

    /**
     * Relationship version ownership
     * @config
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
        return _t(__CLASS__ . '.BlockType', 'Jumbotron');
    }
}

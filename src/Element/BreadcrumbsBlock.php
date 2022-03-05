<?php
namespace Syntro\ElementalBootstrapBlocks\Element;

use SilverStripe\Assets\Image;
use DNADesign\Elemental\Models\BaseElement;

/**
 * A Block used to render breadcrumbs
 *
 * @author Matthias Leutenegger
 */
class BreadcrumbsBlock extends BaseElement
{
    /**
     * Defines the database table name
     *  @var string
     */
    private static $table_name = 'BlockBreadcrumbs';

    /**
     * Singular name for CMS
     *  @var string
     */
    private static $singular_name = 'Breadcrumb';

    /**
     * Plural name for CMS
     *  @var string
     */
    private static $plural_name = 'Breadcrumbs';

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
    private static $icon = 'font-icon-block-external-link';

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
        return _t(__CLASS__ . '.BlockType', 'Breadcrumbs');
    }
}

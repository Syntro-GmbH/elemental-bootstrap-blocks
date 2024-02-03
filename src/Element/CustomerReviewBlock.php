<?php
namespace Syntro\ElementalBootstrapBlocks\Element;

use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Assets\Image;
use DNADesign\Elemental\Models\BaseElement;
use Syntro\SilverStripeElementalBaseitem\Forms\GridFieldConfig_ElementalChildren;
use Syntro\ElementalBootstrapBlocks\Model\Review;

/**
 * An element which renders a carousel with slides
 *
 * @author Matthias Leutenegger
 */
class CustomerReviewBlock extends BaseElement
{
    /**
     * Defines the database table name
     * @config
     *  @var string
     */
    private static $table_name = 'BlockCustomerReview';

    /**
     * Singular name for CMS
     * @config
     *  @var string
     */
    private static $singular_name = 'Customer Review Block';

    /**
     * Plural name for CMS
     * @config
     *  @var string
     */
    private static $plural_name = 'Customer Review Blocks';

    /**
     * @config
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
     * @config
     * @var string
     */
    private static $icon = 'font-icon-block-award';

    /**
     * available holder styles
     * @config
     * @var array
     */
    private static $holder_styles = [
        'colored' => 'Slight color'
    ];

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
    private static $db = [
        'Content' => 'HTMLText'
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
    private static $has_one = [];

    /**
     * Has_many relationship
     * @config
     * @var array
     */
    private static $has_many = [
        'Reviews' => Review::class,
    ];

    /**
     * Relationship version ownership
     * @config
     * @var array
     */
    private static $owns = [
        'Reviews'
    ];

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $contentField = HTMLEditorField::create(
            'Content',
            _t(__CLASS__ . '.CONTENTTITLE', 'Content')
        );
        $contentField->setRows(5);
        $fields->addFieldToTab(
            'Root.Main',
            $contentField
        );

        if ($this->ID) {
            /** @var GridField $griditems */
            $griditems = $fields->fieldByName('Root.Reviews.Reviews');
            $griditems->setConfig(GridFieldConfig_ElementalChildren::create());
            $fields->removeByName([
                'Reviews',
                'Root.Reviews.Reviews'
            ]);
            $fields->addFieldToTab(
                'Root.Main',
                $griditems
            );
        } else {
            $fields->removeByName([
                'Reviews',
                'Root.Reviews.Reviews'
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
        return _t(__CLASS__ . '.BlockType', 'Customer Reviews');
    }
}

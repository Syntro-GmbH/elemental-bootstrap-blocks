<?php
namespace Syntro\ElementalBootstrapBlocks\Element;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Assets\Image;
use DNADesign\Elemental\Models\BaseElement;
use Syntro\SilverStripeElementalBaseitem\Forms\GridFieldConfig_ElementalChildren;
use Syntro\ElementalBootstrapBlocks\Model\AccordionItem;

/**
 * An element which renders cards with icons
 *
 * @author Matthias Leutenegger
 */
class AccordionBlock extends BaseElement
{
    /**
     * Defines the database table name
     *  @var string
     */
    private static $table_name = 'BlockAccordion';

    /**
     * Singular name for CMS
     *  @var string
     */
    private static $singular_name = 'Accordion';

    /**
     * Plural name for CMS
     *  @var string
     */
    private static $plural_name = 'Accordions';

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
    private static $icon = 'font-icon-block-accordion';

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
    private static $db = [
        'StartExpanded' => 'Boolean'
    ];

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
        'AccordionItems' => AccordionItem::class,
    ];

    /**
     * Relationship version ownership
     * @var array
     */
    private static $owns = [
        'AccordionItems'
    ];

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $expandedField = CheckboxField::create(
            'StartExpanded',
            _t(__CLASS__ . '.STARTEXPANDEDTITLE', 'Start expanded')
        );
        $expandedField->setDescription(_t(__CLASS__ . '.STARTEXPANDEDDESCRIPTION', 'If active, the first item will be open on load.'));
        $fields->addFieldToTab(
            'Root.Main',
            $expandedField
        );

        if ($this->ID) {
            /** @var GridField $griditems */
            $griditems = $fields->fieldByName('Root.AccordionItems.AccordionItems');
            $griditems->setConfig(GridFieldConfig_ElementalChildren::create());
            $fields->removeByName([
                'AccordionItems',
                'Root.AccordionItems.AccordionItems'
            ]);
            $fields->addFieldToTab(
                'Root.Main',
                $griditems
            );
        } else {
            $fields->removeByName([
                'AccordionItems',
                'Root.AccordionItems.AccordionItems'
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
        return _t(__CLASS__ . '.BlockType', 'Accordion');
    }
}

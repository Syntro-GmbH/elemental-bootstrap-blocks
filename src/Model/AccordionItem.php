<?php

namespace Syntro\ElementalBootstrapBlocks\Model;

use SilverStripe\Forms\TextField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use Syntro\SilverStripeElementalBaseitem\Model\BaseItem;
use gorriecoe\Link\Models\Link;
use gorriecoe\LinkField\LinkField;
use Syntro\ElementalBootstrapBlocks\Element\AccordionBlock;

/**
 * A card with an icon
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class AccordionItem extends BaseItem
{
    /**
     * Defines the database table name
     * @config
     *  @var string
     */
    private static $table_name = 'BlockAccordion_AccordionItem';

    /**
     * @config
     * @var boolean
     */
    private static $displays_title_in_template = false;

    /**
     * @config
     * @var array
     */
    private static $db = [
        'Content' => 'HTMLText',
    ];

    /**
     * Add default values to database
     * @config
     *  @var array
     */
    private static $defaults = [];

    /**
     * @config
     *
     * @var array
     */
    private static $has_one = [
        'Section' => AccordionBlock::class,
    ];

    /**
     * duplicate relations
     * @config
     *  @var array
     */
    private static $cascade_duplicates = [];


    /**
     * Defines summary fields commonly used in table columns
     * as a quick overview of the data for this dataobject
     * @config
     * @var array
     */
    private static $summary_fields = [
        'Title' => 'Title'
    ];

    /**
     * Relationship version ownership
     * @config
     * @var array
     */
    private static $owns = [
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $contentField = $fields->fieldByName('Root.Main.Content');
        $contentField->setTitle(_t(__CLASS__ . '.CONTENTTITLE', 'Content'));

        $fields->removeByName([
            'SectionID'
        ]);

        return $fields;
    }
}

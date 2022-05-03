<?php
namespace Syntro\ElementalBootstrapBlocks\Element;

use SilverStripe\Control\Controller;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\AssetAdmin\Forms\UploadField;
use DNADesign\Elemental\Models\BaseElement;
use Syntro\ElementalBootstrapBlocks\Control\ContactFormBlockController;

/**
 * Description
 *
 * @author Matthias Leutenegger
 */
class ContactFormBlock extends BaseElement
{
    /**
     * Defines the database table name
     *  @var string
     */
    private static $table_name = 'BlockContactForm';

    /**
     * Singular name for CMS
     *  @var string
     */
    private static $singular_name = 'Contact Form';

    /**
     * Plural name for CMS
     *  @var string
     */
    private static $plural_name = 'Contact Forms';

    /**
     * @var string
     */
    private static $controller_class = ContactFormBlockController::class;

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
     * @var string
     */
    private static $icon = 'font-icon-block-phone';

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
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        return parent::getCMSFields();
    }

    /**
     * getType
     *
     * @return string
     */
    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Contact Form');
    }

    /**
     * Link
     *
     * @param  string $action = null action to append
     * @return string
     */
    public function Link($action = null)
    {
        $current = Controller::curr();

        if ($action === 'finished') {
            return Controller::join_links(
                $current->Link(),
                'finished'
            );
        }

        return parent::Link($action);
    }

    public function Form()
    {
        $form = $this->getController()->HelloForm();

        return $form;
    }
}

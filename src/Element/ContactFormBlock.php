<?php
namespace Syntro\ElementalBootstrapBlocks\Element;

use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\HTMLEditor\HtmlEditorField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\Form;
use SilverStripe\Control\Controller;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Core\Injector\Injector;
use DNADesign\Elemental\Models\BaseElement;
use Syntro\ElementalBootstrapBlocks\Control\ContactFormBlockController;
use Syntro\ElementalBootstrapBlocks\Control\FormProvider;

/**
 * Adds a block which allows to add one of different contact forms to the page
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
    private static $db = [
        'FormName' => 'Varchar',
        'Content' => 'HTMLText',
        'SuccessResponse' => 'Text'
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
     * Relationship version ownership
     * @var array
     */
    private static $owns = [];

    /**
     * The controller which handles the forms
     * @config
     * @var string
     */
    private static $forms_provider = FormProvider::class;

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            'FormName',
            'Content',
            'SuccessResponse'
        ]);
        $controllerClass = $this->config()->get('forms_provider');

        $fields->addFieldsToTab(
            'Root.Main',
            [
                DropdownField::create(
                    'FormName',
                    _t(__CLASS__ . '.FormVariant', 'Form Variant'),
                    $controllerClass::providesForms()
                ),
                HtmlEditorField::create(
                    'Content',
                    _t(__CLASS__ . '.Content', 'Content')
                ),
                TextareaField::create(
                    'SuccessResponse',
                    _t(__CLASS__ . '.SuccessResponse', 'Nachricht bei Erfolg')
                )
            ]
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

    /**
     * Form - returns the Form
     *
     * @return Form|null
     */
    public function Form()
    {

        $providerClass = $this->config()->get('forms_provider');
        if (!class_exists($providerClass ?? '')) {
            throw new \Exception(
                'Could not find form provider class ' . $providerClass . '.'
            );
        }
        $provider = Injector::inst()->create($providerClass, $this);
        $current = Controller::curr();
        $provider->setRequest($current->getRequest());

        $formName = $this->FormName;
        if (!method_exists($provider, $formName)) {
            return null;
        }

        $form = $provider->$formName();

        $form->setFormAction(
            Controller::join_links(
                $current->Link(),
                'element',
                $this->owner->ID,
                'Form'
            )
        );

        return $form;
    }
}

<?php

namespace Syntro\ElementalBootstrapBlocks\Control;

use DNADesign\Elemental\Controllers\ElementController;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;
use Syntro\ElementalBootstrapBlocks\Control\FormProvider;
use SilverStripe\Core\Injector\Injector;

/**
 * Handles proxying the form post to the form provider
 *
 * @author Matthias Leutenegger
 */
class ContactFormBlockController extends ElementController
{

    /**
     * @config
     * @var array
     */
    private static $allowed_actions = [
        'Form'
    ];

    /**
     * @var FormProvider
     */
    protected $formProviderController;

    /**
     * init
     *
     * @return void
     */
    protected function init()
    {
        parent::init();
        if (!$this->getFormProviderController()) {
            $controllerClass = $this->element->config()->get('forms_provider');
            if (!class_exists($controllerClass ?? '')) {
                throw new \Exception(
                    'Could not find form provider class ' . $controllerClass . '.'
                );
            }
            $controller = Injector::inst()->create($controllerClass, $this->element);
        } else {
            $controller = $this->getFormProviderController();
        }
        $controller->setRequest($this->getRequest());
        $controller->doInit();

        $this->setFormProviderController($controller);
    }

    /**
     * Form - Returns the required form
     *
     * @return Form|null
     */
    public function Form()
    {
        $element = $this->element;
        $formName = $element->FormName;
        $provider = $this->getFormProviderController();
        if ($provider->hasMethod($formName)) {
            return $provider->$formName();
        }
        return null;
    }

    /**
     * @param string $action action to append
     * @return string
     */
    public function Link($action = null)
    {
        $id = $this->element->ID;
        $segment = Controller::join_links('element', $id, $action);
        /** @var mixed */
        $page = Director::get_current_page();

        if ($page && !($page instanceof ElementController)) {
            return $page->Link($segment);
        }
        /** @var Controller|null */
        if ($controller = Controller::curr()) {
            return $controller->Link($segment);
        }

        return $segment;
    }

    /**
     * Return the associated FormProvider
     *
     * @return FormProvider|null
     */
    public function getFormProviderController()
    {
        return $this->formProviderController;
    }

    /**
     * Set the associated FormProvider
     *
     * @param FormProvider $controller the controller
     * @return ContactFormBlockController $this
     */
    public function setFormProviderController(FormProvider $controller)
    {
        $this->formProviderController = $controller;
        return $this;
    }
}

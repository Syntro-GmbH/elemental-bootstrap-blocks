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



/**
 *
 */
class ContactFormBlockController extends ElementController
{
    private static $allowed_actions = [
        'HelloForm'
    ];
    public function getTest()
    {
        return 'Test';
    }

    public function HelloForm()
    {
        $fields = new FieldList(
            TextField::create('Name', 'Your Name')
        );

        $actions = new FieldList(
            FormAction::create('doSayHello')->setTitle('Say hello')
        );

        $required = new RequiredFields('Name');

        // $formAction = $this->Link('HelloForm');

        $form = new Form($this, 'HelloForm', $fields, $actions, $required);

        return $form;
    }

    public function doSayHello($data, Form $form)
    {
        $form->sessionMessage('Hello ' . $data['Name'], 'success');

        return $this->redirectBack();
    }

    /**
     * @param string $action
     *
     * @return string
     */
    public function Link($action = null)
    {
        $id = $this->element->ID;
        $segment = Controller::join_links('element', $id, $action);
        $page = Director::get_current_page();

        if ($page && !($page instanceof ElementController)) {
            return $page->Link($segment);
        }

        if ($controller = $this->getParentController()) {
            return $controller->Link($segment);
        }

        return $segment;
    }
}

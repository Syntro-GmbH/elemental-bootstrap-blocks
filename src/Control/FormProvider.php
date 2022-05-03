<?php

namespace Syntro\ElementalBootstrapBlocks\Control;

use SilverStripe\Control\Controller;

use PageController;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;

/**
 * Handles the creation of forms
 *
 * @author Matthias Leutenegger
 */
class FormProvider extends PageController
{
    /**
     * providesForms - returns the form variation
     *
     * @return array
     */
    public static function providesForms()
    {
        return [
            'DefaultForm' => 'Some simple default form',
        ];
    }

    /**
     * DefaultForm - a simple contact form with Name, Email and Message fields.
     * Overwrite if you want to add custom fields
     *
     * @return Form
     */
    public function DefaultForm()
    {
        $fields = new FieldList(
            TextField::create('Name', 'Your Name')
        );

        $actions = new FieldList(
            FormAction::create('doHandleDefaultForm')->setTitle('Say hello')
        );

        $required = new RequiredFields('Name');

        $form = new Form($this, 'HelloForm', $fields, $actions, $required);

        return $form;
    }

    /**
     * doHandleDefaultForm - Handles the sending of the message. Use
     * '$this->data()' to get the calling form block
     *
     * @param  array $data the data from the form
     * @param  Form  $form the form itself
     * @return mixed
     */
    public function doHandleDefaultForm($data, Form $form)
    {
        $form->sessionMessage('Hello ' . $data['Name'], 'success');

        return $this->redirectBack();
    }
}

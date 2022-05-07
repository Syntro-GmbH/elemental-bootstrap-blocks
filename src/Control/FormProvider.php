<?php

namespace Syntro\ElementalBootstrapBlocks\Control;

use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPResponse;
use PageController;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Control\Email\Email;
use Syntro\SilverstripeBootstrapForms\Forms\FieldGroup;
use Syntro\SilverstripeBootstrapForms\Forms\TextField;
use Syntro\SilverstripeBootstrapForms\Forms\EmailField;
use Syntro\SilverstripeBootstrapForms\Forms\DropdownField;
use Syntro\SilverstripeBootstrapForms\Forms\CheckboxField;
use Syntro\SilverstripeBootstrapForms\Forms\TextareaField;
use Syntro\SilverstripeBootstrapForms\Forms\OptionsetField;
use Syntro\SilverstripeBootstrapForms\Forms\CheckboxSetField;
use Syntro\SilverstripeBootstrapForms\Forms\PhoneField;

/**
 * Handles the creation of forms. Add forms in a subclass and configure the
 * 'forms_provider' field on the ContactFormBlock class.
 *
 * This way, you can add any form variant you might need and also add
 * any custom logic which might be necessary to drive a specific customer
 * experience
 *
 * @author Matthias Leutenegger
 */
class FormProvider extends PageController
{

    /**
     * where to send the default Form to.
     * @config
     * @var string
     */
    private static $send_response_to;

    /**
     * where to send the default Form from.
     * @config
     * @var string
     */
    private static $send_response_from;


    /**
     * providedForms - returns the form variation
     *
     * @return array
     */
    public function providedForms()
    {
        $availableForms = [
            'DefaultForm' => _t(__CLASS__ . '.DefaultFormName', 'Some simple default form'),
        ];
        $this->extend('updateAvailableForms', $availableForms);
        return $availableForms;
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
            TextField::create('name', _t(__CLASS__ . '.FullName', 'Full Name')),
            FieldGroup::create(
                PhoneField::create('phone', _t(__CLASS__ . '.Phone', 'Phone Number'))->addHolderClass('mt-3'),
                EmailField::create('email', _t(__CLASS__ . '.Email', 'Email Address'))->addHolderClass('mt-3'),
            )->addExtraClass('row row-cols-1 row-cols-md-2'),
            TextareaField::create(
                'Message',
                _t(__CLASS__ . '.Message', 'Message')
            )->addHolderClass('mt-3'),
            TextField::create('potinfo', 'More Information')->addHolderClass('d-none')
        );

        $this->extend('updateDefaultFormFields', $fields);

        $actions = new FieldList(
            FormAction::create('doHandleDefaultForm')
                ->setTitle(_t(__CLASS__ . '.Submit', 'Send us a message'))
                ->addExtraClass('btn btn-lg btn-primary mt-4')
        );
        $this->extend('updateDefaultFormActions', $actions);

        $required = new RequiredFields(['name', 'phone', 'email']);
        $this->extend('updateDefaultFormRequired', $required);

        $form = new Form($this, 'HelloForm', $fields, $actions, $required);
        $form->setRedirectToFormOnValidationError(true);
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
        if (isset($data['potinfo']) && $data['potinfo'] != '') {
            return $this->redirectToForm($form);
        }
        if ($this->sendDataByMail($data)) {
            $form->sessionMessage($this->data()->SuccessResponse, 'alert alert-success');
        } else {
            $form->setSessionData($data);
            $form->sessionMessage(_t(__CLASS__ . '.MailError', 'Something went wrong, please contact us directly.'), 'alert alert-danger');
        }

        return $this->redirectToForm($form);
    }

    /**
     * redirectToForm - redirects the request back to the form using the ID of
     * the given form, so the sees wether his submission was successful.
     *
     * @param  Form $form the form to redirect to (needed to get the ID)
     * @return HTTPResponse
     */
    public function redirectToForm($form)
    {
        $pageURL = $this->getReturnReferer();
        if (!$pageURL) {
            return $this->redirectBack();
        }
        $pageURL = Controller::join_links($pageURL, '/#' . $form->FormName());
        $pageURL = Director::absoluteURL($pageURL);
        return $this->redirect($pageURL);
    }

    /**
     * sendDataByMail - description
     *
     * @param  array $data the form data
     * @return bool
     */
    public function sendDataByMail($data)
    {
        $to = $this->config()->get('send_response_to');
        $from = $this->config()->get('send_response_from');
        if (!$to || !$from) {
            return false;
        }
        $subject = _t(__CLASS__ . 'MailSubject', 'You have recieved a new Message from a potential Customer!');
        $body = '<table style="max-width: 100%; border-spacing: 10px;">';
        $body .= "<tr><td><b>Origin:</b></td><td><a href=\"{$this->getReturnReferer()}\">{$this->getReturnReferer()}</a></td></tr>";
        foreach ($data as $key => $value) {
            if ($key != 'SecurityID' && $key != 'potinfo' && strpos($key, 'action') === false) {
                $escapedValue = htmlspecialchars($value) ?: '<i style="color: #aaaaaa">not set</i>';
                $body .= "<tr><td><b>$key:</b></td><td>$escapedValue</td></tr>";
            }
        }
        $body .= '</table>';
        $email = new Email($from, $to, $subject, $body);
        $success = false;
        try {
            $success = $email->send();
        } finally {
            return $success;
        }
    }
}

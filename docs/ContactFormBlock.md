# ContactFormBlock

When creating Websites for clients, there is often a need for a form with which
users can contact the owner of the site. There are two options to this problem:

1. Add the userforms module
2. Add a custom form

While adding a custom form gives all the flexibility, it requires a lot of
boilerplate code to get it working with SilverStripe elemental blocks. Adding
the userforms module hands all control to the client and obstructs possibilities
of handling the sent data behind the userform. Additionally, it is rarely necessary
to have the full flexibility (and also complexity) of user defined forms.

The `ContactFormBlock` is intended to be a middle ground between a fully
userdefined form solution and the developer freedom to create a specific
form. The block offers the user a selection from predefined forms which can be
changed on the application level by adding new forms with an extension or an entirely new provider.

## Managing Forms
Forms are handled by the [`FormProvider`](/src/Control/FormProvider.php).

### The Default Form
By default, the block comes with a default form which sends the submitted data
to an email address defined in the config. You have to define the target and sender
address for each client webpage:

```yml
Syntro\ElementalBootstrapBlocks\Control\FormProvider:
  send_response_to: info@yourdomain.com
  send_response_from: info@yourdomain.com
```

> Also make sure to correctly configure the webpage to handle [sending emails](https://docs.silverstripe.org/en/4/developer_guides/email/#email)

The Default form has the following fields:
* Name (required)
* Phone (required)
* Email (required)
* Message

You can change the form in any way you like by adding an extension to the
`FormProvider` and extending the following hooks:

```php
public function updateDefaultFormFields(FieldList &$fields) {/* ... */}
public function updateDefaultFormActions(FieldList &$actions) {/* ... */}
public function updateDefaultFormRequired(RequiredFields &$required) {/* ... */}
```

### Adding New Forms
The easiest way to add a new form is by adding an extension to the `FormProvider`
and add the form there. The default ruleset for forms in controllers applies,
but we recommend to use the [Bootstrap frontend fields](https://github.com/syntro-opensource/silverstripe-bootstrap-forms),
as they are more suited to render in a frontend context.

When you have added the form, you will have to extend the `updateAvailableForms`
hook to add the new form to the available forms:

```php
public function updateAvailableForms(&$forms)
{
    $forms['ExtendedForm'] = 'This is added via extension';
}
```
The Key in the array **must** match the name of the method defining your form!

## `FormProvider` API

### `public sendDataByMail(array $data)` -> `bool`
This will send the data in the given array to the configured email address and
return `true` if successful.

### `public redirectToForm(Form $form)` -> `HTTPResponse`
This will redirect the current request to the origin (similar to `redirectBack()`)
but it will scroll the user to the given forms location on that page.

Use this instead of `$this->redirectBack()`, so the user sees the form message
even if the form is on the bottom of the page.

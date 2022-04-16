# OptionalContentExtension

This extension adds a content field, which can be enabled for specific styles.
This adds a `HtmlEditorField` to the block which is displayed only if the current
style matches a configured one. Use this in a template like so:

```html
<div class="row">
    <div class="col">
        $OptionalContent
    </div>
    <div class="col">
        <!-- Cards, Images or other things -->
    </div>
</div>
```

## Added Fields

| Name              | Description                                                                                  |
| ----------------- | -------------------------------------------------------------------------------------------- |
| `OptionalContent` | The content field which is displayed only, if the current `Style` is configured to be active |

## Config Options

Config options are set on the block to which the extension is applied.

| Name                        | Default | Description                                             |
| --------------------------- | ------- | ------------------------------------------------------- |
| `enable_content_for_styles` | `[]`    | any style in this array will display the content field. |

# MapBlock configuration

The MapBlock displays a Google map using the google javascript API. This requires
an API Token which must be configured in order for the map to be displayed
correctly.

To obtain an API code, go to https://console.cloud.google.com/project/_/google/maps-apis/credentials
and create one for the project.

Then, configure the block:
```yml
Syntro\ElementalBootstrapBlocks\Element\MapBlock:
    google_maps_api_key: XXXXXXXXXXXXXXXX
```

## Templating
In the Template, define the DOM node which should contain the map as follows:
```html
<div class="shadow-sm rounded-3 overflow-hidden mapblock-map h-100 bg-white"
  {$MarkerAttr}
  {$MapoptionsAttr}
>
```

In order for the javascript to be loaded, add the following to each template:
```html
<% require javascript('syntro/elemental-bootstrap-blocks:client/dist/mapblock/bundle.js') %>
<% require javascript($GoogleJS) %>
```

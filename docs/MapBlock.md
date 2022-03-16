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

# UseCarouselExtension

This extension adds fields to control an eventual carousel / slider display.

## Added Fields

* `UseCarousel`: If enabled, the block in question should render its contents
in a slider or carousel.
* `UseCarouselAutoplay`: If enabled, the block should have an autoplay enabled.

## Config Options

Config options are set on the block to which the extension is applied.

| Name                          | Default | Description                                                                                                                                                                                  |
| ----------------------------- | ------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `disable_carousel_for_styles` | `[]`    | any style in this array will not have the carousel Fields enabled. If you want to hide the extension fields for the default style, consider adding a default style `default` to the defaults | 

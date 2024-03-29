# Elemental Bootstrap Blocks

## Blocks
This Repository provides a set of base bootstrap blocks which are used in a
silverstripe-elemental setup.

They lack any template, so you need to provide the following templates in the
`templates/Syntro/ElementalBootstrapBlock/Element` directory:

* [AccordionBlock](src/Element/AccordionBlock.php)
* [BreadcrumbsBlock](src/Element/BreadcrumbsBlock.php)
* [CardDeckBlock](src/Element/CardDeckBlock.php)
* [CarouselBlock](src/Element/CarouselBlock.php)
* [ContactFormBlock](src/Element/ContactFormBlock.php) ([docs](docs/ContactFormBlock.md))
* [ContentImageSplitBlock](src/Element/ContentImageSplitBlock.php)
* [CustomerReviewBlock](src/Element/CustomerReviewBlock.php)
* [EmployeesBlock](src/Element/EmployeesBlock.php)
* [GalleryBlock](src/Element/GalleryBlock.php)
* [HeroBlock](src/Element/HeroBlock.php)
* [ImageBlock](src/Element/ImageBlock.php)
* [JumbotronBlock](src/Element/JumbotronBlock.php)
* [MapBlock](src/Element/MapBlock.php) ([docs](docs/MapBlock.md))
* [TabSetBlock](src/Element/TabSetBlock.php)

### Blog
See [the docs](docs/BlogBlocks.md).


## Extensions
This module supplies some extensions to add functionality to blocks where needed.

* [UseCarouselExtension](docs/extensions/UseCarouselExtension.md)
* [OptionalContentExtension](docs/extensions/OptionalContentExtension.md)

# Using the SilverStripe Blog Module

When installing the blog module in a project using these blocks,
the set of blocks is extended with the following blocks:

* [BlogContentBlock](/src/Element/BlogContentBlock.php)
* [BlogImageBlock](/src/Element/BlogImageBlock.php)
* [BlogPostReelBlock](/src/Element/BlogPostReelBlock.php)

## Content in Blog Posts

By default, a `BlogPost` has only access to the `BlogContentBlock` and
`BlogImageBlock` elements. The reasoning behind this is, that blogs may
display content in specific ways, which do not conform with the rest of
the page (two columns for example). It is required to supply templates
for these elements.

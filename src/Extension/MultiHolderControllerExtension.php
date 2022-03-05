<?php

namespace Syntro\ElementalBootstrapBlocks\Extension;

use SilverStripe\Forms\FieldList;
use SilverStripe\Core\Extension;


/**
 * enables multiple controllers, see {@see MultiHolderExtension}
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class MultiHolderControllerExtension extends Extension
{

    /**
     * updateForTemplateTemplate - updates the holder template if the element
     * can have multiple holders
     *
     * @param  string $template the original holder template path
     * @return string|array
     */
    public function updateForTemplateTemplate(&$template)
    {
        $myElement = $this->getOwner()->getElement();
        if ($myElement->has_extension(MultiHolderExtension::class) && $myElement->HolderStyle) {
            $template = $template .'_'. $myElement->HolderStyle;
        }

        return $template;
    }
}

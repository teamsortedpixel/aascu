<?php

namespace App\Fields\Partials;

use Log1x\AcfComposer\Partial;
use StoutLogic\AcfBuilder\FieldsBuilder;

class CtaButtonFields extends Partial
{
    /**
     * The partial field group.
     *
     * @return array
     */
    public function fields()
    {
        $style_choices = [];

        foreach(\App\View\Components\CtaButton::getStyles() as $style){
            $style_choices[$style] = ucwords($style);
        }

        $ctaButton = new FieldsBuilder('cta_button');

        $ctaButton
            ->addText('label', [
                'wrapper' => [
                    'width' =>'70'
                ],
                'required' => 1
            ])
            ->addSelect('style', [
                'choices' => $style_choices,
                'default_value' => \App\View\Components\CtaButton::getStyles()[0],
                'wpml_cf_preferences' => 0,
                'wrapper' => [
                    'width' =>'30'
                ],
                'required' => 1
            ])
            ->addRadio('cta_type', [
                'label' => 'CTA Type',
                'layout' => 'horizontal',
                'choices' => [
                    'post_id' => 'Internal Post/Page',
                    'externa_url' => 'External URL',
                    'anchor' => 'Page Anchor'
                ],
                'wpml_cf_preferences' => 0,
                'wrapper' => [
                    'width' =>'30'
                ],
                'required' => 1
            ])
            ->addText('externa_url', [
                'label' => 'External URL',
                'wrapper' => [
                    'width' =>'70'
                ],
                'required' => 1
            ])
                ->conditional('cta_type', '==', 'externa_url')
            ->addPostObject('post_id', [
                'label' => 'Internal Post/Page',
                'post_type' => [
                    'page',
                    'post'
                ],
                'wrapper' => [
                    'width' =>'70'
                ],
                'return_format' => 'id',
                'required' => 1
            ])
                ->conditional('cta_type', '==', 'post_id')
            ->addText('anchor', [
                'label' => 'Page Anchor',
                'wrapper' => [
                    'width' =>'70'
                ],
                'required' => 1,
                'instructions' => 'To create an anchor enter a word or two — without spaces —. Example: my-anchor'
            ])
                ->conditional('cta_type', '==', 'anchor')
            ->addTrueFalse('new_tab', [
                'label' => 'Open in new tab?',
                'wrapper' => [
                    'width' =>'50'
                ]
            ])
                ->conditional('cta_type', '!=', 'anchor');

        return $ctaButton;
    }
}

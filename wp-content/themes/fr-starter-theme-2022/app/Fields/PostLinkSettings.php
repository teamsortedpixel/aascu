<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class PostLinkSettings extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $postLinkSettings = new FieldsBuilder('post_link_settings', [
            'position' => 'side'
        ]);

        $postLinkSettings
            ->setLocation('post_type', '==', 'news')
            ->or('post_type', '==', 'resource')
            ->or('post_type', '==', 'event')
            ->or('post_type', '==', 'page');

        $postLinkSettings
            ->addRadio('post_link_type', [
                'instructions' => 'Choose how the post\'s url should work when opened.',
                'label' => 'Post Link Behavior',
                'layout' => 'horizontal',
                'choices' => [
                    'internal' => 'Show Page\'s Content',
                    'external' => 'Redirect to External URL',
                    'file' => 'Open File'
                ],
                'default_value' => 'internal'
            ])
            ->addUrl('post_link_external_url', [
                'label' => 'External URL',
                'required' => 1
            ])
                ->conditional('post_link_type', '==', 'external')
            ->addFile('post_link_file', [
                'label' => 'File',
                'mime_types' => '.doc, .docx, .pdf, .ppt, .pptx, .xls, .xlsx',
                'required' => 1
            ])
                ->conditional('post_link_type', '==', 'file');

        return $postLinkSettings->build();
    }
}

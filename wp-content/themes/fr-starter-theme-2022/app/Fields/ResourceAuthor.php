<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class ResourceAuthor extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $resourceAuthor = new FieldsBuilder('resource_author', [
            'position' => 'side'
        ]);

        $resourceAuthor
            ->setLocation('post_type', '==', 'resource')
            ->addTrueFalse('show_author', [
                'label' => 'Show Author?',
                'default_value' => 1
            ])
            ->addText('resource_author', [
                'label' => 'Author'
            ])
                ->conditional('show_author', '==', 1);

        return $resourceAuthor->build();
    }
}

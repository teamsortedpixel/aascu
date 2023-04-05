<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class PageFields extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $fields = new FieldsBuilder('page_fields', [
            'title' => 'Program Details',
            'position' => 'side'
        ]);

        $fields
            ->setLocation('post_type', '==', 'page');

        $fields
            ->addTrueFalse('hide_selected_program', [
                'default_value' => 0,
                'wrapper' => [
                    'width' => 50
                ]
            ]);

        return $fields->build();
    }
}

<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class DateDisplaySettings extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $fields = new FieldsBuilder('date_display_settings', [
            'label' => 'Date display settings',
            'position' => 'side'
        ]);

        $fields
            ->setLocation('post_type', '==', 'news')
            ->or('post_type', '==', 'resource');

        $fields
            ->addTrueFalse('hide_date', [
                'label' => 'Hide Date in frontend Hero/Card',
                'default_value' => 0
            ]);

        return $fields->build();
    }
}

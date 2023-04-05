<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class ProgramTermFields extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $programTermFields = new FieldsBuilder('program_term_fields', [
            'title' => 'Program Fields'
        ]);

        $programTermFields
            ->setLocation('taxonomy', '==', 'program');

        $programTermFields
            ->addColorPicker('accent_color', [
                'required' => 1,
                'instructions' => 'The color to be used as the background color on tags and the secondary navigation in the main menu.'
            ])
            ->addColorPicker('text_color', [
                'required' => 1,
                'instructions' => 'The color of the text to be used in combination with the `Accent Color`, used on tags and the secondary navigation in the main menu.'
            ]);

        return $programTermFields->build();
    }
}

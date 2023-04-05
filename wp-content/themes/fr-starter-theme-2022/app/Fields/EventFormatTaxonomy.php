<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class EventFormatTaxonomy extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $eventFormatTaxonomy = new FieldsBuilder('event_format_taxonomy', [
            'title' => 'Event Format',
            'position' => 'side'
        ]);

        $eventFormatTaxonomy
            ->setLocation('post_type', '==', 'event');

        $eventFormatTaxonomy
            ->addTaxonomy('format_taxonomy', [
                'label' => 'Select an Event Format',
                'taxonomy' => 'format',
                'field_type' => 'radio',
                'required' => true,
                'add_term' => false,
                'save_terms' => true,
                'load_terms' => true,
            ]);

        return $eventFormatTaxonomy->build();
    }
}

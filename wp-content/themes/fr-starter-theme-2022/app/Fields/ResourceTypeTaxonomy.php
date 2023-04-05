<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class ResourceTypeTaxonomy extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $resourceTypeTaxonomy = new FieldsBuilder('resource_type_taxonomy', [
            'title' => 'Resource Type',
            'position' => 'side'
        ]);

        $resourceTypeTaxonomy
            ->setLocation('post_type', '==', 'resource');

        $resourceTypeTaxonomy
            ->addTaxonomy('resource_type_taxonomy', [
                'label' => 'Select a Resource Type',
                'taxonomy' => 'resource-type',
                'field_type' => 'radio',
                'required' => true,
                'add_term' => false,
                'save_terms' => true,
                'load_terms' => true,
            ]);

        return $resourceTypeTaxonomy->build();
    }
}

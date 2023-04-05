<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class NewsTypeTaxonomy extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $newsTypeTaxonomy = new FieldsBuilder('news_type_taxonomy', [
            'title' => 'News Type',
            'position' => 'side'
        ]);

        $newsTypeTaxonomy
            ->setLocation('post_type', '==', 'news');

        $newsTypeTaxonomy
            ->addTaxonomy('news_type_taxonomy', [
                'label' => 'Select a News Type',
                'taxonomy' => 'news-type',
                'field_type' => 'radio',
                'required' => true,
                'add_term' => false,
                'save_terms' => true,
                'load_terms' => true,
            ]);

        return $newsTypeTaxonomy->build();
    }
}

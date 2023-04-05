<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Fields\Partials\SocialLinks;

class PeoplePostFields extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $fields = new FieldsBuilder('people_post_fields', [
            'title' => 'Personal Details'
        ]);

        $fields
            ->setLocation('post_type', '==', 'people');

        $fields
            ->addText('person_id', [
                'label' => 'Person ID',
                'required' => 1,
                'wrapper' => [
                    'class' => 'fr-no-edit-input',
                    'width' => 30
                ]
            ])
            ->addImage('profile_photo', [
                'wrapper' => [
                    'width' => 70
                ]
            ])
            ->addText('firstname', [
                'required' => 1,
                'wrapper' => [
                    'width' => '50'
                ]
            ])
            ->addText('lastname', [
                'required' => 1,
                'wrapper' => [
                    'width' => '50'
                ]
            ])
            ->addTextArea('title', [
                'wrapper' => [
                    'width' => '50'
                ],
                'rows' => 2
            ])
            ->addTextArea('description', [
                'label' => 'Organization',
                'wrapper' => [
                    'width' => '50'
                ],
                'rows' => 2
            ])
            ->addField('phone', 'phone_number', [
                'default_country' => 'us',
                'wrapper' => [
                    'width' => '50'
                ]
            ])
            ->addEmail('email', [
                'wrapper' => [
                    'width' => '50'
                ]
            ])
            ->addFields($this->get(SocialLinks::class))
            ->addWysiwyg('bio');

        return $fields->build();
    }
}

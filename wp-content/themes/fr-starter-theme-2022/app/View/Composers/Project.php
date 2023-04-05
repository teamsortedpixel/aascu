<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Project extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.content-single-project',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'tags' => $this->getTags(),
            'related_projects' => $this->getRelatedProjects(),
            'go_back_link' => $this->getGoBackLink()
        ];
    }

    public function getTags(){
        $result = [];
        $terms = wp_get_post_terms(get_the_ID(), 'service');

        foreach ($terms?: [] as $term) {
            $result[] = [
                'label' => $term->name,
                'description' => $term->description
            ];
        }

        return $result;
    }

    public function getRelatedProjects(){
        $result = [];
        $show_related_projects = get_field('show_related_projects', get_the_ID());
        $related_projects = get_field('related_projects', get_the_ID());

        if($show_related_projects){
            foreach ($related_projects ?: [] as $i) {
                $result[] = \App\Providers\CardsDataProvider::get($i['project']);
            }
        }

        return $result;
    }

    public function getGoBackLink(){
        $result = false;
        $projects_index_page = get_field('projects_index_page', 'option');

        if($projects_index_page){
            $result = get_the_permalink($projects_index_page);
        }

        return $result;
    }
}

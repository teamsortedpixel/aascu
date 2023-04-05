<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Providers\PostSearchProvider;


class NetworkMap extends Block
{
	/**
	 * The block name.
	 *
	 * @var string
	 */
	public $name = 'Network Map';
	
	/**
	 * Slug
	 *
	 * @var string
	 */
	public $slug = 'fr-page-builder-module-network-map';

	/**
	 * The block description.
	 *
	 * @var string
	 */
	public $description = 'A simple Network Map block.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'fr-page-builder-content-blocks';

	/**
	 * The block icon.
	 *
	 * @var string|array
	 */
	public $icon = ' fricon dashicons-admin-site';

	/**
	 * The block keywords.
	 *
	 * @var array
	 */
	public $keywords = [];

	/**
	 * The block post type allow list.
	 *
	 * @var array
	 */
	public $post_types = [];

	/**
	 * The parent block type allow list.
	 *
	 * @var array
	 */
	public $parent = ['acf/block-container'];

	/**
	 * The default block mode.
	 *
	 * @var string
	 */
	public $mode = 'preview';

	/**
	 * The default block alignment.
	 *
	 * @var string
	 */
	public $align = '';

	/**
	 * The default block text alignment.
	 *
	 * @var string
	 */
	public $align_text = '';

	/**
	 * The default block content alignment.
	 *
	 * @var string
	 */
	public $align_content = '';

	/**
	 * The supported block features.
	 *
	 * @var array
	 */
	public $supports = [
		'align' => false,
		'align_text' => false,
		'align_content' => false,
		'full_height' => false,
		'anchor' => true,
		'mode' => 'edit',
		'multiple' => true,
		'jsx' => true,
	];

	/**
	 * The block preview example data.
	 *
	 * @var array
	 */
	public $example = [
        'attributes' => [
            'preview_image' => 'network-map.png'
        ],
	];

	/**
	 * Data to be passed to the block before rendering.
	 *
	 * @return array
	 */
	public function with()
	{

		$data = [];
		$source = get_field('source');
		$module_id = uniqid('net-map-');

		switch ($source) {
			case 'api':
				$data = $this->GetDataFromAPI();
				break;
			case 'csv':
				$data = $this->GenerateDataArray($this->GetDataFromCSV(get_field('csv_file')));
				break;	
			default:
				break;
		}

		return [
			'data' => $data,
			'source' => get_field('source'),
			'module_id' => $module_id,
			'map_styles' => $this->GetMapStylesFromData($module_id, $data),
			'error_msg' => $this->ShowErrorMessage($data)
		];
	}

	/**
	 * The block field group.
	 *
	 * @return array
	 */
	public function fields()
	{
		$networkMap = new FieldsBuilder('network_map');

		$networkMap
			->addRadio('source', [
				'choices' => [
					'api' => 'iMIS API data.',
					'csv' => 'CSV File'
				],
				'layout' => 'horizontal',
				'default_value' => 'api',
				'wrapper' => [
					'width' => 50
				]
			])
			->addFile('csv_file', [
				'label' => 'CSV File',
				'mime_types' => '.csv',
				'wrapper' => [
					'class' => 'fr-network-map-file-field',
					'width' => 50
				]
			])
				->conditional('source', '==', 'csv')
		;

		return $networkMap->build();
	}

	public function GetDataFromCSV($file){
		$result = [];

		if(!$file || !$file['ID']) return $result;

		$csv = new \ParseCsv\Csv();
		$csv->auto(get_attached_file($file['ID']));
		$result = $csv->data;

		return $result;
	}

	public function GetDataFromAPI(){
		$result = [];
		$data = \App\Providers\ApiProvider::getMapData();

		foreach (isset($data['data']['Items']['$values'])? $data['data']['Items']['$values'] : [] as $d) {
			$item_data = [];

			//get data from $values array
			foreach($d['Properties']['$values']?: [] as $i => $values){
				$item_data[$values['Name']] = $values['Value'] ?? '';
			}

			if(in_array($item_data['Country'], ['Mexico', 'Canada', 'Bahamas'])){
				$state_abv = $item_data['Country'];
				$state_name = $item_data['Country'];
			}else{
				$state_name = $item_data['StateName'];
				$state_abv = $item_data['StateCode'];
			}

			if($state_abv){
				if(array_key_exists($state_abv, $result) == FALSE){
					$result[$state_abv] = [
						'state_name' => $state_name,
						'list' => []
					];
				}

				$result[$state_abv]['list'][] = [
					'name' => $item_data['InstitutionName'] ?? '',
					'city' => $item_data['City'] ?? '',
					'link' => strpos($item_data['Website'] ?? '', '//') !== false ? $item_data['Website'] : '//'.$item_data['Website']
				];
			}
		}

		return $result;
	}

	public function GenerateDataArray($data){
		$result = [];

		foreach ($data?: [] as $d) {
			$state_abv = trim($d['State Abbreviation'] ? $d['State Abbreviation'] : false);
			if($state_abv){
				if(array_key_exists($state_abv, $result) == FALSE){
					$result[$state_abv] = [
						'state_name' => PostSearchProvider::GetStateNameByAbbv($state_abv),
						'list' => []
					];
				}

				$result[$state_abv]['list'][] = [
					'name' => $d['Institution/Organization Name'] ?? '',
					'city' => $d['City'] ?? '',
					'link' => $d['External Link'] ?? ''
				];
			}
		}

		return $result;
	}

	public function GetMapStylesFromData($id, $data = []){
		$result = '';

		foreach ($data as $i => $d) {
			$result .= 
				'.network-map-module[fr-module-id="'.$id.'"] .network-map-container svg rect[state-abv="'.$i.'"], 
				.network-map-module[fr-module-id="'.$id.'"] .network-map-container svg  path[stroke-linecap][state-abv="'.$i.'"]{
					display:block;
					cursor:pointer;
				}
				.network-map-module[fr-module-id="'.$id.'"] .network-map-container .number-container[state-abv="'.$i.'"] .number-container-inner > span::before,
				.network-map-module[fr-module-id="'.$id.'"] .network-map-container .map-aspect-ratio-container .state-label.w-circle[state-abv="'.$i.'"] .circle::before{
					content: "'.count($d['list']).'";
				}
				.network-map-module[fr-module-id="'.$id.'"] .network-map-container .map-aspect-ratio-container .state-label.w-circle[state-abv="'.$i.'"]{
					display:flex;
				}
				.network-map-module[fr-module-id="'.$id.'"] .network-map-container svg path[state-abv="'.$i.'"]{
					pointer-events:initial;
					cursor:pointer;
				}';
		}

		$result = '<style>'.$result.'</style>';


		//remove line breaks and spacings before returning
		return trim(preg_replace('/\t+/', '', str_replace(array("\r", "\n"), '', $result)));
	}

	public function ShowErrorMessage($data){
		$result = false;

		$current_user = wp_get_current_user();
		if (user_can( $current_user, 'administrator' )) {
			if(isset($data['$type']) && strpos($data['$type'], 'Error') !== false){
				$result = $data;
			}
		}

		return $result;
	}

	/**
	 * Assets to be enqueued when rendering the block.
	 *
	 * @return void
	 */
	public function enqueue()
	{
		//
	}
}

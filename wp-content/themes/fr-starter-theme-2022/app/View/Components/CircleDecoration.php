<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CircleDecoration extends Component
{

	public $diameter;
	public $diameter_mobile;
	public $width;
	public $height;
	public $height_mobile;
	public $width_mobile;
	public $style;
	public $position;
	public $id;

	/**
	 * Create a new component instance.
	 *
	 * @return void
	 */
	public function __construct($diameter = '10', $diameterMobile = false, $style = 'colored', $position = [])
	{
		$this->width = $diameter;
		$this->height = $diameter;
		$this->width_mobile = $diameterMobile;
		$this->height_mobile = $diameterMobile;
		$this->style = $style;
		$this->position = self::generatePositionRules($position);
		$this->id = uniqid('dec-');
	}

	public static function getOppositePosition($pos){
		$result = $pos;

		switch ($pos) {
			case 'top':
				$result = 'bottom';
				break;
			case 'bottom':
				$result = 'top';
				break;
			case 'left':
				$result = 'right';
				break;
			case 'right':
				$result = 'left';
				break;
			default:
				break;
		}

		return $result;
	}

	public static function generatePositionRules($position){
		$result = [];

		if(!$position) return $result;

		foreach(['desktop', 'mobile'] as $bp){
			if($position[$bp]){
				$result[$bp] = [];

				foreach (['top', 'right', 'bottom', 'left'] as $pos) {
					if(isset($position[$bp][$pos])){
						$data = str_replace($position[$bp]['unit'], '', $position[$bp][$pos]);
						if(strlen($data)){
							$result[$bp][$pos] = $data . $position[$bp]['unit'];
						}

						if($bp === 'mobile'){
							$opposite = self::getOppositePosition($pos);
							if(isset($result['desktop'][$opposite]) && isset($result[$bp][$pos]) && !isset($result[$bp][$opposite])){
								$result[$bp][$opposite] = 'unset';
							}	
						}
					}

				}
			}
		}

		return $result;
	}

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render()
	{
		return view('components.circle-decoration');
	}
}

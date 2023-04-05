<div class="circle-decoration {{ $style }}" id="{{ $id }}"></div>
@isset($position)
	<style>
		@isset($position['desktop'])
			#{{$id}} {
				width: {{ $width }}px;
				height:{{ $height }}px;
				@foreach ($position['desktop'] as $i => $pos)
					{{ $i }} : {{ $pos }};
				@endforeach
			}
		@endisset
		@isset($position['mobile'])
			@media only screen and (max-width: 767px) {
				#{{$id}} {
					width: {{ $width_mobile }}px;
					height:{{ $height_mobile }}px;
					@foreach ($position['mobile'] as $i => $pos)
						{{ $i }} : {{ $pos }};
					@endforeach
				}
			}
		@endisset
	</style>
@endisset
<a target="{{ $target }}" href="{{ $url }}" class="cta-button {{ @$icon?'icon-btn':'' }} {{ $style }}">@if(@$icon) @svg('images.'.$icon) @endif{{ wp_specialchars_decode($label) }} @if(@$arrow)<b></b>@endif</a>
<div class="search-bar-container">
	<form action="{{ home_url('/') }}" id="{{ $id }}-form">
		<label for="{{ $id }}-input">
			<input type="text" name="s" placeholder="{{ $placeholder_text }}" @if ($default_value)
				value="{{ $default_value }}"
			@endif id="{{ $id }}-input">
			<span class="validation">Please enter a search term.</span>
		</label>
		@include('partials.search-button', ['attr' => 'type="submit" ' . (!$default_value || strlen($default_value) == 0 ? 'disabled="disabled"' : '')])
	</form>
</div>
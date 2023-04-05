<div class="module layout-module {{ $block->classes }} layout-{{ $layouts }}" @if (isset($block->block->anchor)) id="{{ $block->block->anchor }}" @endif>
    @if ($block->preview)
        <div fr-column-inserter style="display: {{in_array($layouts, array_keys($choices)) ? 'none' : ''}}">
            <p>Select a layout to start, layout:</p>
            <div class="btn-group" role="group" aria-label="Select a layout to start editing.">
                @foreach ($choices as $i => $val)
                    <input type="radio" class="btn-check" name="btnradio" value="{{ $i }}" id="lay-{{$block->block->id}}-{{ $i }}" autocomplete="off" {{ $i === $layouts ? 'checked="true"' : '' }}>
                    <label class="btn btn-outline-primary" for="lay-{{$block->block->id}}-{{ $i }}">
                        <b class="btn-group-b" style="background-image:url(@asset('images/column_'.$i.'.svg'));"></b>
                        <span>
                            {{ $val }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>
    @endif
    <div class="container-fluid" {!! $max_width !!}>
        <div class="row">
            <InnerBlocks orientation="horizontal" allowedBlocks='{{ $allowed_blocks }}'/>
        </div>
    </div>
</div>

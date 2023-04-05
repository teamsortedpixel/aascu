<div class="{{ $block->classes }} module module-data-points" @if (isset($block->block->anchor)) id="{{ $block->block->anchor }}" @endif>
    <div class="container">
        <div class="data-point-circle"></div>
        <div class="wysiwyg-content">
            @if ($DataPoints)
                {!! $DataPoints !!}
            @endif
        </div>
    </div>
</div>

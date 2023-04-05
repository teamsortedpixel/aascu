<div class="module wysiwyg-module {{ $block->classes }}" @if(isset($block->block->anchor)) id="{{ $block->block->anchor }}" @endif>
  <div class="container-fluid">
    <div class="row">
      <div class="col wysiwyg-content">
        {!! $content !!}
      </div>
    </div>
  </div>
</div>

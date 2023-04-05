<div class="module module-cta-button {{ $block->classes }} align-{{ $alignment }} wysiwyg-content" @if(isset($block->block->anchor)) id="{{ $block->block->anchor }}" @endif>
  <x-cta-button :label="$label" :type="$type" :post-id="$post_id" :style="$style" :external-url="$external_url" :new-tab="$new_tab" :anchor="$anchor" />
</div>

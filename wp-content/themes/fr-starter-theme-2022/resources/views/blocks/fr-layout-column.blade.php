<div class="fr-col {{ $block->classes }}">
  @if ($block->preview)
      <div class="fr-empty-slot"></div>
  @endif
  <InnerBlocks allowedBlocks='{{ $allowed_blocks }}'/>
</div>

<div class="module accordion-module {{ $block->classes }}"
    @if (isset($block->block->anchor)) id="{{ $block->block->anchor }}" @endif>
    <div class="container-fluid">
        @if ($items)
            <div class="accordion accordion-flush" id="acc-{{ $block->block->id }}">
                @forelse ($items as $i => $item)
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="acc-head-{{ $block->block->id . $i }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#acc-collapse-{{ $block->block->id . $i }}" aria-expanded="false"
                                aria-controls="acc-collapse-{{ $block->block->id . $i }}">
                                <span class="title">{!! $item['title'] !!}</span>
                                <span class="sub-title">{!! $item['sub_title'] !!}</span>
                                <b class="badge"></b>
                            </button>
                        </h6>
                        <div id="acc-collapse-{{ $block->block->id . $i }}" class="accordion-collapse collapse"
                            aria-labelledby="acc-head-{{ $block->block->id . $i }}"
                            data-bs-parent="#acc-{{ $block->block->id }}">
                            <div class="accordion-body">
                                <div class="accordion-inner-content wysiwyg-content">{!! $item['content'] !!}</div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        @endif
    </div>
</div>

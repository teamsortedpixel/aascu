<div class="module fr-block people-list-block" @if (isset($block->block->anchor)) id="{{ $block->block->anchor }}" @endif>
    <div class="container">
        <div class="people-list">
            @foreach($peoples as $member)
                @include('components.people-card', (array) $member)
            @endforeach
        </div>
    </div>
</div>
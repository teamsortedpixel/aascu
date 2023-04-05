<div class="{{ $block->classes }} module-feature-cards module" @if (isset($block->block->anchor)) id="{{ $block->block->anchor }}" @endif>
    <div class="container-fluid">
        <div class="featured-card-title-content">
        {!! $Featured_card_title_content !!}
        </div>
        <div class="featured-card-module fr-card">
            @if ($items)
                @foreach ($items as $item)
                    <div class="featured-image-card-container">
                        @if($item['Featured_image'])
                            <div class="featured-image" style="border-color:{{ $item['Color'] }}">
                                <x-responsive-acf-image :image="$item['Featured_image']" size="thumbnail"/>
                            </div>
                        @else
                            <div class="featured-image empty"></div>
                        @endif
                        <div class="featured-card-content" >
                            <div class="featured-card-title">
                                <span class="card-title featuretitle" data-title="{!! htmlentities($item['Title']) !!}">{!! $item['Title'] !!}</span>
                                @if($item['add_short_description'])
                                <span class="card-small-description feature-small-description" data-title="{!! htmlentities($item['small_descripiton']) !!}">{!! $item['small_descripiton'] !!}</span>
                                @endif
                            </div>
                            <div class="featured-card-button {{ isset($item['label_color']) ? $item['label_color'] : 'white' }}" style="background-color:{{ $item['Color'] ? $item['Color'] : '#000;'  }} ">
                                @if ($item['Button_link'])
                                    <a href="{{ $item['Button_link']['url'] ? $item['Button_link']['url'] : '#' }}" target ="{{ $item['Button_link']['target'] ? $item['Button_link']['target'] : '_self'}}"><span class="button">{!! $item['Button'] !!}<b class="badge"></b></span></a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

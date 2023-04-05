<div class="secondary-nav-dropdown-item" data-item-id="{{ $id }}">
    <a href="{{ $permalink }}">
        <div class="wysiwyg-content">
            <div class="title">
                {!! $title !!}
            </div>
            <div class="desc">
                {!! $description !!}
            </div>
            <span class="arrow-cont">
                @svg('images.arrow')
            </span>
        </div>
    </a>
    <div class="title txt-white pd-20">
        {!! $title !!}
    </div>
    <style>
        [data-item-id="{{ $id }}"] > a{
            background-color:{{ $color }};
        }
        [data-item-id="{{ $id }}"] .wysiwyg-content, 
        [data-item-id="{{ $id }}"] .wysiwyg-content p{
            color:{{ $text_color }};
        }
        [data-item-id="{{ $id }}"] .arrow-cont svg path {
            fill: {{ $text_color }};
        }
    </style>
</div>
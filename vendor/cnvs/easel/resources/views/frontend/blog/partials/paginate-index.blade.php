<ul class="pager">
    {{-- Reverse direction --}}
    @if ($reverse_direction)
        @if ($posts->currentPage() > 1)
            <li class="previous">
                <a href="{!! $posts->url($posts->currentPage() - 1) !!}">
                    <i class="fa fa-angle-left fa-lg"></i>
                    {{ trans('canvas::frontend.previous') }} {{ $tag->tag }}
                </a>
            </li>
        @endif
        @if ($posts->hasMorePages())
            <li class="next">
                <a href="{!! $posts->nextPageUrl() !!}">
                    {{ trans('canvas::frontend.next') }} {{ $tag->tag }}
                    <i class="fa fa-angle-right"></i>
                </a>
            </li>
        @endif
    @else
        @if ($posts->currentPage() > 1)
            <li class="previous">
                <a href="{!! $posts->url($posts->currentPage() - 1) !!}">
                    <i class="fa fa-angle-left fa-lg"></i>
                    {{ trans('canvas::frontend.newer') }} {{ $tag ? $tag->tag : '' }}
                </a>
            </li>
        @endif
        @if ($posts->hasMorePages())
            <li class="next">
                <a href="{!! $posts->nextPageUrl() !!}">
                    {{ trans('canvas::frontend.older') }} {{ $tag ? $tag->tag : '' }}
                    <i class="fa fa-angle-right"></i>
                </a>
            </li>
        @endif
    @endif
</ul>
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
            <br>
            <ul class="pager">
                @if ($tag && $tag->reverse_direction)
                    @if ($post->olderPost($tag))
                        <li class="previous">
                            <a href="{!! $post->olderPost($tag)->url($tag) !!}">
                                <i class="fa fa-angle-left fa-lg"></i>
                                {{ trans('canvas::frontend.previous') }} {{ $tag->tag }}
                            </a>
                        </li>
                    @endif
                    @if ($post->newerPost($tag))
                        <li class="next">
                            <a href="{!! $post->newerPost($tag)->url($tag) !!}">
                                {{ trans('canvas::frontend.next') }} {{ $tag->tag }}
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    @endif
                @else
                    @if ($post->newerPost($tag))
                        <li class="previous">
                            <a href="{!! $post->newerPost($tag)->url($tag) !!}">
                                <i class="fa fa-angle-left fa-lg"></i>
                                {{ trans('canvas::frontend.newer') }}
                            </a>
                        </li>
                    @endif
                    @if ($post->olderPost($tag))
                        <li class="next">
                            <a href="{!! $post->olderPost($tag)->url($tag) !!}">
                                {{ trans('canvas::frontend.older') }}
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    @endif
                @endif
            </ul>
        </div>
    </div>
</div>
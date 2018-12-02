@if (isset($tag->title))
    <hr style="width: 60%">
    <p class="tag-link"><i class="fa fa-fw fa-tag"></i> {!! trans('canvas::frontend.tagged_in', ['tag_url' => '<a href="#">' . ($tag->title ? $tag->title : '') . '</a>']) !!}</p>
    <p class="tag-subtitle">" {{ $tag->subtitle }} "</p>
    <hr style="width: 60%">
@endif
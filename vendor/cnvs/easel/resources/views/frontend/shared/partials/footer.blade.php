<div class="container">
    @if(!empty(\Canvas\Models\Settings::disqus()))
        @include('canvas::frontend.blog.partials.disqus')
    @endif
    @if(!empty(\Canvas\Models\Settings::changyanAppid()) && !empty(\Canvas\Models\Settings::changyanConf()))
        @include('canvas::frontend.blog.partials.changyan')
    @endif
    <div style="text-align: center">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <hr>
                <p class="small">{!! trans('canvas::frontend.powered_by', ['canvas_url' => '<a href="https://cnvs.io" target="_blank">Canvas</a>']) !!} &#183; <a href="{!! route('canvas.admin') !!}"><i class="fa fa-lock"></i> {{ trans('canvas::frontend.sign_in') }}</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- scroll to top button -->
<span id="top-link-block" class="hidden hover-button">
    <a id="scroll-to-top" href="#top">{{ trans('canvas::frontend.scroll_to_top') }}</a>
</span>

@if (!empty(\Canvas\Models\Settings::gaId()))
    @include('canvas::frontend.blog.partials.analytics')
@endif
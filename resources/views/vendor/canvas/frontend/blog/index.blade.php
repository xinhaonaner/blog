@extends('canvas::frontend.layout')

@if (isset($tag->title))
    @section('title', \Canvas\Models\Settings::blogTitle().' | '.$tag->title)
@else
    @section('title', \Canvas\Models\Settings::blogTitle().' | Blog')
@endif
@section('og-title', \Canvas\Models\Settings::blogTitle())
@section('twitter-title', \Canvas\Models\Settings::blogTitle())
@section('og-description', \Canvas\Models\Settings::blogDescription())
@section('twitter-description', \Canvas\Models\Settings::blogDescription())

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-lg-pull-1 col-md-pull-1" style="position: fixed">
                <img src="https://cdn.weifengchuxing.com/app/upload/client/2018-12-02/UAfC4WDD7hTRS8CHvUzHvGmjOV2ricMSPnGscttN.png" alt="支付宝红包">
            </div>
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                @include('canvas::frontend.blog.partials.tag')
                @include('canvas::frontend.blog.partials.posts')
                @include('canvas::frontend.blog.partials.paginate-index')
            </div>
        </div>
    </div>
@stop

@section('unique-js')
    <script src="{{ elixir('vendor/canvas/assets/js/frontend.js') }}" charset="utf-8"></script>
@endsection
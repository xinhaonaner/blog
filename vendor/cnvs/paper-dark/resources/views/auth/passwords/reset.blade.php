@extends('canvas::backend.layout')

@section('title')
    <title>{{ \Canvas\Models\Settings::blogTitle() }} | Reset Password</title>
@stop

@section('login')
    <div class="login-container">
        @include('canvas::backend.shared.partials.errors')
        @include('canvas::auth.passwords.partials.reset-form')
    </div>
@endsection

@section('unique-js')
    @include('canvas::backend.shared.components.show-password', ['inputs' => 'input[name="password"]'])
@stop
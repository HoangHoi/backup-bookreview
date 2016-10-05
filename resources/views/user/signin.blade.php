@extends('layouts.usermaster')

@section('content')
<div class="containter">
    <div class="row">
        <div class="col-md-6 col-md-offset-1">
            @include('includes.error')
            @include('includes.message')
            <h1 style="text-align: center;">{{ trans('user.actions.login') }}</h1>
            {!! Form::open(['route' => 'signin' ,'method' => 'post']) !!}
                <div class="form-group">
                    {!! Form::label('email', trans('user.email')) !!}
                    {!! Form::text('email', null, [
                        'class' => 'form-control',
                        'id' => 'email',
                    ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('password', trans('user.password')) !!}
                    {!! Form::password('password', [
                        'class' => 'form-control',
                        'id' => 'password',
                    ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::submit(trans('user.actions.login'), [
                        'class' => 'form-control btn btn-success btn-login',
                    ]) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

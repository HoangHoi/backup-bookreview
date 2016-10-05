@extends('layouts.usermaster')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            @include('includes.error')
            @include('includes.message')
            <h1 style="text-align: center;">{{ trans('user.res_title') }}</h1>
            {!! Form::open(['route' => 'signup' , 'method' => 'post']) !!}
                <div class="form-group">
                    {!! Form::label('gender', trans('user.gender')) !!}
                    {!! Form::select(
                        'gender',
                        [
                            true => trans('user.male'),
                            false => trans('user.female'),
                        ],
                        null,
                        [
                            'class' => 'form-control sl-gender',
                            'id' => 'gender',
                        ]
                    ) !!}
                </div>
                <div class="form-group fg-name">
                    {!! Form::label('name', trans('user.name')) !!}
                    {!! Form::text('name', old('name'), [
                        'class' => 'form-control',
                        'id' => 'name'
                    ]) !!}
                </div>
                <div class="form-group fg-email">
                    {!! Form::label('email', trans('user.email')) !!}
                    {!! Form::text('email', old('email'), [
                        'class' => 'form-control',
                        'id' => 'email',
                    ]) !!}
                </div>
                <div class="form-group fg-password">
                    {!! Form::label('password', trans('user.password')) !!}
                    {!! Form::password('password', [
                        'class' => 'form-control',
                        'id' => 'password',
                    ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('password_confirm', trans('user.password_repeat')) !!}
                    {!! Form::password('password_confirmation', [
                        'class' => 'form-control',
                        'id' => 'password_confirm',
                    ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::submit(trans('user.actions.register'), [
                        'class' => 'form-control btn btn-success btn-register',
                    ]) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

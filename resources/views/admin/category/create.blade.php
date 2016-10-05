@extends('layouts.adminMaster')

@section('page_title', trans('admin.create', ['name' => 'category']))

@section('main_title', trans('admin.create', ['name' => 'category']))

@section('content')
<div class="entry_form" style="">
    {!! Form::open(['action' => 'Admin\CategoryController@store', 'class' => 'form-horizontal', 'method' => 'post']) !!}
        <div class="form-group">
            {!! Form::label('category_parent_id', trans('category.category_parent_id'), ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-8">
                {!! Form::select('category_parent_id', $categories, null, ['class' => 'form-control', 'placeholder' => trans('category.choose_category_parent')]); !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('name', trans('category.category_content'), ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('category.write_category')]) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-3 col-md-offset-9">
                {!! Form::submit(trans('admin.save'), ['class' => 'btn btn-primary btn_save']) !!}
            </div>
        </div>
    {!! Form::close() !!}
</div>
@endsection

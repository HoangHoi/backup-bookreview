@extends('layouts.adminMaster')

@section('page_title', trans('admin.create', ['name' => trans('admin.book')]))

@section('main_title', trans('admin.create', ['name' => trans('admin.book')]))

@section('content')
<div class="entry_form" style="">
    {!! Form::open([
        'action' => 'Admin\BookController@store',
        'class' => 'form-horizontal',
        'method' => 'post',
        'files' => true,
    ]) !!}
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    {!! Form::label(
                        'category_id',
                        trans('book.category_id'),
                        ['class' => 'col-md-3 control-label']
                    ) !!}
                    <div class="col-md-9">
                        {!! Form::select('category_id', $categories, null, [
                            'class' => 'form-control',
                            'placeholder' => trans('book.choose_category'),
                        ]); !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('title', trans('book.title'), ['class' => 'col-md-3 control-label']) !!}
                    <div class="col-md-9">
                        {!! Form::text('title', null, [
                            'class' => 'form-control',
                            'placeholder' => trans('book.write', ['name' => trans('book.title')]),
                        ]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('author', trans('book.author'), ['class' => 'col-md-3 control-label']) !!}
                    <div class="col-md-9">
                        {!! Form::text('author', null, [
                            'class' => 'form-control',
                            'placeholder' => trans('book.write', ['name' => trans('book.author')]),
                        ]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('num_page', trans('book.num_page'), ['class' => 'col-md-3 control-label']) !!}
                    <div class="col-md-9">
                        {!! Form::number('num_page', 0, [
                            'class' => 'form-control',
                            'placeholder' => trans('book.write', ['name' => trans('book.num_page')]),
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-3" style="height: 13em">
                {!! Form::file('book_image', [
                    'style' => 'display: none;',
                    'onchange' => 'addNewImage(this, "#book_image")',
                ]) !!}
                <img title="{!! trans('book.choose_book_image') !!}"
                    src="{!! asset(config('fileupload.book_image_dir') . config('common.book_image_default')) !!}"
                    height="100%"
                    onclick="$('[name = book_image]').click();"
                    id="book_image"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label(
                        'description',
                        trans('book.description'),
                        ['class' => 'col-md-2 control-label']
                    ) !!}
                    <div class="col-md-8">
                        {!! Form::textarea('description', null, [
                            'class' => 'form-control',
                            'placeholder' => trans('book.write', ['name' => trans('book.description')]),
                        ]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('published_at', trans('book.published_at'), ['class' => 'col-md-2 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text(
                            'published_at',
                            \Carbon\Carbon::now()->format(config('common.publish_date_format')),
                            ['placeholder' => trans('book.date_format')]
                        ) !!}
                    </div>
                </div>
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

@extends('layouts.adminMaster')

@section('page_title', trans('admin.list', ['name' => trans('admin.book')]))

@section('main_title', trans('admin.list', ['name' => trans('admin.book')]))

@section('content')
<div id="message"></div>
<table id="table" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>{!! trans('book.title') !!}</th>
            <th>{!! trans('book.author') !!}</th>
            <th>{!! trans('book.pages') !!}</th>
            <th>{!! trans('book.category') !!}</th>
            <th>{!! trans('book.published_at') !!}</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th></th>
            <th>{!! trans('book.title') !!}</th>
            <th>{!! trans('book.author') !!}</th>
            <th>{!! trans('book.pages') !!}</th>
            <th>{!! trans('book.category') !!}</th>
            <th>{!! trans('book.published_at') !!}</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
<!-- Modal edit-->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{!! trans('admin.update', ['name' => trans('admin.book')]) !!}</h4>
                <!--end modal-header-->
            </div>
            <div class="modal-body">
                <div id="modal_message"></div>
                {!! Form::open([
                    'id' => 'form_modal',
                    'class' => 'form-horizontal',
                    'method' => 'post',
                    'files' => true,
                ]) !!}
                    {!! Form::hidden('id') !!}
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                {!! Form::label(
                                    'category_id',
                                    trans('book.category_id'),
                                    ['class' => 'col-md-3 control-label']
                                ) !!}
                                <div class="col-md-9">
                                    {!! Form::select('category_id', [], null, [
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
                                'id' => 'book-image-file',
                            ]) !!}
                            {!! Form::hidden('reset_image') !!}
                            <img title="{!! trans('book.choose_book_image') !!}"
                                data-default-image="{!! asset(config('fileupload.book_image_dir')
                                    . config('common.book_image_default'))
                                !!}"
                                height="100%"
                                onclick="$('[name = book_image]').click();"
                                id="book_image"
                            />
                            <div style="position: absolute; bottom: 0">
                                {!! Form::button(trans('book.remove'), [
                                    'class' => 'btn btn-success btn-xs',
                                    'id' => 'remove-image',
                                ]) !!}
                                {!! Form::button(trans('book.default'), [
                                    'class' => 'btn btn-danger btn-xs',
                                    'id' => 'default-image',
                                ]) !!}
                            </div>
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
                                {!! Form::label(
                                    'published_at',
                                    trans('book.published_at'),
                                    ['class' => 'col-md-2 control-label']
                                ) !!}
                                <div class="col-md-8">
                                    {!! Form::text('published_at', null, ['placeholder' => trans('book.date_format')]) !!}
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
            <!--end modal-content-->
        </div>
    </div>
</div>
@endsection

@section('style')
<style>
    .book-image {
        float: right;
        width: 100%;
        padding: 2%;
        margin-top: 1.3em;
        border: 1px none;
        background: rgb(255, 255, 255) none repeat scroll 0% 0%;
        box-shadow: 0px 1px 4px rgb(204, 204, 204), 0px 0px 10px rgb(204, 204, 204) inset;
    }
</style>
@endsection

@section('script')
{!! Html::script('js/adminBook.js') !!}
<script type="text/javascript">
    $(document).ready(function () {
        var url = {
            'list': '{!! route('admin.book.ajaxList') !!}',
            'update': '{!! route('admin.book.ajaxUpdate') !!}',
            'delete': '{!! route('admin.book.ajaxDelete') !!}',
            'categories': '{!! route('admin.category.ajaxList') !!}',
        };
        var lang = {
            'trans': {
                'unknown_error': '{!! trans('dataTable.unknown_error') !!}',
                'confirm_select_all': '{!! trans('dataTable.confirm_select_all') !!}',
                'confirm_delete': '{!! trans('dataTable.confirm_delete') !!}',
                'description': '{!! trans('book.description') !!}:',
                'load_categories_error': '{!! trans('book.load_categories_error') !!}',
            },
            'button_text': {
                'select_page': '{!! trans('dataTable.select_page') !!}',
                'select_all': '{!! trans('dataTable.select_all') !!}',
                'unselect': '{!! trans('dataTable.unselect') !!}',
                'delete_select': '{!! trans('dataTable.delete_select') !!}',
            },
            'response': {
                'key_name': '{!! config('common.flash_level_key') !!}',
                'message_name': '{!! config('common.flash_message') !!}',
            }
        };
        var Book = new book();
        Book.init(url, lang);
    });
</script>
@endsection

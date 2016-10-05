@extends('layouts.adminMaster')

@section('page_title', trans('admin.list', ['name' => 'category']))

@section('main_title', trans('admin.list', ['name' => 'category']))

@section('content')
<div id="message"></div>
<table id="table" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>{!! trans('category.category_name') !!}</th>
            <th>{!! trans('category.category_parent') !!}</th>
            <th>{!! trans('category.number_book') !!}</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th></th>
            <th>{!! trans('category.category_name') !!}</th>
            <th>{!! trans('category.category_parent') !!}</th>
            <th>{!! trans('category.number_book') !!}</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
<!-- Modal edit-->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{!! trans('admin.update', ['name' => trans('admin.category')]) !!}</h4>
                <!--end modal-header-->
            </div>
            <div class="modal-body">
                <div id="modal_message"></div>
                {!! Form::open(['class' => 'form-horizontal', 'method' => 'post', 'id' => 'form_modal']) !!}
                    {!! Form::hidden('id', null) !!}
                    <div class="form-group">
                        {!! Form::label('category_parent_id', trans('category.category_parent_id'), [
                            'class' => 'col-md-3 control-label'
                        ]) !!}
                        <div class="col-md-8">
                            {!! Form::select('category_parent_id', $categories, null, [
                                'class' => 'form-control',
                                'placeholder' => trans('category.choose_category_parent')
                            ]); !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label(
                            'name',
                            trans('category.category_content'),
                            ['class' => 'col-md-3 control-label']
                        ) !!}
                        <div class="col-md-8">
                            {!! Form::text('name', null, [
                                'class' => 'form-control',
                                'placeholder' => trans('category.write_category')
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3 col-md-offset-9">
                            {!! Form::submit(trans('admin.save'), ['class' => 'btn btn-primary btn_save']) !!}
                        </div>
                    </div>
                {!! Form::close() !!}
                <!--end modal-body-->
            </div>
            <!--end modal-content-->
        </div>
    </div>
</div>
@endsection

@section('script')
{!! Html::script('js/adminCategory.js') !!}
<script type="text/javascript">
    $(document).ready(function () {
        var url = {
            'listAjax': '{!! route('admin.category.ajaxList') !!}',
            'update': '{!! route('admin.category.ajaxUpdate') !!}',
            'delete': '{!! route('admin.category.ajaxDelete') !!}',
        };
        var lang = {
            'trans': {
                'unknown_error': '{!! trans('dataTable.unknown_error') !!}',
                'confirm_select_all': '{!! trans('dataTable.confirm_select_all') !!}',
                'confirm_delete': '{!! trans('dataTable.confirm_delete') !!}',
            },
            'button_text': {
                'select_page': '{!! trans('dataTable.select_page') !!}',
                'select_all': '{!! trans('dataTable.select_all') !!}',
                'unselect': '{!! trans('dataTable.unselect') !!}',
                'delete_select': '{!! trans('dataTable.delete_select') !!}'
            },
            'response': {
                'key_name': '{!! config('common.flash_level_key') !!}',
                'message_name': '{!! config('common.flash_message') !!}',
            }
        };
        var Category = new category();
        Category.init(url, lang);
    });
</script>
@endsection

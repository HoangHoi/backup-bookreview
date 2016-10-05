@extends('layouts.adminMaster')

@section('page_title', trans('admin.title_home'))

@section('main_title', trans('admin.list', ['name' => trans('admin.user')]) )

@section('content')
<div id="message"></div>
<table id="table" class="display" cellspacing="0">
    <thead>
        <tr>
            <th></th>
            <th>{!! trans('user.name') !!}</th>
            <th>{!! trans('user.email') !!}</th>
            <th>{!! trans('user.gender') !!}</th>
            <th>{!! trans('user.reviews') !!}</th>
            <th>{!! trans('user.read_books') !!}</th>
            <th>{!! trans('user.reading_books') !!}</th>
            <th>{!! trans('user.profile.following') !!}</th>
            <th>{!! trans('user.profile.follower') !!}</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th></th>
            <th>{!! trans('user.name') !!}</th>
            <th>{!! trans('user.email') !!}</th>
            <th>{!! trans('user.gender') !!}</th>
            <th>{!! trans('user.reviews') !!}</th>
            <th>{!! trans('user.read_books') !!}</th>
            <th>{!! trans('user.reading_books') !!}</th>
            <th>{!! trans('user.profile.following') !!}</th>
            <th>{!! trans('user.profile.follower') !!}</th>
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
                <h4 class="modal-title" id="modal-title"></h4>
                <!--end modal-header-->
            </div>
            <div class="modal-body">
                <div id="modal_message"></div>
                {!! Form::open([
                    'class' => 'form-horizontal',
                    'method' => 'post',
                    'id' => 'form_modal',
                ]) !!}
                    {!! Form::hidden('id', null) !!}
                    <div class="form-group">
                        {!! Form::label('name', trans('user.name'), [
                            'class' => 'col-md-3 control-label'
                        ]) !!}
                        <div class="col-md-8">
                            {!! Form::text('name', null, [
                                'class' => 'form-control',
                                'placeholder' => trans('user.write_content', ['name' => trans('user.name')])
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('email', trans('user.email'), [
                            'class' => 'col-md-3 control-label'
                        ]) !!}
                        <div class="col-md-8">
                            {!! Form::email('email', null, [
                                'class' => 'form-control',
                                'placeholder' => trans('user.write_content', ['name' => trans('user.email')])
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('gender', trans('user.gender'), [
                            'class' => 'col-md-3 control-label'
                        ]) !!}
                        <div class="col-md-8">
                            {!! Form::select(
                                'gender',
                                [
                                    config('common.gender.male') => trans('user.male'),
                                    config('common.gender.female') => trans('user.female'),
                                ],
                                null,
                                [
                                    'class' => 'form-control',
                                ]
                            ); !!}
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
{!! Html::script('js/adminUser.js') !!}
<script type="text/javascript">
    var url = {
        'create': '{!! route('admin.user.ajaxCreate') !!}',
        'list': '{!! route('admin.user.ajaxList') !!}',
        'update': '{!! route('admin.user.ajaxUpdate') !!}',
        'delete': '{!! route('admin.user.ajaxDelete') !!}',
        'reset_password': '{!! route('admin.user.ajaxResetPassword') !!}',
    };
    var lang = {
        'trans': {
            'unknown_error': '{!! trans('dataTable.unknown_error') !!}',
            'confirm_select_all': '{!! trans('dataTable.confirm_select_all') !!}',
            'confirm_delete': '{!! trans('dataTable.confirm_delete') !!}',
            'create_user': '{!! trans('user.create_user') !!}',
            'update_user': '{!! trans('user.update_user') !!}',
            'confirm_reset_password': '{!! trans('user.confirm_reset_password') !!}',
        },
        'button_text': {
            'select_page': '{!! trans('dataTable.select_page') !!}',
            'select_all': '{!! trans('dataTable.select_all') !!}',
            'unselect': '{!! trans('dataTable.unselect') !!}',
            'delete_select': '{!! trans('dataTable.delete_select') !!}',
            'create_user': '{!! trans('user.create_user') !!}',
            'reset_password': '{!! trans('user.reset_password') !!}',
        },
        'response': {
            'key_name': '{!! config('common.flash_level_key') !!}',
            'message_name': '{!! config('common.flash_message') !!}',
        }
    };
    var User = new user();
    User.init(url, lang);
</script>
@endsection

@extends('layouts.adminMaster')

@section('page_title', trans('admin.list', ['name' => trans('admin.book_request')]))

@section('main_title', trans('admin.list', ['name' => trans('admin.book_request')]))

@section('content')
<div id="message"></div>
<table id="table" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>{!! trans('bookRequest.user_name') !!}</th>
            <th>{!! trans('book.title') !!}</th>
            <th>{!! trans('book.author') !!}</th>
            <th>{!! trans('bookRequest.date_time') !!}</th>
            <th>{!! trans('bookRequest.accept') !!}</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th></th>
            <th>{!! trans('bookRequest.user_name') !!}</th>
            <th>{!! trans('book.title') !!}</th>
            <th>{!! trans('book.author') !!}</th>
            <th>{!! trans('bookRequest.date_time') !!}</th>
            <th>{!! trans('bookRequest.accept') !!}</th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
@endsection

@section('script')
{!! Html::script('js/adminBookRequest.js') !!}
<script type="text/javascript">
    $(document).ready(function () {
        var url = {
            'list': '{!! route('admin.bookRequest.ajaxList') !!}',
            'accept': '{!! route('admin.user.ajaxAccept') !!}',
            'delete': '{!! route('admin.bookRequest.ajaxDelete') !!}',
        };
        var lang = {
            'trans': {
                'unknown_error': '{!! trans('dataTable.unknown_error') !!}',
                'confirm_select_all': '{!! trans('dataTable.confirm_select_all') !!}',
                'confirm_delete': '{!! trans('dataTable.confirm_delete') !!}',
                'confirm_accept': '{!! trans('bookRequest.confirm_accept') !!}',
            },
            'button_text': {
                'select_page': '{!! trans('dataTable.select_page') !!}',
                'select_all': '{!! trans('dataTable.select_all') !!}',
                'unselect': '{!! trans('dataTable.unselect') !!}',
                'accept_select': '{!! trans('bookRequest.accept') !!}',
                'delete_select': '{!! trans('dataTable.delete_select') !!}',
            },
            'response': {
                'key_name': '{!! config('common.flash_level_key') !!}',
                'message_name': '{!! config('common.flash_message') !!}',
            }
        };
        var BookRequest = new bookRequest();
        BookRequest.init(url, lang);
    });
</script>
@endsection

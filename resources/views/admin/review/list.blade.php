@extends('layouts.adminMaster')

@section('page_title', trans('admin.list', ['name' => trans('admin.review')]))

@section('main_title', trans('admin.list', ['name' => trans('admin.review')]))

@section('content')
<div id="message"></div>
<table id="table" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>{!! trans('review.book') !!}</th>
            <th>{!! trans('review.user') !!}</th>
            <th>{!! trans('review.content') !!}</th>
            <th><i class="fa fa-comments-o" aria-hidden="true"></i></th>
            <th><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th></th>
            <th>{!! trans('review.book') !!}</th>
            <th>{!! trans('review.user') !!}</th>
            <th>{!! trans('review.content') !!}</th>
            <th><i class="fa fa-comments-o" aria-hidden="true"></i></th>
            <th><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
<!-- Modal show-->
<div class="modal fade" id="review-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="review-title" style="font-weight: 900; text-align: center;"></h4>
                <!--end modal-header-->
            </div>
            <div class="modal-body" style="position: relative; overflow: hidden">
                <div class="panel-body">
                    <div style="float: right; width: 30%; margin: 20px;">
                        <img alt="{!! trans('book.book_image') !!}"
                            class="img-thumbnail"
                            style="width: 100%; text-align: center;"
                            id="review-image"
                        />
                    </div>
                    <p id="review-content" style="text-align: justify;"></p>
                    <div style="text-align: center; float: right; margin: 1em 10% 2em 0;">
                        <em id="review-create-date"></em>
                        <div style="color: blue" id="review-create-user"></div>
                    </div>
                </div>
            </div>
            <!--end modal-content-->
        </div>
    </div>
</div>
<!-- Modal book-->
<div class="modal fade" id="book-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal-title"></h4>
                <!--end modal-header-->
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <img alt="{!! trans('book.book_image') !!}"
                            style="width: 100%; text-align: center;"
                            id="book-image"
                        />
                    </div>
                    <div class="col-md-8">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm-3">{!! trans('book.category_id') !!}</div>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-8" id="book-category"></div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm-3">{!! trans('book.title') !!}</div>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-8" id="book-title"></div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm-3">{!! trans('book.author') !!}</div>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-8" id="book-author"></div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm-3">{!! trans('book.num_page') !!}</div>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-8" id="book-num-page"></div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm-3">{!! trans('book.published_at') !!}</div>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-8" id="book-publish-date"></div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm-3">{!! trans('book.rate_point') !!}</div>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-8" id="book-rate-point"></div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm-3">{!! trans('book.favorites') !!}</div>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-8" id="book-favorite"></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row" style="margin-top: 2em;">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <p id="book-description"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end modal-content-->
        </div>
    </div>
</div>
@endsection

@section('script')
{!! Html::script('js/adminReview.js') !!}
<script type="text/javascript">
    $(document).ready(function () {
        var url = {
            'list': '{!! route('admin.review.ajaxList') !!}',
            'delete': '{!! route('admin.review.ajaxDelete') !!}',
            'getBook': '{!! route('admin.book.ajaxGetOne') !!}',
        };
        var lang = {
            'trans': {
                'unknown_error': '{!! trans('dataTable.unknown_error') !!}',
                'confirm_select_all': '{!! trans('dataTable.confirm_select_all') !!}',
                'confirm_delete': '{!! trans('dataTable.confirm_delete') !!}',
                'book_details': '{!! trans('book.details') !!}',
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
        var Review = new review();
        Review.init(url, lang);
    });
</script>
@endsection

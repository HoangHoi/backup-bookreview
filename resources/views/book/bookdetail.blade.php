@extends('layouts.usermaster')

@section('head')
{{ Html::style('css/book.css') }}
<style>
    #your-rate {
        background-color: wheat;
        padding: 10px 5px 5px 5px;
        vertical-align: middle;
        margin-top: 1em;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="book-detail">
        <div class="panel panel-default book-content col-sm-10 col-sm-offset-1"
            style="height: auto"
            data-book-id="{!! $book->id !!}"
            data-book-favorite="{!! empty($book->favorites[0]) ? 0 : 1 !!}"
            data-book-mark="{!!
                empty($book->marks[0]) ?
                    config('book.actions.marks.none') :
                    ($book->marks[0]->action == config('book.db.read') ?
                        config('book.actions.marks.read') :
                        config('book.actions.marks.reading')
                    )
            !!}"
        >
            <div class="panel-body">
                <div class="book-top">
                    <i class="fa {!! empty($book->favorites[0]) ? 'fa-bookmark-o' : 'fa-bookmark' !!} book-favorite"
                        aria-hidden="true"
                    ></i>
                    <a href="{{ route('getCreateReview', $book->id) }}"
                        class="btn btn-info"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="{{ trans('user.review.msg_tooltip') }}"
                    >
                        {{ trans('user.review.write') }}
                    </a>
                    <div class="fa-pull-right dropdown" style="color: tomato; cursor: pointer">
                        <div class="dropdown-toggle" data-toggle="dropdown">
                            <i class="current-mark fa {!!
                                empty($book->marks[0]) ?
                                    '' :
                                    ($book->marks[0]->action == config('book.db.read') ? 'fa-file-text' : 'fa-file-text-o')
                            !!}" aria-hidden="true"></i>&nbsp;
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </div>
                        <ul class="dropdown-menu menu-mark">
                            <li data-mark="{!! config('book.actions.marks.reading') !!}">
                                {!! trans('book.mark_as.reading') !!}
                                <i class="fa fa-file-text-o fa-pull-right" aria-hidden="true"></i>
                            </li>
                            <li data-mark="{!! config('book.actions.marks.read') !!}">
                                {!! trans('book.mark_as.read') !!}
                                <i class="fa fa-file-text fa-pull-right" aria-hidden="true"></i>
                            </li>
                            <li data-mark="{!! config('book.actions.marks.none') !!}">
                                {!! trans('book.mark_as.null') !!}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="book-info" style="padding-right: 20px; width: 100%; float: none">
                            <div class="book-image">
                                <img src="{{ $book->book_image }}">
                            </div>
                            <div class="book-detail" style="text-align: center;">
                                <div style="font-weight: 900; color: blue; font-size: 1.2em; margin-bottom: 0.2em">
                                    <a href="{{ route('book.show', $book->id) }}">{{ $book->title }}</a>
                                </div>
                                <div style="color: #008340; margin-bottom: 0.1em">{{ $book->author }}</div>
                                <div style="margin-bottom: 0.3em"><em>{{ $book->published_at }}</em></div>
                                <div class="br-wrapper br-theme-fontawesome-stars">
                                    <select class="book-start" style="display: none;">
                                        @foreach (range(1, config('common.rate_point_max')) as $i)
                                            <option
                                                value="{!! $i !!}"
                                                {!! ceil($book->avg_rate_point) == $i ? 'selected="selected"' : '' !!}
                                            ></option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn btn-primary" id="button-rate-book">
                                    {!! trans('book.rate_book') !!}
                                </button>
                                <div id="your-rate" style="display: none">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {!! trans('book.your_rate') !!}
                                        </div>
                                        <div class="col-md-8">
                                            <select class="rate-book" style="display: none;">
                                                @foreach (range(1, config('common.rate_point_max')) as $i)
                                                    <option
                                                        value="{!! $i !!}"
                                                        {!! !empty($book->rates[0]) && ceil($book->rates[0]->point) == $i ? 'selected="selected"' : '' !!}
                                                    ></option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="book-board-description board-description" style="height: auto">
                            <div style="text-align: center; margin-top:5px;">
                                <i class="fa fa-2x fa-cog" aria-hidden="true" style="color: black;"></i>
                            </div>
                            <div class="description-content" style="padding: 15px;">
                                <h5 style="font-size: 1.3em; color: white;">
                                    {{ $book->description }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="list-book-review">
        @if (count($reviews))
            @foreach($reviews as $review)
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="item-review" data-review-id="{{ $review->id }}">
                    <?php $pos = strpos($review->content, '.', trans('review.limit_summary')); ?>
                        <article class="post-summary">
                            {!! substr($review->content, 0, $pos) !!}
                        </article>
                        <a href="{{ route('reviews.show', $review->id) }}" class="button-round button-continue">{{ trans('book.continue_reading') }}</a>
                        <hr>
                        <ul class="post-info-wrapper list-inline">
                            <li class="post-info">{{ trans('user.review.writed_at', ['time' => $review->created_at]) }}</li>
                            <li class="post-info">|</li>
                            <li>
                                <a href="{{ route('users.show', ['id' => $review->user->id]) }}" class="post-info when-link">
                                    <span class="user-name">{{ trans('user.write', ['name' => $review->user->name]) }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="text-center">
        {!! $reviews->links() !!}
    </div>
</div>
@endsection

@section('script')
{{ Html::script('js/book.js') }}
<script>
    $(document).ready(function () {
        var Book = new book();
        Book.init(
            {
                'favorite': '{!! route('book.favorite') !!}',
                'mark': '{!! route('book.mark') !!}',
                'login': '{!! route('getSignin') !!}',
                'rate': '{!! route('book.rate') !!}',
            },
            {
                'action': '{!! config('book.action') !!}',
                'actions': {
                    'active': '{!! config('book.actions.active') !!}',
                    'deactive': '{!! config('book.actions.deactive') !!}',
                    'marks': {
                        'read': '{!! config('book.actions.marks.read') !!}',
                        'reading': '{!! config('book.actions.marks.reading') !!}',
                        'none': '{!! config('book.actions.marks.none') !!}',
                    },
                    'rates': {
                        'bookRate': '{!! config('book.actions.rates.book_rate') !!}',
                        'yourRate': '{!! config('book.actions.rates.your_rate') !!}',
                    },
                },
                'result': '{!! config('book.result') !!}',
                'results': {
                    'success': '{!! config('book.results.success') !!}',
                    'warning': '{!! config('book.results.warning') !!}',
                    'fail': '{!! config('book.results.fail') !!}',
                },
            },
            {
                'comfirm_login': '{!! trans('book.comfirm_login') !!}',
            }
        );
        $('.book-start').barrating('show', {
            theme: 'fontawesome-stars',
            hoverState: false,
            readonly: true,
        });
        $('#button-rate-book').on('click', function () {
            $('#your-rate').show();
            $('#button-rate-book').hide();
        });
    });
</script>
@endsection

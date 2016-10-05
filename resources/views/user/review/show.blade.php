@extends('layouts.usermaster')

@section('content')

<div class="container">
    @include('includes.notification')
    <div class="row preview-book">
        <div class="col-md-3 book-img pull-left">
            <img src="{{ $review->book->book_image }}" alt="book-img">
        </div>
        <div class="col-md-9 description-book">
            <div class="row">
                <h3>{{ $review->book->title }}</h3>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <strong>{{ trans('book.author') }}</strong>
                </div>
                <div class="col-sm-6">
                    <p>{{ $review->book->author }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <strong>{{ trans('book.published_at') }}</strong>
                </div>
                <div class="col-sm-6">
                    <p>{{ $review->book->published_at }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <strong>{{ trans('book.num_page') }}</strong>
                </div>
                <div class="col-sm-6">
                    <p>{{ $review->book->num_page }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container review-detail">
    <div class="col-md-10 col-md-offset-2">
        <div class="post-preview">
            <div class="post-subtitle" id="review-content">
                {!! $review->content !!}
            </div>
            <p class="post-meta">
                {{ trans('user.review.posted_by') }}
                <a href="{{ route('users.show', $review->user->id) }}">
                    <strong>{{ $review->user->name }}</strong>
                </a>
                {{ trans('user.created_at', ['time' => $review->created_at]) }}
            </p>
            @if (Auth::check())
                @if (Auth::user()->id === $review->user->id)
                    <div class="btn-review-action">
                        <button class="btn btn-info modal-edit-review" data-toggle="modal" data-target="#review-modal" data-url-put-edit-review="{{ route('reviews.update', $review->id) }}">{{ trans('user.actions.edit') }}</button>
                        {{ Form::open(['route' => ['reviews.destroy', $review->id], 'method' => 'delete']) }}
                            {{ Form::submit(trans('user.actions.delete'), ['class' => 'btn btn-info modal-edit-review']) }}
                        {{ Form::close() }}
                    </div>
                @else
                    <button class="btn btn-like-review btn-info" data-review-id={{ $review->id }} data-url-post-like-review="{{ route('postLikeReview') }}">
                        @if ($review->liked)
                            {{ trans('user.actions.unlike') }}
                        @else
                            {{ trans('user.actions.like') }}
                        @endif
                    </button>
                @endif
            @endif
        </div>
        <div class="modal fade" id="review-modal" role="dialog">
            <div class="modal-dialog" style="width: 800px; margin: 30px auto;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">{{ trans('user.update_review') }}</h4>
                    </div>
                    {!! Form::open(['id' => 'rv-form-update']) !!}
                        <div class="modal-body" style="height: 400px;">
                            {!! Form::textarea('content', $review->content, ['id' => 'rv-content']) !!}
                        </div>
                        <div class="modal-footer">
                            {!! Form::submit(trans('user.actions.update'), ['class' => 'btn btn-info', 'data-dismiss' => 'modal', 'id' => 'submit-update-review']) !!}
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <hr>
    </div>
    <div class="col-md-10 col-md-offset-2">
        <h5 class="comment-title">
            <span class="glyphicon glyphicon-comment"></span>
            <span id="rv-num-comments">{{ trans('user.comments', ['num_comment' => $review->comments_count]) }}</span>
            <span class="fa fa-heart" area-hidden="true" style="margin: auto 10px auto 15px;"></span>
            <span id="rv-num-likes">{{ trans('user.likes', ['num_like' => $review->like_events_count]) }}</span>
        </h5>
        <div class="comment">
            @if ($comments)
                @foreach ($comments as $comment)
                    <div class="item-comment clear-fix" id="item-comment-{{ $comment->id }}">
                        <div class="user-info">
                            <img class="user-ava" src="{{ $comment->user->avatar_link }}"></img>
                            <div class="user-name">
                                <h4>{{ $comment->user->name }}</h4>
                                <p class="comment-time">
                                    {{ trans('user.created_at', ['time' => $comment->created_at]) }}
                                </p>
                            </div>
                            <div class="comment-body">
                                <span>{{ $comment->content }}</span>
                                @if (Auth::check())
                                    @if (Auth::user()->id === $comment->user->id)
                                        <div class="dropdown" data-comment-id="{{ $comment->id }}" data-review-id="{{ $review->id }}">
                                            <i class="fa sm fa-pencil dropdown-toggle" data-toggle="dropdown" aria-hidden="true">
                                            </i>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button>{{ trans('user.action.edit') }}</button>
                                                </li>
                                                <li class="divider"></li>
                                                <li>
                                                    <button id="btn-delete-comment" data-toggle="modal" data-target="#delete-cmt-modal">{{ trans('user.delete') }}</button>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    @if ( Auth::check() )
        <div class="col-md-8 col-md-offset-2">
            <div class="new-comment">
                {{ Form::open(['id' => 'form-comment']) }}
                    <div class="form-group">
                        {{ Form::textarea('content', null, [
                            'class' => 'form-control',
                            'id' => 'comment-content',
                            'rows' => '3',
                        ]) }}
                        <span class="glyphicon glyphicon-send" type="submit" id="icon-submit-comment" data-review-id="{{ $review->id }}" data-url-post-add-comment="{{ route('postAddComment') }}"></span>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    @else
        <a href="{{ route('getSignin') }}" class="btn btn-info col-md-offset-4">{{ trans('user.signin_to_make_comment') }}</a>
    @endif
</div>
<div class="modal fade" id="delete-cmt-modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong class="modal-title">{{ trans('user.delete') }}</strong>
            </div>
            <div class="modal-body">
                <p>{{ trans('user.comment.confirm_delete') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">{{ trans('user.cancel') }}</button>
                <button type="button" class="btn btn-info" id="btn-confirm-delete" data-dismiss="modal" data-url-delete-comment="{{ route('postDeleteComment') }}">{{ trans('user.yes') }} </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>

<script type="text/javascript">
    tinyMCE.init({
        mode : "specific_textareas",
        min_height: 300,
        selector : '#rv-content',
        selection_toolbar: 'bold italic | quicklink h2 h3',
        menubar: false,
        statusbar: false,
        plugins: 'link',
        toolbar: 'fontsizeselect bold italic | h2 | blockquote alignleft aligncenter alignright alignfull',
        fontsize_formats: "12pt 14pt 18pt"
    });
</script>

{{ Html::script('js/ajaxUserUpdateReview.js') }}
{{ Html::script('js/ajaxLikeReview.js') }}
{{ Html::script('js/ajaxUserComment.js') }}
{{ Html::script('js/ajaxDeleteComment.js') }}

@endsection

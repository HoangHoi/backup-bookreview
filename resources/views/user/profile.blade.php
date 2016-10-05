@extends('layouts.usermaster')

@section('content')

<div class="container box-profile">
    <div class="row">
        <div class="avatar">
            <a href="{{ route('users.show', $userInfo->id) }}">
                <img src="{{ $userInfo->avatar_link }}" alt="avatar" style="float: left;">
            </a>
        </div>
        <div class="col-md-6 col-lg-6 content-profile">
            <p class="name-profile">{{ $userInfo->name }}</p>
            @if (!is_null($action))
                @if ($action == trans('user.actions.edit'))
                    <a href="{{ route('getEditProfile') }}" class="btn btn-edit pull-right">{{ $action }}</a>
                @else
                    <button class="btn btn-edit btn-follow pull-right">{{ $action }}</button>
                @endif
            @endif
        </div>
        <div class="col-md-4 col-lg-4 box-info-like">
            <a href="#">
                <p class="col-md-8">{{ trans('user.profile.review') }}</p>
                <p class="col-md-4">0</p>
            </a>
            <a href="#">
                <p class="col-md-8">{{ trans('user.profile.following') }}</p>
                <p class="col-md-4">{{ $userInfo->followings_count }}</p>
            </a>
            <a href="#">
                <p class="col-md-8">{{ trans('user.profile.follower') }}</p>
                <p class="col-md-4">{{ $userInfo->followers_count }}</p>
            </a>
        </div>
    </div>
</div>
<div class="row col-lg-12 box-title box-title-children">
    <ul class="list-title">
        <li>
            <a href="#" class="selected">{{ trans('user.profile.post_review') }}</a>
        </li>
    </ul>
</div>
<div class="container">
    <div class="row box-review">
        <div class="col-lg-12">
            @if (count($reviews))
                @foreach($reviews as $review)
                    <div class="row" style="margin-bottom: 25px;">
                        <div class="pre-review-post col-md-4">
                            <div class="box-top">
                                <div class="pre-review-book-image">
                                    <a href="{{ route('book.show', $review->book->id) }}">
                                        <img src="{{ $review->book->book_image }}">
                                    </a>
                                </div>
                                <div class="pre-footer-review-post">
                                    <span>{{ trans('book.num_reviews', ['num' => $review->book->reviews->count()]) }}</span>
                                </div>
                            </div>
                            <div class="pre-review col-md-8">
                                <div class="pre-title-review">{{ $review->book->title }}</div>
                                <div class="pre-author-review">
                                    <a href="{{ route('users.show', $review->user->id) }}" class="author-link-profile">
                                        <img class="image-tiny image-circle" src="{{ $review->user->avatar_link }}">
                                        <span>{{ $review->user->name }}</span>
                                    </a>
                                    <div class="mini-date">
                                        <span>{{ trans('label.posted') }}</span>
                                        <span>{{ $review->created_at }}</span>
                                    </div>
                                </div>
                                <article class="pre-detail break-word">
                                    {!! str_limit($review->content,config('common.review_min_text_length')) !!}
                                </article>
                                <a href="{{ route('reviews.show', $review->id) }}" class="button-round button-continue">{{ trans('book.continue_reading') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
                <span>
                    {!! $reviews->links() !!}
                </span>
            @endif
        </div>
    </div>
</div>

@endsection

@section('script')

    <script>
        var urlFollow = '{{ route('postFollowUser') }}';
        var userId = '{{ $userInfo->id }}';
        var redirectPath = '{{ route('home') }}';
    </script>

    {{ Html::script('js/ajaxFollowUser.js') }}

@endsection

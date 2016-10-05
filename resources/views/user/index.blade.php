@extends('layouts.usermaster')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-9 col-lg-9 post-left">
            <div class="col-lg-12">
                @if (count($reviews))
                    @foreach ($reviews as $review)
                        <div class="row" style="margin-bottom: 25px;">
                            <div class="pre-review-post col-md-4">
                                <div class="box-top">
                                    <div class="pre-review-book-image">
                                        <a href="{{ route('book.show', $review->book->id) }}">
                                            <img src="{{ $review->book->book_image }}">
                                        </a>
                                    </div>
                                    <div class="pre-footer-review-post">
                                        <span>{{ trans('book.num_reviews', ['num' => $review->book->reviews()->count()]) }}</span>
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
                                        {!! str_limit($review->content, config('common.top_review_min_text_length')) !!}
                                    </article>
                                    <a href="{{ route('reviews.show', $review->id) }}" class="button-round button-continue">{{ trans('book.continue_reading') }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <span class="text-center">{{ $reviews->links() }}</span>
                @else
                    <h3>{{ trans('label.null_reviews') }}</h3>
                @endif
            </div>
        </div>
        <div class="col-md-3 col-lg-3">
            <label for="marquee-left">{{ trans('label.top_review') }}</label>
            <marquee id="marquee-left" behavior="SCROLL" direction="up">
                @if (isset($topReviews))
                    @foreach ($topReviews as $review)
                        <div class="row" style="margin: auto 5px;">
                            <div class="col-sm-3">
                                <img src="{{ $review->user->avatar_link }}" class="image-tiny image-circle">
                            </div>
                            <div class="col-sm-9">
                                <h4>{{ $review->user->name }}</h4>
                            </div>
                            <div class="col-lg-12">
                                <b>{{ $review->book->title }}</b>
                            </div>
                            <a href="{{ route('reviews.show', $review->id) }}">
                                <article class="pre-detail break-word" style="margin: 10px auto;">
                                    {!! str_limit($review->content, config('common.top_review_min_text_length')) !!}
                                </article>
                            </a>
                        </div>
                        <hr>
                    @endforeach
                @endif
            </marquee>
        </div>
        <span class="text-center">{{ $reviews->links() }}</span>
    </div>
</div>
@endsection

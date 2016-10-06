@extends('layouts.usermaster')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-9 col-lg-9 post-left">
            <div class="col-lg-12">
                @if (count($reviews))
                    @foreach ($reviews as $review)
                        @include('includes.listReview')
                    @endforeach
                    <span class="text-center">{{ $reviews->links() }}</span>
                @else
                    <h3>{{ trans('label.null_reviews') }}</h3>
                @endif
            </div>
        </div>
        <div class="col-md-3 col-lg-3">
            <label for="marquee-left">{{ trans('label.top_review') }}</label>
            <marquee id="marquee-left" behavior="SCROLL" direction="up" style="text-align: center;" scrollamount="3">
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
    </div>
</div>
@endsection

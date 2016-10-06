<div class="row" style="margin-bottom: 25px;">
    <div class="pre-review-post col-md-4">
        <div class="row">
            <div class="box-top">
                <div class="row">
                    <div class="col-md-10" style="height: 200px;">
                        <a href="{{ route('book.show', $review->book->id) }}">
                            <img src="{{ $review->book->book_image }}">
                        </a>
                    </div>
                    <div class="pre-footer-review-post"></div>
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
</div>

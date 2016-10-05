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
            <div>
                <p class="col-md-8">{{ trans('user.profile.review') }}</p>
                <mark class="col-md-4">{{ $userInfo->reviews_count }}</mark>
            </div>
            <div>
                <p class="col-md-8">{{ trans('user.profile.following') }}</p>
                <mark class="col-md-4">{{ $userInfo->followings_count }}</mark>
            </div>
            <div>
                <p class="col-md-8">{{ trans('user.profile.follower') }}</p>
                <mark class="col-md-4">{{ $userInfo->followers_count }}</mark>
            </div>
        </div>
    </div>
</div>
<div class="row col-lg-12 box-title box-title-children">
    <ul class="list-title">
        <li>
            <button class="btn btn-info btn-round btn-show-tab" data-tab="posted-review">
                {{ trans('user.profile.post_review') }}
            </button>
            <button class="btn btn-info btn-round btn-show-tab" data-tab="read-book">
                {{ trans('book.mark_as.read') }}
            </button>
            <button class="btn btn-info btn-round btn-show-tab" data-tab="reading-book">
                {{ trans('book.mark_as.reading') }}
            </button>
            <button class="btn btn-info btn-round btn-show-tab" data-tab="follower">
                {{ trans('user.profile.follower') }}
            </button>
            <button class="btn btn-info btn-round btn-show-tab" data-tab="following">
                {{ trans('user.profile.following') }}
            </button>
        </li>
    </ul>
</div>
<div class="container">
    <div class="row box-review list-tab" id="tab-posted-review" style="display: none;">
        <div class="col-lg-12">
            @if (count($reviews))
                @foreach($reviews as $review)
                    @include('includes.listReview')
                @endforeach
                <span>
                    {!! $reviews->links() !!}
                </span>
            @endif
        </div>
    </div>
    <div class="row box-review list-tab" id="tab-read-book" style="display: none;">
        <div class="col-lg-12">
            @if (count($readBooks))
                @foreach($readBooks as $book)
                    @include('includes.bookShow')
                @endforeach
            @else
                {{ trans('label.null_marked_book') }}
            @endif
        </div>
        <span>{!! $readBooks->links() !!}</span>
    </div>
    <div class="row box-review list-tab" id="tab-reading-book" style="display: none;">
        <div class="col-lg-12">
            @if (count($readingBooks))
                @foreach($readingBooks as $book)
                    @include('includes.bookShow')
                @endforeach
            @else
                {{ trans('label.null_marked_book') }}
            @endif
        </div>
        <span>{!! $readingBooks->links() !!}</span>
    </div>
    <div class="box-review list-tab" id="tab-follower">
        <div class="col-lg-12">
            @foreach($userInfo->followers as $userFollow)
                @include('includes.listUser')
            @endforeach
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

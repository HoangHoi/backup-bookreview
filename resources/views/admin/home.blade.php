@extends('layouts.adminMaster')

@section('page_title', trans('admin.title_home'))

@section('main_title', trans('admin.title_home'))

@section('style')
{!! Html::style('css/fontawesome-stars.css') !!}
{!! Html::style('css/adminHome.css') !!}
@endsection

@section('content')
<div class="col-md-12">
    <h3 class="body-title" style="">
        {!! trans('book.best_book') !!}
    </h3>
    <div class="row">
        @foreach($books as $book)
            <div class="col-md-3">
                <div class="panel panel-default book-content">
                    <div class="panel-body">
                        <div class="book-image">
                            <img src="{!! $book->book_image !!}"/>
                        </div>
                        <div class="book-detail">
                            <div class="link-detail">
                                <a href="{{ route('book.show', ['id' => $book->id]) }}">{!! $book->title !!}</a>
                            </div>
                            <div style="color: #008340; margin-bottom: 0.1em">{!! $book->author !!}</div>
                            <div style="margin-bottom: 0.3em"><em>{!! $book->published_at !!}</em></div>
                            <div>
                                <select class="book-start" style="display: none;">
                                    @foreach (range(1, config('common.rate_point_max')) as $i)
                                        <option
                                            value="{!! $i !!}"
                                            {!! ceil($book->avg_rate_point) == $i ? 'selected="selected"' : '' !!}
                                            ></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<div class="col-md-12">
    <h3 class="body-title" style="">
        {!! trans('user.top_reviewer') !!}
    </h3>
    <div class="row">
        @foreach($users as $user)
            <div class="col-md-3">
                <div class="panel panel-default user-content">
                    <div class="panel-body">
                        <div class="user-image">
                            <img src="{!! $user->avatar_link !!}" class="img-circle">
                        </div>
                        <div class="user-detail">
                            <div class="link-detail">
                                <a href="{!! route('users.show', $user->id) !!}">{!! $user->name !!}</a>
                            </div>
                            <div style="color: #008340; margin-bottom: 0.1em">{!! $user->gender_name !!}</div>
                            <div style="margin-bottom: 0.3em">
                                <em>{!! $user->reviews_count . ' ' . trans('user.reviews') !!}</em>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('script')
{{ Html::script('js/jquery.barrating.min.js') }}
<script>
    $(document).ready(function () {
        $('.book-start').barrating('show', {
            theme: 'fontawesome-stars',
            hoverState: false,
            readonly: true,
        });
    });
</script>
@endsection

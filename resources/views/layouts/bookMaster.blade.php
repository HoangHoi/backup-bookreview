@extends('layouts.usermaster')

@section('header')
@include('includes.header')
@endsection

@section('head')
{{ Html::style('css/book.css') }}
@endsection

@section('content')
<div class="container-fluid" style="margin-top: 40px;">
    <div class="col-sm-3">
        <ul class="nav nav-pills nav-stacked" id="book-menu">
            {!! $bookMenu !!}
        </ul>
    </div>
    <div class="col-sm-8">
        <h3 class="body-title">
            @yield('body_title')
        </h3>
        <div class="col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{!! route('book.index') !!}">{!! trans('book.all_book') !!}</a></li>
                {!! $breadcrumbs !!}
            </ul>
        </div>
        @yield('body')
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
        $('.book-start').barrating({
            theme: 'fontawesome-stars',
            hoverState: false,
            readonly: true,
        });
    });
</script>
@endsection

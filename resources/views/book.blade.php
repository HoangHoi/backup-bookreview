@extends('layouts.bookMaster')

@section('body_title', $bodyTitle)

@section('body')
@if (count($books))
    @foreach($books as $book)
        @include('includes.bookShow')
    @endforeach
@else
    <h2>{{ trans('book.category_empty') }}</h2>
@endif
<div class="col-lg-12">
    {!! $books->render() !!}
</div>
@endsection

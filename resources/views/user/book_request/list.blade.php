@extends('layouts.bookMaster')

@section('body')
    @if (count($bookRequests))
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    {{ trans('label.book_request_heading') }}
                </h3>
            </div>
            <table class="table">
                <tr>
                    <th>{{ trans('label.book_request') }}</th>
                    <th>{{ trans('label.status') }}</th>
                </tr>
                @foreach ($bookRequests as $book)
                    <tr>
                        <td>
                            <a href="{{ route('book.show', $book->id) }}">
                                {{ $book->title }}
                            </a>
                        </td>
                        @if ($book->pivot->accepted)
                            <td>
                                {{ trans('user.accepted') }}
                            </td>
                            <td>
                                <a href="#" class="cancel-request" data-book-Id="{{ $book->id }}">
                                    {{ trans('user.actions.cancel') }}
                                </a>
                            </td>
                        @else
                             <td>
                                 {{ trans('user.in_progress') }}
                             </td>                   
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>
        <span>
            {{ $bookRequests->links() }}
        </span>
    @else
        {{ trans('label.null_request') }}
    @endif
@endsection

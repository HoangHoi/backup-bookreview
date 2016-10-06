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
                    <tr id ="item-request-{{ $book->id }}">
                        <td>
                            <a href="{{ route('book.show', $book->id) }}">
                                {{ $book->title }}
                            </a>
                        </td>
                        @if ($book->pivot->accepted)
                            <td>
                                {{ trans('user.accepted') }}
                            </td>
                        @else
                            <td>
                                 {{ trans('user.in_progress') }}
                            </td>
                            <td>
                                <a class="cancel-request" data-book-id="{{ $book->id }}" data-url-cancel-request="{{ route('postCancelRequest') }}" data-message-confirm="{{ trans('user.msg_confirm_delete') }}">
                                    {{ trans('user.actions.cancel') }}
                                </a>
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

@section('script')
{{ Html::script('js/ajaxCancelRequest.js') }}
@endsection

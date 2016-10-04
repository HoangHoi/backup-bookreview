<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Book;
use File;
use App\Http\Requests\BookRequest;
use App\Http\Requests\BookUpdateRequest;

class BookController extends Controller
{
    public function create()
    {
        $categories = Category::lists('name', 'id');

        return view('admin.book.create', ['categories' => $categories]);
    }

    public function store(BookRequest $request)
    {
        $bookRequest = $request->only([
            'title',
            'author',
            'num_page',
            'description',
            'category_id',
            'published_at',
            'book_image',
            'avg_rate_point',
            'num_favorite',
        ]);
        if (Book::create($bookRequest)) {
            return redirect()->back()->with([
                config('common.flash_message') => trans('message.create_success', ['name' => trans('admin.book')]),
                config('common.flash_level_key') => config('common.flash_level.success'),
            ]);
        }

        return redirect()->back()->withErrors([
            trans('message.create_failed', ['name' => trans('admin.book')]),
        ]);
    }

    public function index()
    {
        return view('admin.book.list');
    }

    public function ajaxList()
    {
        $books = Book::with('category')->get();

        return [
            'data' => $books,
        ];
    }

    public function ajaxGetOne(Request $request)
    {
        try {
            $book = Book::with('category')->findOrFail($request->id);

            return $book;
        } catch (Exception $ex) {
            return abort(404);
        }
    }

    public function ajaxDelete(Request $request)
    {
        $resultCount = 0;
        $requestCount = 0;
        $response = [];
        $rid = $request->id;
        if (is_array($rid)) {
            $requestCount = count($rid);
            foreach ($rid as $id) {
                if ($this->deleteById($id)) {
                    $resultCount++;
                }
            }
        } else {
            $result = $this->deleteById($rid);
            $requestCount++;
            if ($result) {
                $resultCount++;
            }
        }

        if ($resultCount == $requestCount) {
            $response[config('common.flash_level_key')] = config('common.flash_level.success');
        } elseif ($requestCount > 0 && $resultCount == 0) {
            $response[config('common.flash_level_key')] = config('common.flash_level.danger');
        } else {
            $response[config('common.flash_level_key')] = config('common.flash_level.warning');
        }

        $response[config('common.flash_message')] = trans('admin.response.delete', [
            'num' => $resultCount,
            'sum' => $requestCount,
            'result' => trans('admin.result.success'),
        ]);

        return $response;
    }

    public function deleteById($id)
    {
        try {
            Book::find($id)->delete();

            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }

    public function ajaxUpdate(BookUpdateRequest $request)
    {
        $requestArray = [
            'title',
            'author',
            'num_page',
            'description',
            'category_id',
            'published_at',
        ];
        if ($request->hasFile('book_image') || $request->reset_image == 'true') {
            $requestArray[] = 'book_image';
        }

        try {
            $book = Book::find($request->id);
            $bookRequest = $request->only($requestArray);
            $book->update($bookRequest);

            return [
                config('common.flash_level_key') => config('common.flash_level.success'),
                config('common.flash_message') => trans('admin.response.update', [
                    'result' => trans('admin.result.success'),
                ]),
            ];
        } catch (\Exception $e) {
            return [
                config('common.flash_level_key') => config('common.flash_level.danger'),
                config('common.flash_message') => trans('admin.response.update', [
                    'result' => trans('admin.result.fail'),
                ]),
            ];
        }
    }
}

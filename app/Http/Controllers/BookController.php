<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Book;
use App\Models\Review;
use App\Models\Favorite;
use App\Models\Mark;
use App\Models\Rate;
use App\Http\Requests\MarkRequest;
use App\Http\Requests\RateRequest;
use App\Models\Category;
use Auth;

class BookController extends Controller
{
    private $userId;
    private $categories;

    public function __construct()
    {
        if (Auth::check()) {
            $this->userId = Auth::user()->id;
        } else {
            $this->userId = null;
        }

        $this->categories = Category::withCount('books')->get()->toArray();
    }

    public function bookMenu($categoryCurrentId)
    {
        return $this->drawBookMenu($this->categories, $categoryCurrentId);
    }

    protected function drawBreadcrumbs($categoryCurrentId)
    {
        $category = Category::find($categoryCurrentId);
        if ($category) {
            return $this->drawBreadcrumbs($category->category_parent_id)
                . '<li><a href="'
                . route('book.showByCategory', $category->id)
                . '">'
                . $category->name
                . '</a></li>';
        }

        return null;
    }

    protected function drawBookMenu($categories, $categoryCurrentId, $category_parent_id = null)
    {
        $result = '';
        foreach ($categories as $key => $category) {
            if ($category['category_parent_id'] == $category_parent_id) {
                unset($categories[$key]);
                $children = $this->drawBookMenu($categories, $categoryCurrentId, $category['id']);
                $result .= view('includes.bookMenuPiece', [
                    'category' => $category,
                    'children' => $children,
                    'bookCountTree' => $this->bookCountTree($key),
                    'categoryCurrentId' => $categoryCurrentId,
                ])->render();
            }
        }

        return $result;
    }

    public function index()
    {
        $books = Book::with([
                'marks' => function($query) {
                    $query->where('user_id', $this->userId);
                },
                'favorites' => function($query) {
                    $query->where('user_id', $this->userId);
                },
            ])->orderBy('published_at', 'desc')->paginate(config('common.num_entry_per_page'));

        return view('book', [
            'bodyTitle' => trans('book.all_book'),
            'books' => $books,
            'bookMenu' => $this->bookMenu(null),
            'breadcrumbs' => $this->drawBreadcrumbs(null),
        ]);
    }

    protected function categoryAllTree($categoryId)
    {
        if (Category::find($categoryId)) {
            $response = [$categoryId];
            foreach ($this->categories as $category) {
                if ($category['category_parent_id'] == $categoryId) {
                    $response = array_merge($this->categoryAllTree($category['id']), $response);
                }
            }

            return $response;
        }

        return [];
    }

    public function bookCountTree($key)
    {
        if (isset($this->categories[$key])) {
            $response = $this->categories[$key]['books_count'];
            foreach ($this->categories as $k => $category) {
                if ($category['category_parent_id'] == $this->categories[$key]['id']) {
                    $response += $this->bookCountTree($k);
                }
            }

            return $response;
        }

        return 0;
    }

    public function showByCategory(Category $category)
    {
        $books = Book::with([
            'marks' => function($query) {
                $query->where('user_id', $this->userId);
            },
            'favorites' => function($query) {
                $query->where('user_id', $this->userId);
            },
        ])->whereIn('category_id', $this->categoryAllTree($category->id))
            ->orderBy('published_at', 'desc')
            ->paginate(config('common.num_entry_per_page'));

        return view('book', [
            'bodyTitle' => $category->name,
            'books' => $books,
            'bookMenu' => $this->bookMenu($category->id),
            'breadcrumbs' => $this->drawBreadcrumbs($category->id),
        ]);
    }

    public function show($bookId)
    {
        $book = Book::with([
            'marks' => function($query) {
                $query->where('user_id', $this->userId);
            },
            'favorites' => function($query) {
                $query->where('user_id', $this->userId);
            },
            'rates' => function($query) {
                $query->where('user_id', $this->userId);
            },
        ])->findOrFail($bookId);
        $reviews = Review::where('book_id', $bookId)->orderBy('created_at', 'desc')->paginate(5);

        return view('book.bookdetail', ['book' => $book, 'reviews' => $reviews]);
    }

    public function favorite(MarkRequest $request)
    {
        $favorite = Favorite::where('user_id', $this->userId)->where('book_id', $request->id)->first();
        if ($favorite) {
            if ($request->action == config('book.actions.deactive') && $favorite->delete()) {
                return [
                    config('book.action') => config('book.actions.deactive'),
                    config('book.result') => config('book.results.success'),
                ];
            }
        } else {
            if ($request->action == config('book.actions.active') && Favorite::create([
                'user_id' => $this->userId,
                'book_id' => $request->id
            ])) {
                return [
                    config('book.action') => config('book.actions.active'),
                    config('book.result') => config('book.results.success'),
                ];
            }
        }

        return [
            config('book.action') => $request->action,
            config('book.result') => config('book.results.fail')
        ];
    }

    public function mark(MarkRequest $request)
    {
        $mark = Mark::where('user_id', $this->userId)->where('book_id', $request->id)->first();
        if ($mark) {
            switch ($request->action) {
                case config('book.actions.marks.read'):
                    if ($mark->action != config('book.db.read') && $mark->update(['action' => config('book.db.read')])) {
                        return [
                            config('book.action') => config('book.actions.marks.read'),
                            config('book.result') => config('book.results.success'),
                        ];
                    }
                    break;
                case config('book.actions.marks.reading'):
                    if ($mark->action != config('book.db.reading')
                        && $mark->update(['action' => config('book.db.reading')])
                    ) {
                        return [
                            config('book.action') => config('book.actions.marks.reading'),
                            config('book.result') => config('book.results.success'),
                        ];
                    }
                    break;
                case config('book.actions.marks.none'):
                    if ($mark->delete()) {
                        return [
                            config('book.action') => config('book.actions.marks.none'),
                            config('book.result') => config('book.results.success'),
                        ];
                    }
                    break;
            }
        } else {
            switch ($request->action) {
                case config('book.actions.marks.read'):
                    if (Mark::create([
                        'user_id' => $this->userId,
                        'book_id' => $request->id,
                        'action' => config('common.read')
                    ])) {
                        return [
                            config('book.action') => config('book.actions.marks.read'),
                            config('book.result') => config('book.results.success'),
                        ];
                    }
                    break;
                case config('book.actions.marks.reading'):
                    if (Mark::create([
                        'user_id' => $this->userId,
                        'book_id' => $request->id,
                        'action' => config('common.reading')
                    ])) {
                        return [
                            config('book.action') => config('book.actions.marks.reading'),
                            config('book.result') => config('book.results.success'),
                        ];
                    }
                    break;
            }
        }

        return [
            config('book.action') => $request->action,
            config('book.result') => config('book.results.fail')
        ];
    }

    public function bookAutocomplete(Request $request)
    {
        $books = Book::where('title', 'LIKE', "%{$request->input('query')}%")
            ->take(config('book.limit_search'))
            ->get(['title']);

        return response()->json($books);   
    }

    public function searchBook(Request $request)
    {
        $books = Book::with([
                'marks' => function($query) {
                    $query->where('user_id', $this->userId);
                },
                'favorites' => function($query) {
                    $query->where('user_id', $this->userId);
                },
            ])->where('title', 'LIKE', "%{$request->input('query')}%")
            ->orderBy('published_at', 'desc')
            ->paginate(config('common.num_entry_per_page'));

        return view('book', [
            'bodyTitle' => trans('book.all_book'),
            'books' => $books,
            'bookMenu' => $this->bookMenu(null),
            'breadcrumbs' => $this->drawBreadcrumbs(null),
        ]);
    }

    public function rate(RateRequest $request)
    {
        $rate = Rate::where('user_id', $this->userId)->where('book_id', $request->id)->first();
        $book = Book::find($request->id);
        if ($rate) {
            if ($rate->update(['point' => $request->action]) && $pointAvg = $this->avgRate($book)) {
                return [
                    config('book.action') => [
                        config('book.actions.rates.your_rate') => $request->action,
                        config('book.actions.rates.book_rate') => $pointAvg,
                    ],
                    config('book.result') => config('book.results.success'),
                ];
            }
        } else {
            if (Rate::create([
                'user_id' => $this->userId,
                'book_id' => $request->id,
                'point' => $request->action,
            ]) && $pointAvg = $this->avgRate($book)) {
                return [
                    config('book.action') => [
                        config('book.actions.rates.your_rate') => $request->action,
                        config('book.actions.rates.book_rate') => $pointAvg,
                    ],
                    config('book.result') => config('book.results.success'),
                ];
            }
        }

        return [
            config('book.action') => [
                config('book.actions.rates.your_rate') => $request->action,
                config('book.actions.rates.book_rate') => $book->avg_rate_point,
            ],
            config('book.result') => config('book.results.fail'),
        ];
    }

    protected function avgRate (Book $book) {
        if ($book->update(['avg_rate_point' => ceil($book->rates()->avg('point'))])) {
            return $book->avg_rate_point;
        }

        return false;
    }

    public function getBookRequest()
    {
        $bookRequests = Auth::user()->bookRequests()->paginate(config('common.limit_show_request'));

        return view('user.book_request.list', [
            'bookRequests' => $bookRequests,
            'bookMenu' => $this->bookMenu(null),
            'breadcrumbs' => $this->drawBreadcrumbs(null),
        ]);
    }
}

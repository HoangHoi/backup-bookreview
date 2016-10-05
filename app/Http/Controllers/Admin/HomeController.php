<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;

class HomeController extends Controller
{

    public function index()
    {
        $books = Book::orderBy('avg_rate_point', 'desc')
            ->limit(config('common.limit_top'))
            ->get();
        $users = User::withCount('reviews')
            ->orderBy('reviews_count', 'desc')
            ->limit(config('common.limit_top'))
            ->get();

        return view('admin.home', ['books' => $books, 'users' => $users]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Review;
use Auth;

class HomeController extends Controller
{
    protected $user;

    public function __construct()
    {
        if (Auth::check()) {
            $this->user = Auth::user();
        }
    }

    public function index()
    {
        $topReviews = Review::getTopReviewByLike();
        if (Auth::check()) {
            $reviews = Review::where(function($query) {
                return $query->where('user_id', $this->user->id)
                    ->orWhereIn(
                        'user_id',
                        $this->user->followers()->lists('follower_id')
                    );
            })
            ->orderBy('created_at', 'desc')
            ->paginate(config('common.num_entry_per_page'));

            if (!isset($reviews)) {
                $reviews = Review::orderBy('created_at', 'desc')
                    ->limit(config('common.limit_review'))
                    ->paginate(config('common.num_entry_per_page'));
            }

            return view('user.index', ['reviews' => $reviews, 'topReviews' => $topReviews]);
        }

        $reviews = Review::orderBy('created_at', 'desc')->paginate(config('common.num_entry_per_page'));

        return view('user.index', ['reviews' => $reviews, 'topReviews' => $topReviews]);
    }
}

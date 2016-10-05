<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\UserFollow;
use App\Models\Review;
use App\Models\LikeEvent;
use App\Providers\UserService;
use Auth;

class UserController extends Controller
{
    public function show($id)
    {
        if (Auth::user()) {
            if (Auth::user()->id != $id) {
                $action = UserService::checkFollowed($id, Auth::user()->id) ? trans('user.actions.unfollow') : trans('user.actions.follow');
            } else {
                $action = trans('user.actions.edit');
            }
        } else {
            $action = null;
        }

        $userInfo = User::withCount(['followers', 'followings', 'reviews'])->find($id);
        if (is_null($userInfo)) {
            return redirect()->route('home');
        }

        $this->id = $id;
        $readBooks = $userInfo->readBooks()
            ->with([
                'marks' => function($query) {
                    $query->where('user_id', $this->id);
                },
                'favorites' => function($query) {
                    $query->where('user_id', $this->id);
                },
            ])
            ->orderBy('published_at', 'desc')
            ->paginate(config('common.num_entry_per_page'));
        $readingBooks = $userInfo->readingBooks()
            ->with([
                'marks' => function($query) {
                    $query->where('user_id', $this->id);
                },
                'favorites' => function($query) {
                    $query->where('user_id', $this->id);
                },
            ])
            ->orderBy('published_at', 'desc')
            ->paginate(config('common.num_entry_per_page'));
        $reviews = $userInfo->reviews()->paginate(config('common.num_entry_per_page'));

        return view('user.profile')->with(['userInfo' => $userInfo, 'reviews' => $reviews, 'readBooks' => $readBooks, 'readingBooks' => $readingBooks, 'action' => $action]);
    }

    public function getEditProfile()
    {
        return view('user.profiledetail')->with('user', Auth::user());
    }

    public function postUpdateProfile(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|max:40',
                'gender' => 'required',
            ]);
            $user = Auth::user();
            $params = $request->only(['name', 'gender', 'avatar_link']);
            $user->update($params);

            return redirect()->route('getEditProfile')->with([
                config('common.flash_message') => trans('user.msg_success_update_profile'),
                config('common.flash_level_key') => config('common.flash_level.success')
            ]);
        } catch (\Exception $e) {
            return redirect()->route('getEditProfile')->with([
                config('common.flash_message') => trans('user.msg_unsuccess_update_profile'),
                config('common.flash_level_key') => config('common.flash_level.danger')
            ]);
        }
    }

    public function postFollowUser(Request $request)
    {
        if (!User::find($request->userId)) {
            return response()->json(['err' => trans('user.profile.not_exist'), 404]);
        }

        $currentUserId = Auth::user()->id;
        if (UserService::checkFollowed($request->userId, $currentUserId)) {
            $action = UserFollow::deleteFollow($request->userId, $currentUserId) ? trans('user.actions.follow') : trans('user.actions.unfollow');
        } else {
            $params['follower_id'] = $request->userId;
            $params['following_id'] = $currentUserId;
            UserFollow::create($params);
            $action = trans('user.actions.unfollow');
        }

        $userFollow = User::withCount('followers', 'followings')->find($request->userId);

        return response()->json([
            'changeAction' => $action,
            'num_followings' => $userFollow->followings_count,
            'num_followers' => $userFollow->followers_count,
            200
        ]);
    }

    public function postLikeReview(Request $request)
    {
        if (!Review::find($request->reviewId)) {
            return response()->json(['err' => trans('user.review.not_exist'), 404]);
        }

        $currentUserId = Auth::user()->id;
        $reviewId = $request->reviewId;
        if (UserService::checkLiked($reviewId, $currentUserId)) {
            $likeAction = LikeEvent::deleteLike($request->reviewId, $currentUserId) ? trans('user.actions.like') : trans('user.actions.unlike');
        } else {
            $params['user_id'] = $currentUserId;
            $params['review_id'] = $reviewId;
            LikeEvent::create($params);
            $likeAction = trans('user.actions.unlike');
        }

        $review = Review::withCount('likeEvents')->find($reviewId);
        $htmlVal = '<span id="rv-num-likes">'
            . trans('user.likes', ['num_like' => $review->like_events_count])
            . '</span>';

        return response()->json([
            'likeAction' => $likeAction,
            'htmlVal' => $htmlVal,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\UserFollow;
use App\Providers\UserService;
use Auth;

class UserController extends Controller
{
    public function show($id)
    {
        if (Auth::user()) {
            if (Auth::user()->id != $id) {
                $action = UserService::checkFollowed($id, Auth::user()->id) ? trans('user.profile.unfollow') : trans('user.profile.follow');
            } else {
                $action = trans('user.profile.edit');
            }
        } else {
            $action = null;
        }

        $userInfo = User::withCount(['followers', 'followings'])->find($id);
        if (is_null($userInfo)) {
            return redirect()->route('home');
        }

        return view('user.profile')->with(['userInfo' => $userInfo, 'action' => $action]);
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

            return redirect()->route('getEditProfile');
        } catch (\Exception $e) {
            return redirect()->route('getEditProfile')->with([
                config('common.flash_message') => trans('user.msg_unsuccess_update_profile'),
                config('common.flash_level_key') => config('common.flash_level.warning')
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
            $action = UserFollow::deleteFollow($request->userId, $currentUserId) ? trans('user.profile.follow') : trans('user.profile.unfollow');
        } else {
            $params['follower_id'] = $request->userId;
            $params['following_id'] = $currentUserId;
            UserFollow::create($params);
            $action = trans('user.profile.unfollow');
        }
        $userFollow = User::withCount('followers', 'followings')->find($request->userId);
        return response()->json([
            'changeAction' => $action,
            'num_followings' => $userFollow->followings_count,
            'num_followers' => $userFollow->followers_count,
            200
        ]);
    }
}
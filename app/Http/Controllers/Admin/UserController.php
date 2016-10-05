<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserCreateRequest;

class UserController extends Controller
{

    public function index()
    {
        return view('admin.user.list');
    }

    public function ajaxList()
    {
        $users = User::withCount([
            'reviews',
            'readBooks',
            'readingBooks',
            'followings',
            'followers',
        ])->where('is_admin', false)->get();

        return [
            'data' => $users,
        ];
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
            User::where('is_admin', false)->find($id)->delete();

            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }

    public function ajaxUpdate(UserUpdateRequest $request)
    {
        try {
            $user = User::find($request->id);
            $userRequest = $request->only(['name', 'email', 'gender']);
            $user->update($userRequest);

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

    public function ajaxCreate(UserCreateRequest $request)
    {
        try {
            $request['password'] = $request->email;
            $userRequest = $request->only([
                'name',
                'email',
                'gender',
                'password',
                'avatar_link',
                'is_admin',
            ]);
            User::create($userRequest);

            return [
                config('common.flash_level_key') => config('common.flash_level.success'),
                config('common.flash_message') => trans('admin.response.create', [
                    'name' => trans('admin.user'),
                    'result' => trans('admin.result.success'),
                ]),
            ];
        } catch (\Exception $e) {
            return [
                config('common.flash_level_key') => config('common.flash_level.danger'),
                config('common.flash_message') => trans('admin.response.create', [
                    'name' => trans('admin.user'),
                    'result' => trans('admin.result.fail'),
                ]),
            ];
        }
    }

    public function ajaxResetPassword(Request $request)
    {
        $resultCount = 0;
        $response = [];
        $rid = $request->id;
        $requestCount = count($rid);
        foreach ($rid as $id) {
            if ($this->resetById($id)) {
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

        $response[config('common.flash_message')] = trans('admin.response.reset_password', [
            'num' => $resultCount,
            'sum' => $requestCount,
            'result' => trans('admin.result.success'),
        ]);

        return $response;
    }

    public function resetById($id)
    {
        try {
            $user = User::where('is_admin', false)->findOrFail($id);
            $user->password = $user->email;
            $user->save();

            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }
}

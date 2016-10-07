<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Response;

class CategoryController extends Controller
{

    public function create()
    {
        $categories = Category::lists('name', 'id');

        return view('admin.category.create', ['categories' => $categories]);
    }

    public function store(CategoryRequest $request)
    {
        $categoryRequest = $request->only(['name', 'category_parent_id']);
        $category = Category::create($categoryRequest);
        if ($category) {
            return redirect()->back()->with([
                config('common.flash_message') => trans('message.create_success', ['name' => trans('admin.category')]),
                config('common.flash_level_key') => config('common.flash_level.success'),
            ]);
        }

        return redirect()->back()->withErrors([
            trans('message.create_failed', ['name' => trans('admin.category')]),
        ]);
    }

    public function index()
    {
        $categories = Category::lists('name', 'id');

        return view('admin.category.list', ['categories' => $categories]);
    }

    public function ajaxList()
    {
        $categories = Category::withCount('books')->get();

        return [
            'data' => $categories,
        ];
    }

    public function ajaxListOnly()
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            $update = true;
            foreach ($categories as $categoryParent) {
                if ($category->category_parent_id == $categoryParent->id) {
                    $update = false;
                    continue;
                }
            }
            if ($update) {
                $category->update(['category_parent_id' => null]);
            }
        }

        return $categories;
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

        return Response::json($response);
    }

    public function deleteById($id)
    {
        try {
            Category::find($id)->delete();
            Category::where('category_parent_id', $id)->update(['category_parent_id' => null]);

            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }

    public function ajaxUpdate(CategoryUpdateRequest $request)
    {
        try {
            $category = Category::find($request->id);
            $categoryRequest = $request->only(['name', 'category_parent_id']);
            $category->update($categoryRequest);

            return Response::json([
                config('common.flash_level_key') => config('common.flash_level.success'),
                config('common.flash_message') => trans('admin.response.update', [
                    'result' => trans('admin.result.success'),
                ]),
            ]);
        } catch (\Exception $e) {
            return Response::json([
                config('common.flash_level_key') => config('common.flash_level.danger'),
                config('common.flash_message') => trans('admin.response.update', [
                    'result' => trans('admin.result.fail'),
                ]),
            ]);
        }
    }
}

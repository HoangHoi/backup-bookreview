<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Comment;
use App\Models\Review;
use Auth;

class CommentController extends Controller
{
    public function postAddComment(Request $request)
    {
        $this->validate($request, [
            'content' => 'required',
            'reviewId' => 'required',
        ]);
        if (!Review::find($request->reviewId)) {
            return response()->json(['err' => trans('user.review.not_exist'), 404]);
        }

        $params['user_id'] = Auth::user()->id;
        $params['review_id'] = $request->reviewId;
        $params['content'] = $request->content;
        $comment = Comment::create($params);
        if (!$comment) {
            return response()->json(['err' => trans('user.query_fail'), 500]);
        }

        $currentReview = Review::withCount('comments')->findOrFail($request->reviewId);
        $htmlComments = '<span id="rv-num-comments">'
            . trans('user.comments', ['num_comment' => $currentReview->comments_count])
            . '</span>';

        return response()->json([
            'htmlComments' => $htmlComments,
            'htmlValue' => view('includes.itemComment', ['comment' => $comment])->render(),
            200
        ]);
    }

    public function postDeleteComment(Request $request)
    {
        $comment = Comment::findOrFail($request->commentId);
        if (Auth::user()->id == $comment->user->id) {
            $result = $comment->delete();
            if ($result) {
                $currentReview = Review::withCount('comments')->findOrFail($request->reviewId);
                $htmlComments = '<span id="rv-num-comments">'
                    . trans('user.comments', ['num_comment' => $currentReview->comments_count])
                    . '</span>';

                return response()->json([
                    'status' =>  trans('user.yes'),
                    'num_comment' => $currentReview->comments_count,
                    'htmlComments' => $htmlComments,
                    200
                ]);
            }
        }

        return response()->json([
            'err' => trans('user.comment.cannot_delete'),
            500
        ]);
    }

    public function postLoadComment(Request $request)
    {
        try {
            $review = Review::withCount(['comments', 'likeEvents'])->find($request->reviewId);
            $comments = $review->comments()->orderBy('created_at', 'ASC')->get();

            return response()->json([
                'num_comment' => $review->comments_count,
                'num_like' => $review->likeEvents_count,
                'htmlValue' => view('includes.loadComment', ['comments' => $comments])->render(),
                200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'err' => trans('user.review.load_fail'),
                500
            ]);
        }
    }
}

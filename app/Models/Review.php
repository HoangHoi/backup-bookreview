<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Review extends Model
{
    protected $table = 'reviews';
    protected $fillable = [
        'user_id', 'book_id', 'content',
    ];

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'review_id');
    }

    public function likeEvents()
    {
        return $this->hasMany('App\Models\LikeEvent', 'review_id');
    }

    public function usersLikes()
    {
        return $this->belongsToMany('App\Models\User', 'like_events');
    }

    public function book()
    {
        return $this->belongsTo('App\Models\Book', 'book_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function getContentAttribute($value)
    {
        return nl2br($value);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format(config('common.publish_date_format'));
    }

    public function delete()
    {
        try {
            parent::delete();
            $this->comments()->delete();

            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }

    public static function getTopReviewByLike()
    {
        $reviews = Review::withCount('likeEvents')
            ->orderBy('like_events_count', 'desc')
            ->limit(config('common.limit_top_review'))
            ->get();

        return $reviews;
    }
}

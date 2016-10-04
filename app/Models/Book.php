<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use File;
use Carbon\Carbon;
use Str;
use Image;

class Book extends Model
{
    protected $table = 'books';
    protected $fillable = [
        'id',
        'title',
        'author',
        'num_page',
        'book_image',
        'description',
        'category_id',
        'num_favorite',
        'avg_rate_point',
        'published_at',
    ];
    protected $dates = [
        'published_at',
    ];

    public function setBookImageAttribute($imageUpload)
    {
        $imgFolder = public_path(config('fileupload.book_image_dir'));
        if (!File::isDirectory($imgFolder)) {
            File::makeDirectory($imgFolder, 755, true);
        }
        if ($imageUpload != NULL) {
            $imgFilename = Carbon::now()
                ->format(config('common.date_format_book_image_file'))
                . '_'
                . str_slug(Str::words($this->attributes['title'], 5, ''), '_')
                . '.'
                . $imageUpload->getClientOriginalExtension();
            if (Image::make($imageUpload->getRealPath())
                ->resize(config('common.book_image_size.width'), config('common.book_image_size.height'))
                ->save($imgFolder . $imgFilename)) {
                $this->deleteFileBookImage();

                return $this->attributes['book_image'] = $imgFilename;
            }
        }

        return $this->attributes['book_image'] = config('common.book_image_default');
    }

    public function setAvgRatePointAttribute($avgRatePoint)
    {
        return $this->attributes['avg_rate_point'] = $avgRatePoint ?: 0;
    }

    public function setNumFavoriteAttribute($numFavorite)
    {
        return $this->attributes['num_favorite'] = $numFavorite ?: 0;
    }

    public function getBookImageAttribute($value)
    {
        if (File::exists(public_path(config('fileupload.book_image_dir') . $value))) {
            return asset(config('fileupload.book_image_dir') . $value);
        }

        return asset(config('fileupload.book_image_dir') . config('common.book_image_default'));
    }

    public function getPublishedAtAttribute($value)
    {
        return Carbon::parse($value)->format(config('common.publish_date_format'));
    }

    public function setPublishedAtAttribute($value)
    {
        return $this->attributes['published_at'] = $value ?
            Carbon::createFromFormat(config('common.publish_date_format'), $value)
            : Carbon::now()->format(config('common.publish_date_format'));
    }

    public function delete()
    {
        try {
            parent::delete();
            $this->reviews()->delete();
            $this->deleteFileBookImage();
            $this->bookRequests()->delete();

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function deleteFileBookImage()
    {
        if (isset($this->attributes['book_image']) && $this->attributes['book_image'] != config('common.book_image_default')) {
            $pathImageFile = config('fileupload.book_image_dir') . $this->attributes['book_image'];
            if (File::exists($pathImageFile)) {
                File::delete($pathImageFile);
            }
        }
    }

    public function getDescriptionAttribute($value)
    {
        return nl2br($value);
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review', 'book_id');
    }

    public function rates()
    {
        return $this->hasMany('App\Models\Rate', 'book_id');
    }

    public function bookRequests()
    {
        return $this->hasMany('App\Models\BookRequest', 'book_id');
    }

    public function marks()
    {
        return $this->hasMany('App\Models\Mark', 'book_id');
    }

    public function favorites()
    {
        return $this->hasMany('App\Models\Favorite', 'book_id');
    }
}

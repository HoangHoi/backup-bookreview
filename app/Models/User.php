<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Hash;
use Image;
use File;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'gender', 'password', 'avatar_link', 'is_admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function followings()
    {
        return $this->belongsToMany('App\Models\User', 'user_follows', 'follower_id', 'following_id');
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function followers()
    {
        return $this->belongsToMany('App\Models\User', 'user_follows', 'following_id', 'follower_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Commment', 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review', 'user_id');
    }

    public function favoriteBooks()
    {
        return $this->belongsToMany('App\Models\Book', 'favorites');
    }

    public function readBooks()
    {
        return $this->belongsToMany('App\Models\Book', 'marks')->where('action', config('common.read'));
    }

    public function readingBooks()
    {
        return $this->belongsToMany('App\Models\Book', 'marks')->where('action', config('common.reading'));
    }

    public function bookRequests()
    {
        return $this->belongsToMany('App\Models\Book', 'book_requests');
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function setAvatarLinkAttribute($avatar)
    {
        $imgFolder = config('upload.image_upload');
        if (!File::isDirectory($imgFolder)) {
            File::makeDirectory($imgFolder, 775, true);
        }
        if (!is_file($avatar)) {
            if (!isset($this->attributes['avatar_link'])) {
                $this->attributes['avatar_link'] = config('upload.default');
            }
        } else {
            $fileName = time() . '.' . $avatar->getClientOriginalExtension();
            if (Image::make($avatar->getRealPath())->resize(config('upload.default_size'), config('upload.default_size'))->save(config('upload.image_upload') . $fileName)) {
                return $this->attributes['avatar_link'] = $fileName;
            } else {
                $this->attributes['avatar_link'] = $this->avatar_link;
            }
        }
    }

    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }

    public function getAvatarLinkAttribute()
    {
        return asset(config('upload.image_upload') . $this->attributes['avatar_link']);
    }

    protected $appends = ['gender_name'];

    public function getGenderNameAttribute()
    {
        return $this->attributes['gender'] ? trans('user.male') : trans('user.female');
    }

    public function delete()
    {
        try {
            parent::delete();
            $this->reviews()->delete();
            $this->deleteAvatar();
            $this->hasMany('App\Models\BookRequest', 'user_id')->delete();

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function deleteAvatar()
    {
        if (isset($this->attributes['avatar_link']) && $this->attributes['avatar_link'] != config('upload.default')) {
            $pathAvatarFile = config('upload.image_upload') . $this->attributes['avatar_link'];
            if (File::exists($pathAvatarFile)) {
                File::delete($pathAvatarFile);
            }
        }
    }
}

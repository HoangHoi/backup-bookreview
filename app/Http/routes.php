<?php

Route::get('/', [
    'as' => 'home',
    'uses' => 'HomeController@index',
]);

Route::group(['prefix' => 'book'], function () {
    Route::get('/', ['uses' => 'BookController@index', 'as' => 'book.index']);
    Route::get('category/{category}', ['uses' => 'BookController@showByCategory', 'as' => 'book.showByCategory']);
    Route::get('/{bookId}', [
        'as' => 'book.show',
        'uses' => 'BookController@show',
    ]);
    Route::group(['middleware' => 'auth'], function() {
        Route::post('favorite', ['uses' => 'BookController@favorite', 'as' => 'book.favorite']);
        Route::post('mark', ['uses' => 'BookController@mark', 'as' => 'book.mark']);
    });
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('login', ['uses' => 'AdminController@getLogin', 'as' => 'admin.getLogin']);
    Route::post('login', ['uses' => 'AdminController@postLogin', 'as' => 'admin.postLogin']);
    Route::get('logout', ['uses' => 'AdminController@logout', 'as' => 'admin.logout']);

    Route::group(['middleware' => 'admin'], function() {
        Route::get('/', [
            'uses' => 'HomeController@index',
            'as' => 'admin.home',
        ]);
        Route::resource('category', 'CategoryController', [
            'only' => [
                'create',
                'store',
                'index',
            ],
        ]);
        Route::group(['prefix' => 'category/ajax'], function() {
            Route::get('list', ['uses' => 'CategoryController@ajaxList', 'as' => 'admin.category.ajaxList']);
            Route::delete('delete', ['uses' => 'CategoryController@ajaxDelete', 'as' => 'admin.category.ajaxDelete']);
            Route::post('update', ['uses' => 'CategoryController@ajaxUpdate', 'as' => 'admin.category.ajaxUpdate']);
        });
        Route::resource('book', 'BookController', [
            'only' => [
                'create',
                'store',
                'index',
            ],
        ]);
        Route::group(['prefix' => 'book/ajax'], function() {
            Route::get('list', ['uses' => 'BookController@ajaxList', 'as' => 'admin.book.ajaxList']);
            Route::delete('delete', ['uses' => 'BookController@ajaxDelete', 'as' => 'admin.book.ajaxDelete']);
            Route::post('update', ['uses' => 'BookController@ajaxUpdate', 'as' => 'admin.book.ajaxUpdate']);
            Route::post('get-one', ['uses' => 'BookController@ajaxGetOne', 'as' => 'admin.book.ajaxGetOne']);
        });
        Route::get('book-request', ['uses' => 'BookRequestController@index', 'as' => 'admin.bookRequest.index']);
        Route::group(['prefix' => 'book-request/ajax'], function() {
            Route::get('list', ['uses' => 'BookRequestController@ajaxList', 'as' => 'admin.bookRequest.ajaxList']);
            Route::delete('delete', ['uses' => 'BookRequestController@ajaxDelete', 'as' => 'admin.bookRequest.ajaxDelete']);
            Route::post('accept', ['uses' => 'BookRequestController@ajaxAccept', 'as' => 'admin.user.ajaxAccept']);
        });
        Route::get('user', ['uses' => 'UserController@index', 'as' => 'admin.user.index']);
        Route::group(['prefix' => 'user/ajax'], function() {
            Route::get('list', ['uses' => 'UserController@ajaxList', 'as' => 'admin.user.ajaxList']);
            Route::delete('delete', ['uses' => 'UserController@ajaxDelete', 'as' => 'admin.user.ajaxDelete']);
            Route::post('update', ['uses' => 'UserController@ajaxUpdate', 'as' => 'admin.user.ajaxUpdate']);
            Route::post('create', ['uses' => 'UserController@ajaxCreate', 'as' => 'admin.user.ajaxCreate']);
            Route::post('resetPassword', [
                'uses' => 'UserController@ajaxResetPassword',
                'as' => 'admin.user.ajaxResetPassword'
            ]);
        });
        Route::get('review', ['uses' => 'ReviewController@index', 'as' => 'admin.review.index']);
        Route::group(['prefix' => 'review/ajax'], function() {
            Route::get('list', ['uses' => 'ReviewController@ajaxList', 'as' => 'admin.review.ajaxList']);
            Route::delete('delete', ['uses' => 'ReviewController@ajaxDelete', 'as' => 'admin.review.ajaxDelete']);
        });
    });
});

Route::get('signup', [
    'uses' => 'AuthUserController@getSignup',
    'as' => 'getSignup'
]);

Route::get('signin', [
    'uses' => 'AuthUserController@getSignin',
    'as' => 'getSignin'
]);

Route::group(['middleware' => 'auth', 'prefix' => 'user'], function() {
    Route::get('signout', [
        'uses' => 'AuthUserController@getSignout',
        'as' => 'signout'
    ]);
});

Route::post('signup', [
    'uses' => 'AuthUserController@postSignup',
    'as' => 'signup'
]);

Route::post('signin', [
    'uses' => 'AuthUserController@postSignin',
    'as' => 'signin'
]);

Route::resource('users', 'UserController');

Route::group(['middleware' => 'auth'], function() {
    Route::get('profile-preview', [
        'uses' => 'UserController@getEditProfile',
        'as' => 'getEditProfile'
    ]);

    Route::post('profile-preview', [
        'uses' => 'UserController@postUpdateProfile',
        'as' => 'updateProfile'
    ]);

    Route::post('follow-user', [
        'uses' => 'UserController@postFollowUser',
        'as' => 'postFollowUser'
    ]);

    Route::get('/reviews/create/{book_id}', [
        'uses' => 'ReviewController@getCreateReview',
        'as' => 'getCreateReview'
    ]);

    Route::post('like-review', [
        'uses' => 'UserController@postLikeReview',
        'as' => 'postLikeReview'
    ]);

    Route::post('add-comment', [
        'uses' => 'CommentController@postAddComment',
        'as' => 'postAddComment'
    ]);

    Route::post('delete-comment', [
        'uses' => 'CommentController@postDeleteComment',
        'as' => 'postDeleteComment'
    ]);
});

Route::resource('reviews', 'ReviewController');

Route::post('load-comment', [
    'uses' => 'CommentController@postLoadComment',
    'as' => 'postLoadComment'
]);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('users', 'UserController@index');
Route::get('users/{user}', 'UserController@show');
Route::get('users/{user}/posts', 'UserController@posts');

Route::get('posts', 'PostController@index');
Route::get('posts/{post}', 'PostController@show');
Route::get('posts/{post}/comments', 'PostController@comments');

Route::get('comments', 'CommentController@index');
Route::get('comments/{comment}', 'CommentController@show');

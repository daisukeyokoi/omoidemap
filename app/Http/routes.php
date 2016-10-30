<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MypageController;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/////////////////////////////////////////////////////////////////////////////
// 一般ページ(ログインしなくても見れるページ)
/////////////////////////////////////////////////////////////////////////////

// Topページ
Route::get('/', 'TopController@index');

// 会員登録画面表示
Route::group(['prefix' => 'register'], function() {
  Route::get('/', 'Auth\AuthController@getRegister');
  Route::post('/', 'Auth\AuthController@postRegister');
  Route::get('/confirm/{token}', 'Auth\AuthController@getConfirm');
});

// パスワードリセット(リセットリクエスト -> メール内URL -> パスワード再設定)
Route::group(['prefix' => 'reminder'], function (){
	Route::get('/', 'Auth\PasswordController@getReminder');
	Route::post('/', 'Auth\PasswordController@postReminder');
	Route::get('/reset/{token}', 'Auth\PasswordController@getReset');
	Route::post('/reset', 'Auth\PasswordController@postReset');
});



// ログイン画面表示
Route::group(['prefix' => 'login'], function() {
  Route::get('/', 'Auth\AuthController@getLogin');
  Route::post('/', 'Auth\AuthController@postLogin');
});

// 新着記事を表示
Route::group(['prefix' => 'new_post'], function() {
    Route::get('/', 'TopController@getNewPost');
});

// ajax県取得
Route::any('/get_prefectures', 'TopController@ajaxGetPrefectures');


// ログアウト (認証済みが前提だが未認証でもokなのでフィルタ外)
Route::get('/logout', 'Auth\AuthController@getLogout');

// 画像表示
Route::get('/show/{image}', 'TopController@showImage');

// 記事詳細ページ
Route::group(['prefix' => 'article'], function() {
    Route::get('/detail/{id}', 'TopController@getArticleDetail');
});


/////////////////////////////////////////////////////////////////////////////
// 会員ページ
/////////////////////////////////////////////////////////////////////////////

// マイページ
Route::group(['middleware' => 'auth'], function (){
  Route::group(['prefix' => 'mypage'], function() {
    // top
    Route::get('/', 'MypageController@getIndex');
    Route::get('/updateprofile', 'MypageController@getUpdateProfile');

    // 記事投稿ページ
    Route::group(['prefix' => 'a_post'], function() {
      Route::get('/', 'MypageController@getArticlePost');
      Route::post('/', 'MypageController@postArticlePost');
    });
  });
});

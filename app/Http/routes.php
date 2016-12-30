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

// ログアウト (認証済みが前提だが未認証でもokなのでフィルタ外)
Route::get('/logout', 'Auth\AuthController@getLogout');

// 画像表示
Route::group(['prefix' => 'show'], function() {
    Route::get('/{image}', 'TopController@showPostImage');
    Route::get('/user/{id}', 'TopController@showUserImage');
    Route::get('/event/{image}', 'TopController@showEventImage');
});


// 記事詳細ページ
Route::group(['prefix' => 'article'], function() {
    Route::get('/detail/{id}', 'TopController@getArticleDetail');
});

// タグページ
Route::group(['prefix' => 'tag'], function() {
    Route::get('/{id}', 'TopController@getTagArticle');
});

// 検索ページ
Route::group(['prefix' => 'search'], function() {
    Route::get('/', 'TopController@getSearch');
});

// ランキングページ
Route::group(['prefix' => 'ranking'], function() {
    Route::get('/', 'TopController@getRanking');
});

// イベントページ
Route::group(['prefix' => 'event'], function() {
    Route::get('/{id}', 'TopController@getEvent');
});

/////////////ajax
// 県取得
Route::any('/get_prefectures', 'TopController@ajaxGetPrefectures');
// いいね
Route::any('/plus_good', 'TopController@ajaxPlusGood');
// 記事取得
Route::any('/ajax/article_list', 'TopController@ajaxGetArticle');

Route::group(['prefix' => 'ajax/ranking'], function() {
    // 都道府県ランキング取得
    Route::any('/prefectures', 'TopController@ajaxGetRankingPrefectures');
    // 感情ランキング取得
    Route::any('/feeling', 'TopController@ajaxGetRankingFeeling');
    // タグランキング取得
    Route::any('/tags', 'TopController@ajaxGetRankingTags');
});



/////////////////////////////////////////////////////////////////////////////
// 会員ページ
/////////////////////////////////////////////////////////////////////////////



// マイページ
Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => 'auth_general'], function (){
        Route::group(['prefix' => 'mypage'], function() {
            // top
            Route::get('/', 'MypageController@getIndex');

            // 記事投稿ページ
            Route::group(['prefix' => 'a_post'], function() {
              Route::get('/', 'MypageController@getArticlePost');
              Route::post('/', 'MypageController@postArticlePost');
            });

            // いいねページ
            Route::get('/good', 'MypageController@getGood');

            // フォロー中のタグページ
            Route::get('/followtag', 'MypageController@getFollowtag');

            // プロフィールページ
            Route::get('/updateprofile', 'MypageController@getUpdateProfile');

        });
    });
});
// コメント投稿
Route::post('/post/comment', 'TopController@postComment');


/////////////////////////////////////////////////////////////////////////////
// 管理画面
/////////////////////////////////////////////////////////////////////////////

Route::group(['middleware' => 'auth'], function() {
    Route::group(['middleware' => 'auth_admin'], function() {
        Route::group(['prefix' => 'admin'], function() {
            Route::get('/', 'AdminController@getIndex');

            // ユーザー関連
            Route::group(['prefix' => 'user'], function() {
                Route::get('/', 'AdminController@getUser');
                Route::get('/detail/{id}', 'AdminController@getUserDetail');
                Route::get('/edit/{id}', 'AdminController@getUserEdit');
                Route::post('/edit', 'AdminController@postUserEdit');
                Route::post('/delete', 'AdminController@deleteUser');
            });

            // 記事関連
            Route::group(['prefix' => 'posts'], function() {
                Route::get('/', 'AdminController@getPosts');
                Route::get('/detail/{id}', 'AdminController@getPostsDetail');
                Route::post('/delete', 'AdminController@deletePosts');
            });

            //　タグ関連
            Route::group(['prefix' => 'tags'], function() {
                Route::get('/', 'AdminController@getTags');
                Route::get('/detail/{id}', 'AdminController@getTagsDetail');
                Route::post('/delete', 'AdminController@deleteTags');
                Route::post('/ajax/create_tag', 'AdminController@ajaxCreateTag');
                Route::post('/ajax/delete_tag', 'AdminController@ajaxDeleteTag');
            });

            // イベント関連
            Route::group(['prefix' => 'event'], function() {
                Route::get('/', 'AdminController@getEvents');
                Route::get('/detail/{id}', 'AdminController@getEventDetail');
                Route::get('/create', 'AdminController@getEventCreate');
                Route::get('/create/ranking/{id}', 'AdminController@getEventCreateRanking');
                Route::post('/create', 'AdminController@postEventCreate');
                Route::get('/edit/{id}', 'AdminController@getEventEdit');
                Route::post('/edit', 'AdminController@postEventEdit');
                Route::post('/delete', 'AdminController@deleteEvent');
                Route::post('/ajax/create/ranking', 'AdminController@ajaxEventCreateRanking');
                Route::post('/ajax/delete/ranking', 'AdminController@ajaxEventDeleteRanking');
            });
        });
    });
});

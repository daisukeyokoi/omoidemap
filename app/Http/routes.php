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

// ソーシャルログイン

// twitter
Route::get('auth/twitter', 'Auth\AuthController@redirectToTwitterProvider');
Route::get('auth/twitter/callback', 'Auth\AuthController@handleTwitterProviderCallback');

// facebook
Route::get('auth/facebook', 'Auth\AuthController@redirectToFacebookProvider');
Route::get('auth/facebook/callback', 'Auth\AuthController@handleFacebookProviderCallback');



// 新着記事を表示
Route::group(['prefix' => 'new_post'], function() {
    Route::get('/', 'TopController@getNewPost');
});

// お問い合わせ
Route::group(['prefix' => 'inquiry'], function () {
    Route::get('/', 'TopController@getInquiry');
    Route::post('/', 'TopController@postInquiry');
    Route::post('/complete', 'TopController@postConfirmInquiry');
    Route::get('/complete', 'TopController@getCompleteInquiry');
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
// 行きたい
Route::any('/plus_go', 'TopController@ajaxPlusGo');
// 記事取得
Route::any('/ajax/article_list', 'TopController@ajaxGetArticle');
// お気に入り
Route::any('/ajax/favorite/post', 'TopController@ajaxFavoritePost');
// お気に入りチェック
Route::any('/ajax/favcheck', 'TopController@ajaxFavoriteCheck');

// Route::group(['prefix' => 'ajax/ranking'], function() {
//     // 都道府県ランキング取得
//     Route::any('/prefectures', 'TopController@ajaxGetRankingPrefectures');
//     // 感情ランキング取得
//     Route::any('/feeling', 'TopController@ajaxGetRankingFeeling');
//     // タグランキング取得
//     Route::any('/tags', 'TopController@ajaxGetRankingTags');
// });



/////////////////////////////////////////////////////////////////////////////
// 会員ページ
/////////////////////////////////////////////////////////////////////////////



// マイページ
Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => 'auth_general'], function (){
        Route::group(['prefix' => 'mypage'], function() {
            // タイムライン
            Route::get('/', 'MypageController@getIndex');
            Route::post('/deletePost/{id}', 'MypageController@deletePost');
            Route::post('/deleteComment/{id}', 'MypageController@deleteComment');
            Route::post('/getPost', 'MypageController@getPostInfo');

            // コメント編集
            Route::post('/updateComment', 'MypageController@updateComment');

            // 記事編集ページ
            Route::get('/post/{id}', 'MypageController@getPost');
            Route::post('/post/updatepost', 'MypageController@updatePost');

            // 記事投稿ページ
            Route::group(['prefix' => 'a_post'], function() {
              Route::get('/', 'MypageController@getArticlePost');
              Route::post('/', 'MypageController@postArticlePost');
            });

            // いいねページ
            Route::get('/good', 'MypageController@getGood');

            // フォロー中のタグページ
            Route::group(['prefix' => 'followtag'], function() {
              Route::get('/', 'MypageController@getFollowtag');
              Route::post('/', 'MypageController@searchTag');
              Route::post('/follow', 'MypageController@followTag');
              Route::post('/unfollow', 'MypageController@unFollowTag');
            });

            // プロフィールページ
            Route::get('/updateprofile', 'MypageController@getUpdateProfile');
            Route::get('/updateprivacy', 'MypageController@getUpdatePrivacy');
            Route::post('/updateprofile/update', 'MypageController@updateProfile');
            Route::post('/updateprofile/updateEmail', 'MypageController@updateEmail');
            Route::post('/updateprofile/updatePassword', 'MypageController@updatePassword');
            Route::post('/updateprofile/updateImage', 'MypageController@updateImage');

            // プロフィール画像投稿
            // Route::post('/ajax/updateprofile', 'MypageController@ajaxImagePost');

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

            // タグ関連
            Route::group(['prefix' => 'tags'], function() {
                Route::get('/', 'AdminController@getTags');
                Route::get('/detail/{id}', 'AdminController@getTagsDetail');
                Route::post('/delete', 'AdminController@deleteTags');
                Route::post('/ajax/create_tag', 'AdminController@ajaxCreateTag');
                Route::post('/ajax/delete_tag', 'AdminController@ajaxDeleteTag');
            });

            // お問い合わせ関連
            Route::group(['prefix' =>  'inquiry'], function(){
                Route::get('/', 'AdminController@getInquiry');
                Route::get('/{id}', 'AdminController@getInquiryDetail');
                Route::post('/', 'AdminController@postInquiryResponse');
            });

            //　コメント関連
            Route::group(['prefix' => 'comments'], function() {
                Route::get('/', 'AdminController@getComments');
                Route::post('/ajax/delete_comment', 'AdminController@ajaxDeleteComment');
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Helpers\AppUtil;
use App\PostsImage;
use App\Tag;
use App\Post;
use App\PostsTag;
use App\Followtag;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Event;
use App\User;
use App\Good;
use App\Prefecture;

class MypageController extends Controller
{
    // マイページTopを表示
    public function getIndex() {
        $user = Auth::user();
        $posts = Post::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
        return view('mypage.index', compact('user', 'posts'));
    }

    // いいねページ表示
    public function getGood() {
        $user = Auth::user();
        $goods = Good::where('user_id', $user->id)->get();
        $good_posts = [];
        if (count($goods)>0) {
            foreach ($goods as $good) {
                $good_post = Post::find($good->post_id);
                array_push($good_posts, $good_post);
            }
        }
        return view('mypage.good', compact('user', 'good_posts'));
    }

    // フォロー中のタグページ表示
    public function getFollowtag() {
        $user = Auth::user();
        $follow_tags = Followtag::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
        return view('mypage.followtag', compact('user', 'follow_tags'));
    }

    // ajaxでタグを検索
    public function searchTag(Request $request) {
        // すでにフォローしているタグは表示しない
        $user = Auth::user();
        $followtags = Followtag::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
        $followtag_ids = [];
        foreach ($followtags as $followtag) {
            array_push($followtag_ids, $followtag->tag_id);
        }
        if ( $request->tag_name==null ) {
            $search_tags = [];
        } else {
            $search_tags = Tag::where('name', 'like', "%$request->tag_name%")->whereNotIn('id', $followtag_ids)->take(10)->get();
        }
        return response()->json([
            'message' => 'success',
            'search_tags' => $search_tags
        ]);
    }

    // ajaxでタグをフォロー
    public function followTag(Request $request) {
        $user_id = Auth::user()->id;
        $followtag = new Followtag();
        $followtag->user_id = $user_id;
        $followtag->tag_id  = $request->tag_id;
        $followtag->save();
        return response()->json([
            'message' => 'success'
        ]);
    }

    // ajaxでフォローしているタグを削除
    public function unFollowTag(Request $request) {
        $remove_tag = Followtag::where('id', $request->tag_id)->delete();
        return response()->json([
            'message' => 'success'
        ]);
    }

    // プロフィール編集ページ表示
    public function getUpdateProfile() {
        $user = Auth::user();
        $prefectures = Prefecture::all();
        return view('mypage.updateprofile', compact('user', 'prefectures'));
    }

    // プライバシー編集ページ表示
    public function getUpdatePrivacy() {
        $user = Auth::user();
        return view('mypage.updateprivacy', compact('user'));
    }

    // 投稿ページ表示
    public function getArticlePost() {
        $tags = Tag::take(10)->get();
        $today = Carbon::now();
        return view('mypage.articlepost.index', compact('tags', 'today'));
    }

    // 投稿編集ページ表示
    public function getPost($id) {
        $user = Auth::user();
        $post = Post::find($id);
        $post_tag_ids = PostsTag::where('post_id', $post->id)->get();
        $post_tags = [];
        foreach ($post_tag_ids as $post_tag_id) {
            $tag = Tag::find($post_tag_id->tag_id);
            array_push($post_tags, $tag->name);
        }
        $tags = Tag::take(10)->get();
        return view('mypage.updatepost', compact('post', 'post_tags', 'tags'));
    }

    // 投稿を編集
    public function updatePost(Request $request) {
        $rules = [
            'address_true'  => 'required',
            'photo_age'     => 'required',
            'photo_feeling' => 'required',
            'episode'       => 'required'
        ];
        $messages = [
            'address_true.required'  => '思い出の場所を入力してください',
            'photo_age.required'     => '思い出の種類を選んでください',
            'photo_feeling.required' => '当時の年齢を選んでください',
            'episode.required'       => '思い出のエピソードを記入してください'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withInput($request->all())->withErrors($validator);
        }
        Log::info($request->all());
        $post_id = $request->post_id;
        $post = Post::find($post_id);
        $post->lat     = $request->lat;
        $post->lng     = $request->lng;
        $post->age     = $request->photo_age;
        $post->feeling = $request->photo_feeling;
        $post->address = $request->address_true;
        $post->episode = $request->episode;
        $post->save();

        // タグを削除しておく
        $old_post_tags = PostsTag::where('post_id', $post_id)->get();
        foreach ($old_post_tags as $post_tag) {
            $post_tag->delete();
        }

        // 画像の変更があった場合
        if (array_key_exists('image_file', $request->all())) {
            $post_image = PostsImage::where('post_id', $post_id)->first();
            $image_name = AppUtil::saveImage($request->image_file, 'posts');
            $post_image->image = '/show/'.$image_name;
            $post_image->save();
        }

        // タグの変更があった場合
        if (array_key_exists('tags', $request->all())) {
            foreach ($request->tags as $key => $value) {
                $new_post_tag = new PostsTag();
                $new_post_tag->post_id = $request->post_id;
                $tags = Tag::all();
                foreach ($tags as $tag) {
                    if ($tag->name == $value) {
                        $new_post_tag->tag_id = $tag->id;
                    }
                }
                $new_post_tag->save();
            }
        }

        return redirect(url('/mypage/'));
    }

    // 投稿を削除
    public function deletePost(Request $request) {
        $post_id = $request->post_id;
        $post = Post::findOrFail($post_id);
        $post->delete();
        return redirect(url('/mypage/'));
    }

    // 写真アップロード
    public function postArticlePost(Request $request) {
        try {
            DB::beginTransaction();
            // 記事の生成
            $post = new Post();
            $post->user_id = Auth::user()->id;
            $post->age     = $request->photo_age;
            $post->feeling = $request->photo_feeling;
            $post->episode = $request->episode;
            $post->address = $request->address;
            $post->lat     = $request->lat;
            $post->lng     = $request->lng;
            // イベントID
            if (isset($request->event_id)) {
                if (count(Event::find($request->event_id)) != 0) {
                    $post->event_id = $request->event_id;
                }
            }
            $post->save();

            // タグの生成
            if (!empty($request->tags)) {
                $tags = array_unique($request->tags);
                Log::info($tags);
                for ($i = 0; $i < count($tags); $i ++) {
                    if (!Tag::where('name', $tags[$i])->exists()) {
                        $tag = new Tag();
                        $tag->name = $tags[$i];
                        $tag->save();
                    }else {
                        $tag = Tag::where('name', $tags[$i])->first();
                    }

                    // タグとポストの中間の生成
                    $post_tag = new PostsTag();
                    $post_tag->post_id = $post->id;
                    $post_tag->tag_id  = $tag->id;
                    $post_tag->save();
                }
            }

            // 画像の生成
            if ($request->hasFile('file')) {
                $file = $request->file;
                for ($i = 0; $i < count($file); $i ++) {
                    $path = AppUtil::saveImage($file[$i], 'posts');
                    $image = new PostsImage();
                    $image->post_id = $post->id;
                    $image->image = '/show/'. $path;
                    $image->save();
                }
            }
            DB::commit();
            session()->flash('flash_message', '投稿が完了しました!');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'error'
            ]);
        }
        return response()->json([
            'message' => 'success'
        ]);

    }

    // プロフィール情報を変更
    public function updateProfile(Request $request) {
        $rules = [
            'nickname'   => 'required',
            'year'       => 'required',
            'month'      => 'required',
            'day'        => 'required',
            'sex'        => 'required',
            'birthplace' => 'required'
        ];
        $messages = [
            'nickname.required'   => 'ニックネームを入力してください',
            'year.required'       => '生まれた年を入力してください',
            'month.required'      => '生まれた月を入力してください',
            'day.required'        => '生まれた日を入力してください',
            'sex.required'        => '性別を入力してください',
            'birthplace.required' => '出身地を入力してください',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withInput($request->all())->withErrors($validator);
        }
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        if (count($user) == 0) {
            abort(404);
        }
        $user->nickname    = $request->nickname;
        $user->birth_year  = $request->year;
        $user->birth_month = $request->month;
        $user->birth_day   = $request->day;
        $user->sex         = $request->sex;
        $user->birthplace  = $request->birthplace;
        $user->save();
        return redirect(url('/mypage/updateprofile'));
    }

    // ajaxでプロフィール画像をアップロード
    public function ajaxImagePost(Request $request) {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $user->image = AppUtil::saveImage($request->file, 'user_profile', 150);
        $user->save();
        return response()->json([
            'message' => 'success'
        ]);
    }

    // メールアドレスを変更
    public function updateEmail(Request $request) {
        $rules = [
            'email' => 'email|confirmed|unique:users|max:255',
        ];
        $messages = [
            'email.email'     => 'メールアドレスの形式で入力してください',
            'email.unique'    => 'そのメールアドレスは既に使用されています',
            'email.max'       => 'メールアドレスは255字以内で入力してください',
            'email.confirmed' => '再入力されたメールアドレスと異なります'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withInput($request->all())->withErrors($validator);
        }
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        if (count($user) == 0) {
            abort(404);
        }
        $user->email = $request->email;
        $user->save();
        return redirect(url('/mypage/updateprivacy'));
    }

    // パスワードを変更
    public function updatePassword(Request $request) {
        $rules = [
            'password' => 'confirmed|min:6|max:64|alpha_num'
        ];
        $messages = [
            'password.min'       => 'パスワードは6文字以上で入力してください',
            'password.max'       => 'パスワードは64文字以下で入力してください',
            'password.alpha_num' => 'パスワードは半角英数字で入力してください',
            'password.confirmed' => '再入力されたパスワードと異なります'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withInput($request->all())->withErrors($validator);
        }
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        if (count($user) == 0) {
            abort(404);
        }
        $user->password = $request->password;
        $user->save();
        return redirect(url('/mypage/updateprivacy'));
    }

    // プロフィール画像を変更
    public function updateImage(Request $request) {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $user->image = AppUtil::saveImage($request->file, 'user_profile', 150);
        $user->save();
        return redirect(url('/mypage/updateprofile'));
    }

}

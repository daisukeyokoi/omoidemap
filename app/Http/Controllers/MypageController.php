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
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Event;

class MypageController extends Controller
{
    // マイページTopを表示
    public function getIndex() {
        $user = Auth::user();
        $posts = Post::where('user_id', $user->id)->get();
        return view('mypage.index', compact('user', 'posts'));
    }

    // いいねページ表示
    public function getGood() {
        $user = Auth::user();
        return view('mypage.good', compact('user'));
    }

    // フォロー中のタグページ表示
    public function getFollowtag() {
        $user = Auth::user();
        return view('mypage.followtag', compact('user'));
    }

    // プロフィール編集ページ表示
    public function getUpdateProfile() {
        $user = Auth::user();
        return view('mypage.updateprofile', compact('user'));
    }

    // 投稿ページ表示
    public function getArticlePost() {
        $tags = Tag::take(10)->get();
        $today = Carbon::now();
        return view('mypage.articlepost.index', compact('tags', 'today'));
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
            $post->title   = $request->photo_title;
            $post->episode = $request->episode;
            $post->address = $request->address;
            $post->lat     = $request->lat;
            $post->lng     = $request->lng;
            $post->photo_date    = $request->photo_year. '-'. $request->photo_month. '-01';
            // イベントID
            if (isset($request->event_id)) {
                if (count(Event::find($request->event_id)) != 0) {
                    $post->event_id = $request->event_id;
                }
            }
            $post->save();

            // タグの生成
            if (!empty($request->tags)) {
                $tags = $request->tags;
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

}

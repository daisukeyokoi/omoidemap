<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use App\Region;
use App\Prefecture;
use Illuminate\Support\Facades\Validator;
use App\Comment;
use App\Good;
use Illuminate\Support\Facades\Auth;

class TopController extends Controller
{
    public function index() {
        $regions = Region::all();
        $posts = Post::paginate(20);
        return view('top', compact('regions', 'prefectures', 'posts'));
    }

    // 新着記事表示
    public function getNewPost() {
        $posts = Post::all();
        return view('new.index', compact('posts'));
    }

    // 画像表示
    public function showImage($image) {
        $path = 'images/'. $image;
        if (Storage::exists($path)) {
            $photo = Storage::get($path);
            return (new Response($photo, 200))->header('Content-Type', 'image/jpeg');
        }
    }

    // ユーザープロフィール画像表示
    public function showUserImage($id) {
        $path = 'user_profile/user_'. $id . '.jpg';
        if (Storage::exists($path)) {
            $photo = Storage::get($path);
            return (new Response($photo, 200))->header('Content-Type', 'image/jpeg');
        }else {
            $photo = Storage::get('user_profile/man.jpg');
            return (new Response($photo, 200))->header('Content-Type', 'image/jpeg');
        }
    }

    //　県取得
    public function ajaxGetPrefectures(Request $request) {
        $prefectures = Prefecture::where('region_id', $request->region_id)->get();
        return response()->json([
            'prefectures' => $prefectures
        ]);
    }

    // 記事取得
    public function ajaxGetArticle(Request $request) {
        if (empty(array_filter($request->all()))) {
            return response()->json([
                'message' => 'empty'
            ]);
        }
        $query = Post::orderBy('created_at', 'desc');
        if (isset($request->keyword)) {
            $query = $query->where('title', 'like', '%' .$request->keyword. '%')->orwhere('episode', 'like', '%'. $request->keyword. '%');
        }
        if (isset($request->region)) {
            $prefectures = Prefecture::where('region_id', $request->region)->get();
            foreach ($prefectures as $prefecture) {
                $query = $query->orwhere('address', 'like', '%'. $prefecture->name. '%');
            }
        }
        if (isset($request->prefecture)) {
            $query = $query->where('address', 'like', '%'. $request->prefecture. '%');
        }
        $articles = $query->get();
        return response()->json([
            'message' => 'success',
            'articles' => $articles
        ]);
    }

    // 記事詳細ページ
    public function getArticleDetail($id) {
        $article = Post::find($id);
        if (empty($article)) {
            abort(404);
        }
        return view('article.detail', compact('article'));
    }

    // いいね
    public function ajaxPlusGood(Request $request) {
        $post = Post::find($request->post_id);
        if (empty($post) || !Auth::check()) {
            return response()->json([
                'message' => 'error'
            ]);
        }
        $user_id = Auth::user()->id;
        if ($post->user_id == $user_id) {
            return response()->json([
                'message' => 'you'
            ]);
        }
        if (Good::where('post_id', $request->post_id)->where('user_id', $user_id)->exists()) {
            return response()->json([
                'message' => 'already'
            ]);
        }
        $good = new Good();
        $good->post_id = $request->post_id;
        $good->user_id = $user_id;
        $good->save();
        $good_count = Good::where('post_id', $request->post_id)->count();
        return response()->json([
            'message' => 'success',
            'count' => $good_count
        ]);
    }

    // コメント投稿
    public function postComment(Request $request) {
        $rules = [
            'user_id' => 'required',
            'comment' => 'required|min:5|max:500|string'
        ];
        $messages = [
            'user_id.required' => 'エラーが発生しました,リロードして再度ご投稿お願いいたします。',
            'comment.required' => 'コメントを入力してください。',
            'comment.min'      => 'コメントは5文字以上入力してください。',
            'comment.max' => 'コメントは500文字以内で入力してください。',
            'comment.string' => 'コメントは文字列で入力してください。'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
        $comment = new Comment();
        $comment->user_id = $request->user_id;
        $comment->content = $request->comment;
        $comment->post_id = $request->post_id;
        $comment->save();
        session()->flash('flash_message', 'コメントの投稿が完了しました。');
        return redirect(url('/article/detail', $comment->post_id));
    }
}

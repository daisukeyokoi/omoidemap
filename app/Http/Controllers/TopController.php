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

    //　県取得
    public function ajaxGetPrefectures(Request $request) {
        $prefectures = Prefecture::where('region_id', $request->region_id)->get();
        return response()->json([
            'prefectures' => $prefectures
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
}

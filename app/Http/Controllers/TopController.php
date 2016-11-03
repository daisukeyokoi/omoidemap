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
use App\Helpers\AppUtil;
use App\Tag;
use App\PostsTag;

class TopController extends Controller
{
    ///////////////////////// Topページ /////////////////////
    // Topページ表示
    public function index(Request $request) {
        $regions = Region::all();
        $posts = Post::all();
        return view('top', compact('regions', 'posts'));
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
        $posts = Post::mapRange($request->sw_lat, $request->sw_lng, $request->ne_lat, $request->ne_lng)
                        ->goodsSort()
                        ->get();
        $articles = [];
        foreach($posts as $post) {
            $articles[] = [
                [
                    'url'      => url('/article/detail', $post->id),
                    'image'    => "'" .url($post->oneImage->image)."'",
                    'title'    => $post->title,
                    'address'  => AppUtil::postNumberRemove($post->address),
                    'goods'    => count($post->goods),
                    'comments' => count($post->comments)
                ]
            ];
        }
        return response()->json([
            'articles' => $articles
        ]);
    }


    ///////////////////////// 新着ページ//////////////
    // 新着記事表示
    public function getNewPost() {
        $posts = Post::all();
        return view('new.index', compact('posts'));
    }


    ////////////////////////画像表示関連//////////////
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

    ////////////////////////// 記事詳細ページ///////////////////////
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
            'count'   => $good_count
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
            'comment.max'      => 'コメントは500文字以内で入力してください。',
            'comment.string'   => 'コメントは文字列で入力してください。'
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

    ////////////////////////// タグページ///////////////////////
    public function getTagArticle($id) {
        $tag = Tag::find($id);
        if (empty($tag)) {
            abort(404);
        }
        foreach($tag->postsTags as $postTag) {
            $p_id[] = $postTag->post_id;
        }
        $relatePostTags = PostsTag::whereIn('post_id', $p_id)->where('tag_id', '!=', $tag->id)->get()->unique('tag_id');
        $articles = $tag->postsTags()->leftjoin('posts', 'posts_tags.post_id', '=', 'posts.id')
                                     ->selectRaw('posts_tags.post_id, posts.*')
                                     ->groupBy('posts.id')
                                     ->leftJoin('goods', 'posts.id', '=', 'goods.post_id')
                                     ->selectRaw('posts.*, count(goods.post_id) as count')
                                     ->groupBy('posts.id')
                                     ->orderBy('count', 'desc')
                                     ->paginate(18);
        return view('tag.index', compact('tag', 'relatePostTags', 'articles'));
    }

    ////////////////////////// 検索ページ///////////////////////
    public function getSearch(Request $request) {
        $keyword = $request->h_keyword;
        if ($keyword != "") {
            $relate_tags = Tag::where('name', 'like', '%'.$keyword.'%')->get();
            $tag = Tag::where('name', $keyword)->value('id');
            if ($tag) {
                $query = Post::whereHas('postsTags', function($query) use($tag) {
                    $query->where('tag_id', $tag);
                })->orwhere('title', 'like', '%'.$keyword.'%')
                  ->orwhere('episode', 'like', '%'.$keyword.'%')
                  ->goodsSort();
            }else {
                $query = Post::where('title', 'like', '%'.$keyword.'%')
                                ->orwhere('episode', 'like', '%'.$keyword.'%')
                                ->goodsSort();
            }
            $posts = $query->paginate(18);
        }else {
            $relate_tags = [];
            $posts = Post::where('title', '')->paginate(18);
        }
        return view('search.index', compact('posts', 'relate_tags'));
    }

    ////////////////////////// ランキングページ///////////////////////
    public function getRanking() {
        $posts = Post::goodsSort()->take(3)->get();
        $rank = 0;
        $prefectures = Prefecture::all();
        $tags = Tag::postsSort()->take(30)->get();
        return view('ranking.index', compact('posts', 'rank', 'prefectures', 'tags'));
    }

    public function ajaxGetRankingPrefectures(Request $request) {
        $prefecture = Prefecture::find($request->prefecture_id);
        if (empty($prefecture)) {
            return response()->json([
                'message' => 'error'
            ]);
        }
        $posts = Post::where('address', 'like', '%'.$prefecture->name.'%')->goodsSort()->take(3)->get();
        if (count($posts) == 0) {
            return response()->json([
                'message' => 'error'
            ]);
        }else {
            $articles = self::createRankingArticles($posts);
            return response()->json([
                'message' => 'success',
                'posts'   => $articles
            ]);
        }
    }

    public function ajaxGetRankingFeeling(Request $request) {
        $posts = Post::where('feeling', $request->feeling_id)->goodsSort()->take(3)->get();
        if (count($posts) == 0) {
            return response()->json([
                'message' => 'error'
            ]);
        }else {
            $articles = self::createRankingArticles($posts);
            return response()->json([
                'message' => 'success',
                'posts'   => $articles
            ]);
        }
    }

    public function ajaxGetRankingTags(Request $request) {
        $tag = Tag::find($request->tag_id);
        if (count($tag) == 0) {
            return response()->json([
                'message' => 'error'
            ]);
        }
        $posts = $tag->postsTags()->leftjoin('posts', 'posts_tags.post_id', '=', 'posts.id')
                                  ->selectRaw('posts_tags.post_id, posts.*')
                                  ->groupBy('posts.id')
                                  ->leftJoin('goods', 'posts.id', '=', 'goods.post_id')
                                  ->selectRaw('posts.*, count(goods.post_id) as count')
                                  ->groupBy('posts.id')
                                  ->orderBy('count', 'desc')
                                  ->take(3)
                                  ->get();
        if (count($posts) == 0) {
          return response()->json([
              'message' => 'error'
          ]);
        }else {
            $articles = [];
            foreach($posts as $post) {
                $articles[] = [
                    [
                        'url'      => url('/article/detail', $post->post->id),
                        'image'    => "'" .url($post->post->oneImage->image)."'",
                        'title'    => $post->post->title,
                        'address'  => AppUtil::postNumberRemove($post->post->address),
                        'user_image' => url('/show/user', $post->post->user_id),
                        'goods'    => count($post->post->goods),
                        'comments' => count($post->post->comments)
                    ]
                ];
            }
          return response()->json([
              'message' => 'success',
              'posts'   => $articles
          ]);
        }
    }

    private function createRankingArticles($posts) {
        $articles = [];
        foreach($posts as $post) {
            $articles[] = [
                [
                    'url'      => url('/article/detail', $post->id),
                    'image'    => "'" .url($post->oneImage->image)."'",
                    'title'    => $post->title,
                    'address'  => AppUtil::postNumberRemove($post->address),
                    'user_image' => url('/show/user', $post->user_id),
                    'goods'    => count($post->goods),
                    'comments' => count($post->comments)
                ]
            ];
        }
        return $articles;
    }

}

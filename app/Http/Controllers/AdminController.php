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
use App\User;
use Carbon\Carbon;
use App\Event;
use App\EventPost;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function getIndex() {
        return view('admin.index');
    }

    // ユーザー関連
    public function getUser(Request $request) {
        $query = User::orderBy('created_at', 'desc');
        if (isset($request->nickname)) {
            $query = $query->where('nickname', 'like', '%'.$request->nickname.'%');
        }
        if (isset($request->email)) {
            $query = $query->where('email', 'like', '%'.$request->email.'%');
        }
        $users = $query->paginate(30);
        return view('admin.user.index', compact('users'));
    }

    public function getUserDetail($id)  {
        $user = AppUtil::userCheck($id);
        return view('admin.user.detail', compact('user'));
    }

    public function getUserEdit($id) {
        $user = AppUtil::userCheck($id);
        return view('admin.user.edit', compact('user'));
    }

    public function postUserEdit(Request $request) {
        $rules = [
            'nickname' => 'required|string',
            'email'    => 'required|email'
        ];
        $messages = [
            'nickname.required' => 'ニックネームを入力してください',
            'nickname.string'   => 'ニックネームは文字列で入力してください',
            'email.required'    => 'メールアドレスを入力してください',
            'email.email'       => 'メールアドレスを入力してください'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }
        $user = AppUtil::userCheck($request->user_id);
        $user->nickname = $request->nickname;
        $user->email    = $request->email;
        $user->save();
        session()->flash('flash_message', 'プロフィール編集が完了しました');
        return redirect(url('/admin/user'));
    }

    public function deleteUser(Request $request) {
        $user = AppUtil::userCheck($request->user_id);
        $user->delete();
        session()->flash('flash_message', '削除が完了しました');
        return redirect(url('/admin/user'));
    }

    // 記事関連
    public function getPosts(Request $request) {
        $prefectures = Prefecture::all();
        $query = Post::orderBy('created_at', 'desc');
        if ($request->age != "") {
            $query = $query->where('age', $request->age);
        }
        if ($request->feeling != "") {
            $query = $query->where('feeling', $request->feeling);
        }
        if ($request->episode != "") {
            $query = $query->where('episode', 'like', '%'.$request->episode.'%');
        }
        if ($request->prefectures != "") {
            $query = $query->where('address', 'like', '%'.$request->prefecture.'%');
        }
        $posts = $query->paginate(30);
        return view('admin.posts.index', compact('prefectures', 'posts'));
    }

    public function getPostsDetail($id) {
        $post = Post::find($id);
        if (count($post) == 0) {
            abort(404);
        }
        return view('admin.posts.detail', compact('post'));
    }

    public function deletePosts(Request $request) {
        $post = Post::find($request->post_id);
        if (count($post) == 0) {
            abort(404);
        }
        $path = 'posts/'.substr($post->oneImage->image, 6);
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
        $post->delete();
        session()->flash('flash_message', '削除が完了しました');
        return redirect(url('/admin/posts'));
    }

    // タグ関連
    public function getTags(Request $request) {
        $query = Tag::orderBy('created_at', 'desc');
        if ($request->name) {
            $query = $query->where('name', 'like', '%'.$request->name.'%');
        }
        $tags = $query->paginate(30);
        return view('admin.tag.index', compact('tags'));
    }

    // タグ作成
    public function ajaxCreateTag(Request $request) {
        if ($request->tag_name != '') {
            $tag = new Tag();
            $tag->name = $request->tag_name;
            $tag->save();
            return response()->json([
                'message' => 'success',
                'tag'     => $tag
            ]);
        }else {
            return response()->json([
                'message' => 'error'
            ]);
        }
    }

    //　タグ削除
    public function ajaxDeleteTag(Request $request) {
        $tag = Tag::find($request->tag_id);
        if (!empty($tag)) {
            $tag->delete();
            return response()->json([
                'message' => 'success',
                'tag_id'  => $tag->id
            ]);
        }else {
            return response()->json([
                'message' => 'error'
            ]);
        }
    }

    // イベント関連
    public function getEvents(Request $request) {
        $today = Carbon::now();
        $query = Event::orderBy('created_at', 'desc');
        if ($request->keyword) {
            $query = $query->where(function($q) {
                $q->where('title', 'like', '%'.$GLOBALS['request']->keyword.'%')
                  ->orWhere('description', 'like', '%'.$GLOBALS['request']->keyword.'%');
            });
        }

        if ($request->event_period) {
            if ($request->event_period == 'select') {
                $start = $request->start_year.'-'.$request->start_month.'-'.$request->start_day;
                $end = $request->end_year.'_'.$request->end_month.'-'.$request->end_day;
                if ($request->event_period_type) {
                    if ($request->event_period_type == 'start') {
                        $query = $query->whereBetween('start', [$start, $end]);
                    }else {
                        $query = $query->whereBetween('end', [$start, $end]);
                    }
                }
            }
        }
        if ($request->state) {
            switch ($request->state) {
                case 'yet':
                    $query = $query->where('state', Event::YET);
                    break;
                case 'open':
                    $query = $query->where('state', Event::OPEN);
                    break;
                case 'review':
                    $query = $query->where('state', Event::REVIEW);
                    break;
                case 'close':
                    $query = $query->where('state', Event::CLOSE);
                    break;
                default:
                    break;
            }
        }
        $events = $query->paginate(30);
        return view('admin.event.index', compact('today', 'events'));
    }

    // イベント詳細
    public function getEventDetail($id) {
        $event = Event::find($id);
        if (count($event) == 0) {
            abort(404);
        }
        $posts = Post::where('event_id', $event->id)->paginate(30);
        return view('admin.event.detail', compact('event', 'posts'));
    }


    // イベント作成
    public function getEventCreate() {
        $today = Carbon::now();
        return view('admin.event.create', compact('today'));
    }

    public function postEventCreate(Request $request) {
        $rules = [
            'image_file'  => 'required|image',
            'title'       => 'required|string',
            'description' => 'required|string',
            'start'       => 'required|date',
            'end'         => 'required|date|after:start',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
        $event = new Event();
        $event->title = $request->title;
        $event->description = $request->description;
        // 画像の生成
        if ($request->hasFile('image_file')) {
            $file = $request->image_file;
            $path = AppUtil::saveImage($file, 'events');
            $event->image = '/show/event/'. $path;
        }
        $event->start = $request->start;
        $event->end = $request->end;
        $event->state = Event::YET;
        $event->save();
        session()->flash('flash_message', 'イベントの作成が完了しました。');
        return redirect(url('/admin/event/create'));
    }

    // イベント編集
    public function getEventEdit($id) {
        $today = Carbon::now();
        $event = Event::find($id);
        if (empty($event)) {
            abort(404);
        }
        $start = explode('-', $event->start);
        $end = explode('-', $event->end);
        return view('admin.event.edit', compact('today', 'event', 'start', 'end'));
    }

    public function postEventEdit(Request $request) {
        $rules = [
            'title'       => 'required|string',
            'description' => 'required|string',
            'start'       => 'required|date',
            'end'         => 'required|date|after:start',
            'state'       => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
        $event = Event::find($request->event_id);
        if (empty($event)) {
            abort(404);
        }
        $event->title = $request->title;
        $event->description = $request->description;
        // 画像の生成
        if ($request->hasFile('image_file')) {
            $file = $request->image_file;
            $path = AppUtil::saveImage($file, 'events');
            $event->image = '/show/event/'. $path;
        }
        $event->start = $request->start;
        $event->end = $request->end;
        $event->state = $request->state;
        $event->save();
        session()->flash('flash_message', 'イベントの編集が完了しました。');
        return redirect(url('/admin/event/detail', $event->id));
    }


    public function getEventCreateRanking($id) {
        $event = Event::find($id);
        if (empty($event)) {
            abort(404);
        }
        $eventPosts = EventPost::where('event_id', $event->id)->orderBy('ranking', 'asc')->get();
        $posts = Post::where('event_id', $event->id)->get();
        return view('admin.event.create_ranking', compact('event', 'posts', 'eventPosts'));
    }

    public function ajaxEventCreateRanking(Request $request) {
        $eventPostExist = EventPost::where('event_id', $request->event_id)->where('post_id', $request->post_id)->exists();
        $res_array = [];
        $flg = AppUtil::FLG_OFF;
        if (!$eventPostExist) {
            try {
                DB::beginTransaction();
                if ($request->ranking <= 10) {
                    $eventPost = new EventPost();
                    $eventPost->event_id = $request->event_id;
                    $eventPost->post_id = $request->post_id;
                    $eventPost->ranking = $request->ranking;
                    $eventPost->save();

                    $eventPosts = EventPost::where('event_id', $request->event_id)->orderBy('ranking', 'asc')->get();
                    if (!empty($eventPosts)) {
                        $res_array = $this->createResArray($eventPosts, $res_array);
                        $flg = AppUtil::FLG_ON;
                    }
                }
                DB::commit();
            }catch (\Exception $e) {
                DB::rollBack();
                $flg = AppUtil::FLG_OFF;
            }
        }

        if ($flg == AppUtil::FLG_ON) {
            return response()->json([
                'message' => 'success',
                'posts'   => $res_array
            ]);
        }else {
            return response()->json([
                'message' => 'error'
            ]);
        }
    }

    public function ajaxEventDeleteRanking(Request $request) {
        $eventPosts = EventPost::where('event_id', $request->event_id)->orderBy('ranking', 'asc')->get();
        $eventPost = EventPost::where('event_id', $request->event_id)->where('post_id', $request->post_id)->first();
        $res_array = [];
        $flg = AppUtil::FLG_OFF;
        if (!empty($eventPost)) {
            try {
                DB::beginTransaction();
                $this_rank = $eventPost->ranking;
                $eventPost->delete();
                foreach ($eventPosts as $ep) {
                    if ($ep->ranking > $this_rank) {
                        $ranking = $ep->ranking - 1;
                        $ep->ranking = $ranking;
                        $ep->save();
                    }
                }
                $eventPosts = EventPost::where('event_id', $request->event_id)->orderBy('ranking', 'asc')->get();
                if (!empty($eventPosts)) {
                    $res_array = $this->createResArray($eventPosts, $res_array);
                    $flg = AppUtil::FLG_ON;
                }
                DB::commit();
             } catch (\Exception $e) {
                 DB::rollBack();
                 $flg = AppUtil::FLG_OFF;
             }
        }
        if ($flg == AppUtil::FLG_ON) {
            return response()->json([
                'message' => 'success',
                'posts'   => $res_array
            ]);
        }else {
            return response()->json([
                'message' => 'error'
            ]);
        }
    }

    private function createResArray($eventPosts, $res_array) {
        foreach ($eventPosts as $eventPost) {
            $res_array[] = [
                'url_img' => url(AppUtil::showPostImage($eventPost->post)),
                'ranking' => $eventPost->ranking,
                'post_id' => $eventPost->post->id
            ];
        }
        return $res_array;
    }
}

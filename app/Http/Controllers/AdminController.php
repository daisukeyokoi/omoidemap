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
        if (count($post) )
    }

    // イベント関連
    public function getEvents(Request $request) {
        $today = Carbon::now();
        $events = Event::first();
        return view('admin.event.index', compact('today', 'events'));
    }
    // イベント作成
    public function getEventCreate() {
        $today = Carbon::now();
        return view('admin.event.create', compact('today'));
    }

    public function postEventCreate(Request $request) {
        $rules = [
            'image_file' => 'required|image',
            'title'      => 'required|string',
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
}

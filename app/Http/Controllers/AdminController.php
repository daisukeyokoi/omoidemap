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

class AdminController extends Controller
{
    public function getIndex() {
        return view('admin.index');
    }

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
}

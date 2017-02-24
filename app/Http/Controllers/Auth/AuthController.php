<?php

namespace App\Http\Controllers\Auth;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Redirect;
use Carbon\Carbon;
use Illuminate\http\Request;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Config\Repository as Config;
use App\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Helpers\AppUtil;
use Socialite;
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

  ////////////////////////////////////////////////////////////////
	// 会員登録
	////////////////////////////////////////////////////////////////
  // ログイン画面表示
  public function getRegister() {
    return view('register.index');
  }

  // 仮会員登録
  public function postRegister(Request $request) {
    $rules = [
            'nickname'       => 'required|string',
			'password'       => 'required|confirmed|min:6|max:64|alpha_num',
			'email'          => 'required|max:255|email|unique:users',
		];

		$messages = [
          'nickname.required'  => 'ニックネームを入力してください。',
          'nickname.string'    => 'ニックネームは文字列で入力してください。',
          'password.required'  => 'パスワードを入力してください。',
          'password.confirmed' => '再入力されたパスワードと異なっています。',
          'password.min'       => 'パスワードは6文字以上で入力してください。',
          'password.max'       => 'パスワードは64文字以上で入力してください。',
          'password.alpha_num' => 'パスワードは半角英数字で入力してください。',
          'email.required'     => 'メールアドレスを入力してください。',
          'email.max'          => 'メールアドレスは255文字以内で入力してください。',
          'email.email'        => 'メールアドレスを入力してください。',
          'email.unique'       => 'そのメールアドレスは既に使用されています。',
		];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      return back()->withErrors($validator)->withInput($request->all());
    }

    $user = new User();
    $user->nickname = $request->nickname;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->makeConfirmationToken(config('app.key'));
    $user->save();

    Mail::send('emails.auth.mail_to_user_confirm', ['user' => $user, 'token' => $user->confirmation_token], function ($m) use ($user) {
        $m->from(env('MAIL_FROM_ADDRESS'), 'OmoideMap');

        $m->to($user->email, $user->name)->subject('仮会員登録の完了');
    });

    session()->flash('flash_message', 'ユーザー確認登録メールを送りました。ユーザー認証してください。');
		return redirect('/login');
  }

  /**
	 * メール承認(仮登録メール内URL)登録完了
	 * @param unknown $token
	 * @return Ambigous <\Illuminate\Routing\Redirector, \Illuminate\Http\RedirectResponse>
	 */
	public function getConfirm($token){
		$user = User::where('confirmation_token', $token)->first();
		if (! $user){
			session()->flash('flash_message', '無効なトークンです');
			return redirect('/login');
		}

		// トークンの初期化と登録完了日設定
		$user->confirm();
		$user->save();
		Auth::login($user);
		return redirect('/mypage');
	}

    /**
    * ログイン
    */
    public function getLogin(Request $request) {
        if (Auth::check()){
            if (Auth::user()->identification == User::IDENTIFICATION_GENERAL) {
                return redirect(url('/mypage'));
            }else {
                return redirect(url('/admin'));
            }
        }
        if (isset($request->a_d)) {
            session()->put('a_d', $request->a_d);
        }else {
            session()->forget('a_d');
        }
        return view('login.index');
    }

   /**
	 * ログイン処理
	 */
	public function postLogin(Request $request){
		$rules = [
			'email'    => 'required|email|max:255',
			'password' => 'required|max:64|min:6|alpha_num',
			'remember' => 'in:0,1'
		];
        $messages = [
          'email.required' => 'メールアドレスを入力してください。',
          'email.email'    => 'メールアドレスを入力してください。',
          'email.max'      => 'メールアドレスは255文字以内で入力してください。'
        ];
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()){
			return back()->withErrors($validator)->withInput($request->all());
		}

		$email    = $request->email;
		$password = $request->password;
		$remember = $request->has('remember', 0);

		// 認証処理
		if (Auth::attempt(['email' => $email, 'password' => $password, 'confirmation_token' => ''], $remember)){
			if (session()->has('a_d')) {
				return redirect(url('/article/detail', session()->pull('a_d')));
			} else {
                if (isset($request->event_id)) {
                    return redirect(url('/mypage/a_post?e_id='.$request->event_id));
                }
                if (Auth::user()->identification == User::IDENTIFICATION_GENERAL) {
                    return redirect(url('/mypage'));
                }else {
                    return redirect(url('/admin'));
                }
			}
		} else {
			session()->flash('flash_message', 'メールアドレスもしくはパスワードが間違っています。');
			return back()->withInput($request->except('password'));
		}
	}



    /**
	 * ログアウト処理
	 */
	public function getLogout(){
		Auth::logout();
		return redirect("/login");
	}



    /**
	 * ソーシャルログイン
	 */

    // twitter
    public function redirectToTwitterProvider() {
        return Socialite::driver('twitter')->redirect();
    }

    public function handleTwitterProviderCallback() {
        try {
            $user = Socialite::with('twitter')->user();
        }catch (Exeption $e) {
            return redirect(url('/auth/twitter'));
        }

        $nickname = $user->nickname;
        $email = $user->email;
        $oauth_id = $user->id;
        $avatar = $user->avatar_original;
        $condition = true;
        if (count($email) == 0) {
            session()->flash('flash_message', 'Twitterアカウントからemailアドレスへのアクセスを許可するように設定してください');
            return redirect(url('/login'));
        }

        if (!User::where('oauth_id', $oauth_id)->exists()) {
            $condition = $this->OAuthRegister($nickname, $email, $oauth_id, $avatar);
        }

        if (!$condition) {
            session()->flash('flash_message', '現在このメールアドレスは使用されているため利用できません。');
            return redirect('/register');
        }
        $password = $oauth_id;
        $login = Auth::attempt([
            'email' => $email,
            'password' => $password
        ]);
        if ($login) {
            return redirect(url('/mypage'));
        }

        session()->flash('flash_message', '認証に失敗しました。');
        return redirect('/login');

    }

    // facebook
    public function redirectToFacebookProvider() {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookProviderCallback() {
        try {
            $user = Socialite::driver('facebook')->user();
        }catch (Exeption $e) {
            return redirect(url('/auth/twitter'));
        }

		$email = $user->getEmail();
		$nickname = $user->getName();
        $oauth_id = $user->getId();
        $avatar = $user->getAvatar();
        $condition = true;
        if (count($email) == 0) {
            session()->flash('flash_message', 'Facebookアカウントからemailアドレスへのアクセスを許可するように設定してください');
            return redirect(url('/login'));
        }

        if (!User::where('oauth_id', $oauth_id)->exists()) {
            $condition = $this->OAuthRegister($nickname, $email, $oauth_id, $avatar);
        }

        if (!$condition) {
            session()->flash('flash_message', '現在このメールアドレスは使用されているため利用できません。');
            return redirect('/register');
        }
        $password = $oauth_id;
        $login = Auth::attempt([
            'email' => $email,
            'password' => $password
        ]);
        if ($login) {
            return redirect(url('/mypage'));
        }

        session()->flash('flash_message', '認証に失敗しました。');
        return redirect('/login');

    }

    private function OAuthRegister($nickname, $email, $oauth_id, $avatar) {
        if (User::where('email', $email)->exists()) {
            return false;
        }

        $user = new User();
        $user->nickname = $nickname;
        $user->email = $email;
        $user->oauth_id = $oauth_id;
        $user->password = bcrypt($oauth_id);
        $user->confirmation_token = "";
        $user->confirmed_at = Carbon::now();
        $user->created_at = Carbon::now();
        if (count($avatar) != 0) {
            $image = AppUtil::saveImage($avatar, 'user_profile', 700);
            $user->image = $image;
        }
        $user->save();
        return true;
    }











}

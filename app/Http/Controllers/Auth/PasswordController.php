<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Validator;
use Carbon\Carbon;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $redirectTo = '/mypage';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

  /**
	 * パスワードリマインダーページを表示する
	 */
	public function getReminder(){
		return view('reminder.form');
	}

  /**
	 * パスワード再設定用urlを記載したメールを送信する
	 */
	public function postReminder(Request $request){
    $rules = [
      'email' => 'required|email|max:255'
    ];
    $messages = [
      'email.required' => 'メールアドレスを入力してください。',
      'email.email'    => 'メールアドレスを入力してください。',
      'email.max'      => 'メールアドレスは255文字以内で入力してください。'
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      return back()->withErrors($validator)->withInput($request->all());
    }

		$response = Password::sendResetLink($request->only('email'), function (Message $message) {
			$message->subject("パスワード再設定手続きのご案内");
		});

		switch ($response) {
			case Password::RESET_LINK_SENT:
				return redirect()->back()->with('status', "パスワード再設定用メールを送信しました。");
			case Password::INVALID_USER:
				return redirect()->back()->withErrors(['email' => trans($response)]);
		}
	}

  /**
	 * パスワードリセットページを表示する
	 */
	public function getReset($token = null) {
		if (is_null($token)) {
			throw new NotFoundHttpException;
    }

		// トークンの存在チェック
		$rec = DB::table('password_resets')->where('token', $token)->first();
		if(is_null($rec)){
			throw new NotFoundHttpException;
		}
		// トークンの期限チェック 期限が過ぎていればデータを削除する
		$expire = Carbon::now()->subHours(config('auth.password.expire'));
		if($expire->gt(Carbon::parse($rec->created_at))){
			DB::table('password_resets')->where('token', $token)->delete();	// 物理削除
			var_dump(config('app.password.expire'));
			throw new NotFoundHttpException;
		}

		return view('reminder.reset')->with('token', $token);
	}

  /**
	 * パスワードリセット処理を行う
	 */
	public function postReset(Request $request) {
		$this->validate($request, [
				'token' => 'required',
				'email' => 'required|email|max:255',
				'password' => 'required|confirmed|min:6|max:64|string',
		],[
				'token.required' => 'tokenエラーです。',
				'password.required' => 'パスワードを入力してください。',
				'password.confiremed' => 'パスワードを確認してください。',
				'password.min' => 'パスワードは6文字以上で入力してください。',
				'password.max' => 'パスワードは64文字以内で入力してください。',
				'password.string' => 'パスワードは文字列で入力してください。',
		]);

		$credentials = $request->only(
				'email', 'password', 'password_confirmation', 'token'
		);

		$response = Password::reset($credentials, function ($user, $password) {
			$this->resetPassword($user, $password);
		});

		switch ($response) {
			case Password::PASSWORD_RESET:
				return redirect($this->redirectPath())->with('status', trans($response));

			default:
				return redirect()->back()
				->withInput($request->only('email'))
				->withErrors(['email' => trans($response)]);
		}
	}

  /**
	 * パスワード再設定処理
	 */
	protected function resetPassword($user, $password) {
		$user->password = bcrypt($password);
		$user->save();
		Auth::login($user);
	}
}

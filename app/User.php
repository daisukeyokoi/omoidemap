<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AppUtil;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Storage;


class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nickname', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];


    ////////////////////////////////////////////////////////////////////////////
	// util
	////////////////////////////////////////////////////////////////////////////
  // 本登録認証トークンの生成＆設定
	public function makeConfirmationToken($key){
		$this->confirmation_token = hash_hmac('sha256', str_random(40), $key);
		return $this->confirmation_token;
	}

  // 本登録完了処理(トークンの初期化と登録日設定)
	public function confirm(){
		$this->confirmed_at = Carbon::now();
		$this->confirmation_token = '';
	}

    public function favPosts() {
        return $this->belongsToMany('App\Post', 'favorites');
    }

    ////////////////////////////////////////////////////////////////////////////
	// 定数
	////////////////////////////////////////////////////////////////////////////
    const IDENTIFICATION_GENERAL = 0;
    const IDENTIFICATION_ADMIN   = 1;
}

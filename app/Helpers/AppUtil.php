<?php

namespace App\Helpers;

use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Exceptions\App\Exceptions;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;

class AppUtil {
  /** 汎用フラグ：OFF(0) */
	const FLG_OFF = 0;
	/** 汎用フラグ：ON(1) */
	const FLG_ON  = 1;

  //////////////////////////////////////////////////////////////
	// メール送信
	//////////////////////////////////////////////////////////////
	// $paramsは連想配列
	// viewpathは配列またはviewのみ　(html.view と text.view)を想定しています。// html用のものとtext用のものを想定している。
	public static function sendMail($fromAddress, $fromName, $toAddress, $toName, $viewPath, $params, $subject){
		try{
			Mail::send($viewPath, $params, function ($message) use($fromAddress, $fromName, $toAddress, $toName, $viewPath, $subject){
				$message->from($fromAddress, $fromName)->to($toAddress, $toName)->subject($subject);
			});
		}catch(Exception $e){
			Log::error('メール送信失敗', $e);
		}
	}
}

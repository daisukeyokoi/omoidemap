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
use App\PostsImage;

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

    //////////////////////////////////////////////////////////////
	// 画像保存
	//////////////////////////////////////////////////////////////
    public static function saveImage($file) {
        $img = Image::make($file);
        $img = self::rotateImage($img);
        $img = self::resizeImage($img, 1000);
        $path = str_random(10). '.jpg';
        if (!Storage::exists('images')) {
			Storage::makeDirectory('images');
		}
        $img->save(storage_path('app/images/' . $path));
        $img->destroy();
        return $path;
    }

    //////////////////////////////////////////////////////////////
	// 画像回転
	//////////////////////////////////////////////////////////////
    public static function rotateImage($img) {
		$exif = $img->exif();

		// exif情報が取得できない場合は何もしない
		if (empty($exif)){
			return $img;
		}

		if (array_key_exists('Orientation', $exif)) {
			switch ($exif['Orientation']) {
				case 3:
					$img = $img->rotate(-180);
					break;
				case 4:
					$img = $img->rotate(-180);
					break;
				case 5:
					$img = $img->rotate(-90);
					break;
				case 6:
					$img = $img->rotate(-90);
					break;
				case 7:
					$img = $img->rotate(-270);
					break;
				case 8:
					$img = $img->rotate(-270);
					break;
				default:
					$img = $img->rotate(0);
			}
		}
		return $img;
	}

    //////////////////////////////////////////////////////////////
	// 画像リサイズ
	//////////////////////////////////////////////////////////////
    public static function resizeImage($img , $size = 1000) {
		$width 	= $img->width();
		$height = $img->height();
		if ($width > $size && $height > $size) {
			if ($width >= $height) {
				$img->resize($size, null, function ($constraint) {
					$constraint->aspectRatio();
				});
			}else {
				$img->resize(null, $size, function ($constraint) {
					$constraint->aspectRatio();
				});
			}
		}elseif ($width > $size) {
			$img->resize($size, null, function ($constraint) {
				$constraint->aspectRatio();
			});
		}elseif ($height > $size) {
			$img->resize(null, $size, function ($constraint) {
				$constraint->aspectRatio();
			});
		}
		return $img;
	}
}

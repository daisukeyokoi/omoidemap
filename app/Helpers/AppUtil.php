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
use App\Tag;
use App\Post;

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
    public static function saveImage($file, $type) {
        $img = Image::make($file);
        $img = self::rotateImage($img);
        $img = self::resizeImage($img, 1000);
        $path = str_random(10). '.jpg';
        if (!Storage::exists($type)) {
			Storage::makeDirectory($type);
		}
        $img->save(storage_path('app/'. $type. '/' . $path));
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
    public static function resizeImage($img , $size = 700) {
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

    //////////////////////////////////////////////////////////////
	// 投稿の年代のリスト
	//////////////////////////////////////////////////////////////

    const CHILD_AGE    = 1;
    const TEEN_AGE     = 2;
    const TWENTIES     = 3;
    const THIRTIES     = 4;
    const FORTIES      = 5;
    const FIFTIES      = 6;
    const SIXTIES      = 7;
    const OVER_SIXTIES = 8;

    public static function photoAgeList() {
        $list = [];
        $list['子供時代']  = self::CHILD_AGE;
        $list['10代']     = self::TEEN_AGE;
        $list['20代']     = self::TWENTIES;
        $list['30代']     = self::THIRTIES;
        $list['40代']     = self::FORTIES;
        $list['50代']     = self::FIFTIES;
        $list['60代']     = self::SIXTIES;
        $list['60代以上']  = self::OVER_SIXTIES;
        return $list;
    }

    public static function photoAgeLabel() {
        $label = [];
        $label[self::CHILD_AGE]    = '子供時代';
        $label[self::TEEN_AGE]     = '10代';
        $label[self::TWENTIES]     = '20代';
        $label[self::THIRTIES]     = '30代';
        $label[self::FORTIES]      = '40代';
        $label[self::FIFTIES]      = '50代';
        $label[self::SIXTIES]      = '60代';
        $label[self::OVER_SIXTIES] = '60代以上';
        return $label;
    }

    //////////////////////////////////////////////////////////////
	// 投稿の感情のリスト
	//////////////////////////////////////////////////////////////
    const HAPPY     = 1;
    const SAD       = 2;
    const FUN       = 3;
    const NOSTALGIC = 4;

    public static function photoFeelingList() {
        $list = [];
        $list['嬉しい'] = self::HAPPY;
        $list['悲しい'] = self::SAD;
        $list['楽しい'] = self::FUN;
        $list['懐かしい'] = self::NOSTALGIC;
        return $list;
    }

    public static function photoFeelingLabel() {
        $label = [];
        $label[self::HAPPY]     = '嬉しい';
        $label[self::SAD]       = '悲しい';
        $label[self::FUN]       = '楽しい';
        $label[self::NOSTALGIC] = '懐かしい';
        return $label;
    }

    //////////////////////////////////////////////////////////////
	// 郵便番号の削除
	//////////////////////////////////////////////////////////////
    public static function postNumberRemove($address) {
        $new_address = preg_replace("/(〒|ZIP：)\d{3}-\d{4}/", '', $address);
        return $new_address;
    }

    //////////////////////////////////////////////////////////////
	// ユーザーの存在確認
	//////////////////////////////////////////////////////////////
    public static function userCheck($user_id) {
        $user = User::find($user_id);
        if (empty($user)) {
            abort(404);
        }
        return $user;
    }

    //////////////////////////////////////////////////////////////
	// 人気のタグ
	//////////////////////////////////////////////////////////////
    public static function popularTags() {
        $tags = Tag::postsSort()->take(20)->get();
        return $tags;
    }

    //////////////////////////////////////////////////////////////
	// 人気の記事
	//////////////////////////////////////////////////////////////
    public static function popularPosts() {
        $posts = Post::goodsSort()->take(5)->get();
        return $posts;
    }

    //////////////////////////////////////////////////////////////
	// 文字を丸める
	//////////////////////////////////////////////////////////////
    public static function wordRound($string, $word_num) {
        $length = mb_strlen($string, 'utf-8');
		if ($length > $word_num) {
			$string = mb_substr($string, 0, $word_num, 'utf-8'). '...';
		}
		return $string;
    }

    //////////////////////////////////////////////////////////////
	// Topページの記事リストのタグ表示
	//////////////////////////////////////////////////////////////
    public static function topTag($post) {
        $tag_count = 0;
        $postTags = $post->postsTags;
        $tags_name = 'タグ:';
        foreach($postTags as $postTag) {
            if ($tag_count > 5) {
                break;
            }
            $tags_name = $tags_name . '<span class="article_list_data_tag">'. $postTag->tag->name. '</span>';
            $tag_count += 1;
        }
        return $tags_name;
    }
}

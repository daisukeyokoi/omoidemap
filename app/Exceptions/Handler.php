<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }
        
        //これはTokenmismatch errorの場合。
		if ($e instanceof TokenMismatchException){
			//現在はこのエラーに対してのviewをここで表示
			return response()->view('errors.sessiontime', [], 400);
		}

		// 存在しないURLの場合
		if ($e instanceof NotFoundHttpException){
			return response()->view('errors.404', [], 404);
		}

		// 未定義のHttpリクエストタイプ
		if ($e instanceof MethodNotAllowedHttpException){
			return response()->view('errors.404');
		}

		// メンテナンスモード
		if ($e instanceof  HttpException && $e->getStatusCode() == 503){
			return parent::render($request, $e);
		}

		// デバッグモードの場合、スタックトレースエラー画面を表示する
		if (config('app.debug')){
			return parent::render($request, $e);
		}

		return response()->view('errors.500', [], 500);
    }
}

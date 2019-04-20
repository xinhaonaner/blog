<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
        parent::report($e);
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
        switch ($e) {
            case $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException:
                return response(view('canvas::errors.404'), 404);
                break;

            case $e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException:
                return response(view('canvas::errors.404'), 404);
                break;

            case $e instanceof \Symfony\Component\HttpKernel\Exception\HttpException:
                return response(json_encode(['msg'=>'fail','code'=>0]), 503);
//                return response(view('canvas::errors.503'), 503);
                break;
            case $e instanceof ValidationException:
                return json_encode(['code'=>'402','msg'=>$e->getMessage()]);
            default:
                return parent::render($request, $e);
                break;
        }
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $e
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $e)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        } else {
            return view('canvas::auth.login');
        }
    }
}

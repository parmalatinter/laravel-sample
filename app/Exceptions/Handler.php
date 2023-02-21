<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->ajax()) {
                Log::error('[API Error] ' . $request->method() . ': ' . $request->fullUrl());
            }else{
                Log::error('[Error] ' . $request->method() . ': ' . $request->fullUrl());
            }

            switch (true) {
                case $e instanceof ValidationException:
                    Log::error($e->errors());
                    break;
                case $e instanceof HttpExceptionInterface:
                default:
                    $message = $e->getMessage() ? : HttpResponse::$statusTexts[$e->getStatusCode()];
                    Log::error($message);
                    break;
            }
        });

        $this->renderable(function (Throwable $e, Request $request) {

            if ($request->is('api/*') || $request->ajax()){

                switch (true) {
                    case $e instanceof HttpExceptionInterface:
                        $message = $e->getMessage() ? : HttpResponse::$statusTexts[$e->getStatusCode()];

                        return response()->json([
                            'message' => $message
                        ], $e->getStatusCode());
                    case $e instanceof ValidationException:
                        return $this->invalidJson($request, $e);
                    default:
                        return response()->json([
                            'message' => 'Internal Server Error'
                        ], 500);
                }
            }
        });
    }
}

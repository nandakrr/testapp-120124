<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        // echo $exception->getCode() . ":" . $exception->getMessage() . '<br>';
        // echo $exception->getFile() . ' : ' . $exception->getLine();exit;
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($this->isHttpException($e)) {
            switch ($e->getStatusCode()) {
                // not authorized
                case '403':
                    return \Response::view('errors.403',array(),403);
                    break;

                // not found
                case '404':
              //      Log::error('404 Error: ' . $e->getMessage() . ' | URL: ' . $request->fullUrl());
                    return \Response::view('errors.500',array(),404);
                    break;

                // internal error
                case '500':
                    return \Response::view('errors.500', array(), 500);
                    break;
					
                default:
                    return $this->renderHttpException($e);
                    break;
            }
        } else {
            return parent::render($request, $e);
        }

        //return parent::render($request, $exception);
    }
}

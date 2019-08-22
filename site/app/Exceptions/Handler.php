<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        /**
         * Trata dos erros de autenticação
         */
        if ($exception instanceof UnauthorizedHttpException) {
            $preException = $exception->getPrevious();
            if ($preException instanceof
                \Tymon\JWTAuth\Exceptions\TokenExpiredException
            ) {
                return responder()->error(401, 'Token expirado')->respond();
            } elseif ($preException instanceof
                \Tymon\JWTAuth\Exceptions\TokenInvalidException
            ) {
                return responder()->error(401, 'Token inválido')->respond();
            } elseif ($preException instanceof
                \Tymon\JWTAuth\Exceptions\TokenBlacklistedException
            ) {
                return responder()->error(401, 'Token na blacklist')->respond();
            } elseif ($exception->getMessage() === 'Token not provided') {
                return responder()->error(401, 'Token não fornecido')->respond();
            }
        }

        /**
         * Trata do erro quando uma rota não exixtente é acionada
         */
        if ($exception instanceof ModelNotFoundException) {
            return responder()->error(404, 'Recurso não encontrado')->respond();
        }

        return parent::render($request, $exception);
    }
}

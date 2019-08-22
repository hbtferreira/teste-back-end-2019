<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

/**
 * BaseController
 *
 * Determina as funções de envio das respostas
 * Códigos de erro possíveis:
 *
 * 200: OK.
 * 400: Bad request.
 * 401: Unauthorized.
 * 403: Forbidden.
 * 404: Not found.
 * 500: Internal server error.
 * 503: Service unavailable.
 */
class BaseController extends Controller
{
    /**
     * Envia a resposta em um objeto Json
     *
     * @param array|object|null $result O payload de retorno, quando houver
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result)
    {
        return responder()->success($result, null, null)->respond();
    }

    /**
     * Envia o erro em um objeto Json
     *
     * @param string $error A mensagem de erro amigável
     * @param int|null $code O código de resposta
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $code = 403)
    {
        return responder()->error($code, $error)->respond();
    }
}

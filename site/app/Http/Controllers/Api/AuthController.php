<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;

class AuthController extends BaseController
{
    /**
     * Cria uma nova instancia e valida as rotas que devem ser autenticadas
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth')->except('login');
    }

    /**
     * Gera um access_token válido por 1 minuto
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $body = $request->all();

            $validator = Validator::make($body, [
                'email' => 'required|email:rfc,dns,spoof,filter',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->getMessageBag(), 400);
            }

            if (!auth()->validate($body)) {
                return $this->sendError('Usuário ou senha inválida.', 400);
            }

            if (!$token = auth()->attempt($body)) {
                return $this->sendError('Token não pode ser gerado.', 400);
            }

            return $this->sendResponse($this->respondWithToken($token));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    /**
     * Desloga o usuário atual
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            auth()->logout();
            return $this->sendResponse(array('message' => 'Usuário deslogado com sucesso.'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    /**
     * Gera um novo token desde que esteja dentro do prazo máximo de 1 min
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $token = auth()->refresh();
            return $this->sendResponse($this->respondWithToken($token));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    /**
     * Retorna os dados do usuário logado
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            if (!$user = auth()->user()) {
                return $this->sendError('Usuário não encontrado.', 404);
            }
            return $this->sendResponse($user);
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    /**
     * Retorna um array contendo o token e o tempo para expiração (em segundos).
     *
     * @param string $token
     * @return array
     */
    protected function respondWithToken($token)
    {
        $retorno = array(
            'access_token' => $token,
            'expires_in_seconds' => auth()->factory()->getTTL() * 60
        );

        return $retorno;
    }
}

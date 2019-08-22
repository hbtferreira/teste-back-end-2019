<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProductController extends BaseController
{
    /**
     * Cria uma nova instancia e valida as rotas que devem ser autenticadas
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * Retorna a lista de produtos.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $products = Product::all();
            return $this->sendResponse($products);
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    /**
     * Cria um novo produto.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $body = $request->all();

            $validator = Validator::make($body, [
                'name' => 'required|string',
                'price' => 'required|numeric',
                'weight' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->getMessageBag(), 400);
            }

            $product = new Product([
                'name' => $body['name'],
                'price' => $body['price'],
                'weight' => $body['weight']
            ]);

            if (!$product->save()) {
                return $this->sendError('Houve um erro ao salvar o produto.', 401);
            }

            return $this->sendResponse(array('message' => 'Produto salvo com sucesso.'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    /**
     * Retorna os dados do produto.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $product = Product::find($id);

            if (is_null($product)) {
                return $this->sendError('Produto não encontrado', 404);
            }

            return $this->sendResponse($product);
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    /**
     * Atualiza os dados de um produto.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $body = $request->all();

            $validator = Validator::make($body, [
                'name' => 'required|string',
                'price' => 'required|numeric',
                'weight' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->getMessageBag(), 400);
            }

            $product = Product::find($id);

            if (is_null($product)) {
                return $this->sendError('Produto não encontrado', 404);
            }

            if ($product->name !== $body['name']) {
                $product->name = $body['name'];
            }

            if ($product->price != $body['price']) {
                $product->price = $body['price'];
            }

            if ($product->weight != $body['weight']) {
                $product->weight = $body['weight'];
            }

            if (!$product->save()) {
                return $this->sendError('Houve um erro ao atualizar o produto.', 401);
            }

            return $this->sendResponse(array('message' => 'Produto atualizado com sucesso.'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    /**
     * Remove um produto.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $product = Product::find($id);

            if (is_null($product)) {
                return $this->sendError('Produto não encontrado', 404);
            }

            if (!$product->delete()) {
                return $this->sendError('Houve um erro ao remover o produto.', 401);
            }

            return $this->sendResponse(array('message' => 'Produto removido com sucesso.'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(), 500);
        }
    }
}

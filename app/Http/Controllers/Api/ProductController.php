<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('kategori')->get();
        $response = ApiFormatter::createJson(200, 'Get products successfully', $products);
        return response()->json($response);
    }

    public function indexById($id)
    {
        $product = Product::with('kategori')->find($id);
        if (is_null($product)) {
            $response = ApiFormatter::createJson(404, 'Product not found');
            return response()->json($response, 404);
        }
        $response = ApiFormatter::createJson(200, 'Get product by id successfully', $product);
        return response()->json($response, 200);
    }

    public function create(Request $request)
    {
        try {
            $params = $request->all();
            $validator = Validator::make(
                $params,
                [
                    'kategori_id' => 'required|exists:kategoris,id',
                    'nama_product' => 'required|string|max:255',
                    'harga' => 'required|numeric',
                    'stock' => 'required|integer',
                ],
                [
                    'kategori_id.required' => 'Kategori ID is required',
                    'kategori_id.exists' => 'Kategori ID must be valid',
                    'nama_product.required' => 'Nama product is required',
                    'nama_product.string' => 'Nama product must be a string',
                    'harga.required' => 'Harga is required',
                    'harga.numeric' => 'Harga must be a valid number',
                    'stock.required' => 'Stock is required',
                    'stock.integer' => 'Stock must be an integer',
                ]
            );

            if ($validator->fails()) {
                $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                return response()->json($response, 400);
            }

            $product = Product::create([
                'kategori_id' => $params['kategori_id'],
                'nama_product' => $params['nama_product'],
                'harga' => $params['harga'],
                'stock' => $params['stock'],
            ]);

            $response = ApiFormatter::createJson(201, 'Create product successfully!', $product);
            return response()->json($response, 201);
        } catch (Throwable $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response, 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::find($id);
            if (is_null($product)) {
                $response = ApiFormatter::createJson(404, 'Product not found');
                return response()->json($response, 404);
            }

            $params = $request->all();
            $validator = Validator::make(
                $params,
                [
                    'kategori_id' => 'required|exists:kategoris,id',
                    'nama_product' => 'required|string|max:255',
                    'harga' => 'required|numeric',
                    'stock' => 'required|integer',
                ],
                [
                    'kategori_id.required' => 'Kategori ID is required',
                    'kategori_id.exists' => 'Kategori ID must be valid',
                    'nama_product.required' => 'Nama product is required',
                    'nama_product.string' => 'Nama product must be a string',
                    'harga.required' => 'Harga is required',
                    'harga.numeric' => 'Harga must be a valid number',
                    'stock.required' => 'Stock is required',
                    'stock.integer' => 'Stock must be an integer',
                ]
            );

            if ($validator->fails()) {
                $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                return response()->json($response, 400);
            }

            $product->update([
                'kategori_id' => $params['kategori_id'],
                'nama_product' => $params['nama_product'],
                'harga' => $params['harga'],
                'stock' => $params['stock'],
            ]);

            $response = ApiFormatter::createJson(200, 'Update product successfully!', $product);
            return response()->json($response, 200);
        } catch (Throwable $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response, 500);
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::find($id);
            if (is_null($product)) {
                $response = ApiFormatter::createJson(404, 'Product not found');
                return response()->json($response, 404);
            }

            $product->delete();
            $response = ApiFormatter::createJson(200, 'Product deleted successfully!');
            return response()->json($response, 200);
        } catch (Throwable $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response, 500);
        }
    }
}

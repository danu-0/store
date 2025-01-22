<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        $response = ApiFormatter::createJson(200, 'Get kategori successfully', $kategoris);
        return response()->json($response);
    }

    public function indexById($id)
    {
        $kategori = Kategori::find($id);
        if (is_null($kategori)) {
            $response = ApiFormatter::createJson(404, 'Kategori not found');
            return response()->json($response, 404);
        }
        $response = ApiFormatter::createJson(200, 'Get kategori by id successfully', $kategori);
        return response()->json($response, 200);
    }

    public function create(Request $request)
    {
        try {
            $params = $request->all();
            $validator = Validator::make(
                $params,
                [
                    'kategori' => 'required|string|max:255',
                ],
                [
                    'kategori.required' => 'Kategori is required',
                    'kategori.string' => 'Kategori must be a string',
                    'kategori.max' => 'Kategori must not exceed 255 characters',
                ]
            );

            if ($validator->fails()) {
                $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                return response()->json($response, 400);
            }

            $kategori = Kategori::create([
                'kategori' => $params['kategori'],
            ]);

            $response = ApiFormatter::createJson(201, 'Create kategori successfully!', $kategori);
            return response()->json($response, 201);
        } catch (Throwable $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response, 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $kategori = Kategori::find($id);
            if (is_null($kategori)) {
                $response = ApiFormatter::createJson(404, 'Kategori not found');
                return response()->json($response, 404);
            }

            $params = $request->all();
            $validator = Validator::make(
                $params,
                [
                    'kategori' => 'required|string|max:255',
                ],
                [
                    'kategori.required' => 'Kategori is required',
                    'kategori.string' => 'Kategori must be a string',
                    'kategori.max' => 'Kategori must not exceed 255 characters',
                ]
            );

            if ($validator->fails()) {
                $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                return response()->json($response, 400);
            }

            $kategori->update([
                'kategori' => $params['kategori'],
            ]);

            $response = ApiFormatter::createJson(200, 'Update kategori successfully!', $kategori);
            return response()->json($response, 200);
        } catch (Throwable $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response, 500);
        }
    }

    public function delete($id)
    {
        try {
            $kategori = Kategori::find($id);
            if (is_null($kategori)) {
                $response = ApiFormatter::createJson(404, 'Kategori not found');
                return response()->json($response, 404);
            }

            $kategori->delete();
            $response = ApiFormatter::createJson(200, 'Kategori deleted successfully!');
            return response()->json($response, 200);
        } catch (Throwable $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response, 500);
        }
    }
}

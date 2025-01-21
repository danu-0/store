<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Throwable;
use App\Helpers\ApiFormatter;
use App\Models\Pembayaran;
use App\Models\Product;

class PembayaranController extends Controller
{

    public function __construct()
    {
    $this->middleware('auth:api');
    }

    // ADMIN: Melihat semua pembayaran
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(ApiFormatter::createJson(403, 'Access Denied: Only admin can access this'), 403);
        }

        $pembayaran = Pembayaran::with(['customer', 'product'])->get();
        return response()->json(ApiFormatter::createJson(200, 'Get all payments successfully', $pembayaran));
    }

    // CUSTOMER: Melihat pembayaran mereka sendiri
    public function indexByUser(Request $request)
    {
        $userId = $request->user()->id;
        $pembayaran = Pembayaran::where('user_id', $userId)->with('product')->get();
        return response()->json(ApiFormatter::createJson(200, 'Get user payments successfully', $pembayaran));
    }

    // CUSTOMER: Membuat pembayaran baru
    public function create(Request $request)
    {
        try {
            $params = $request->all();
            $validator = Validator::make($params, [
                'product_id' => 'required|exists:product,id',
                'jumlah_product' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json(ApiFormatter::createJson(400, 'Validation Error', $validator->errors()->all()), 400);
            }

            $product = Product::findOrFail($params['product_id']);
            $total_pembayaran = $product->harga * $params['jumlah_product'];

            $pembayaran = Pembayaran::create([
                'product_id' => $params['product_id'],
                'user_id' => $request->user()->id,
                'jumlah_product' => $params['jumlah_product'],
                'total_pembayaran' => $total_pembayaran,
                'tanggal_pembayaran' => now(),
            ]);

            return response()->json(ApiFormatter::createJson(201, 'Payment created successfully', $pembayaran), 201);
        } catch (Throwable $e) {
            return response()->json(ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage()), 500);
        }
    }

    // ADMIN: Menghapus pembayaran
    public function delete($id)
    {
        try {
            $pembayaran = Pembayaran::find($id);
            if (!$pembayaran) {
                return response()->json(ApiFormatter::createJson(404, 'Payment not found'), 404);
            }

            $pembayaran->delete();
            return response()->json(ApiFormatter::createJson(200, 'Payment deleted successfully'), 200);
        } catch (Throwable $e) {
            return response()->json(ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage()), 500);
        }
    }
}

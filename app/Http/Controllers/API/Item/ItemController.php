<?php

namespace App\Http\Controllers\API\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;


class ItemController extends Controller
{
    // Alur untuk melihat semua data item dengan user role super admin / role_id = 1
    public function index()
    {
        try {
            $user = auth()->user();
            if ($user->role_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You dont have permission to access this resource',
                ], 403);
            }

            $item = \App\Models\Item::with('categories')->get();

            return response()->json([
                'success' => true,
                'message' => 'List All Item',
                'data' => $item
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve item data',
                'error' => $e->getMessage(),
            ]);
        }
    }

    // Alur untuk menyimpan data baru untuk item, dengan kondisi user harus role_id = 1 / super admin
    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user->role_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You dont have permission to access this resource',
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'categories_id' => 'required|integer|exists:item_categories,id',
                'name' => 'required|string|max:255',
                'price' => 'required|integer',
                'stock' => 'required|integer',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $item = \App\Models\Item::create([
                'categories_id' => $request->categories_id,
                'name' => $request->name,
                'price' => $request->price,
                'stock' => $request->stock,
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item created successfully',
                'data' => $item
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create item',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Alur untuk menampilkan data item sesuai dengan id item, dengan kondisi user harus role_id = 1 / super admin
    public function show(string $id)
    {
        try {
            $user = auth()->user();
            if ($user->role_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You dont have permission to access this resource',
                ], 403);
            }

            $item = \App\Models\Item::find($id);

            return response()->json([
                'success' => true,
                'message' => 'Item retrieved successfully',
                'data' => $item
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve item',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Alur untuk meng-update data item sesuai dengan id item, dengan kondisi user harus role_id = 1 / super admin
    public function update(Request $request, string $id)
    {
        try {
            $user = auth()->user();
            if ($user->role_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You dont have permission to access this resource',
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'categories_id' => 'required|integer|exists:item_categories,id',
                'name' => 'required|string|max:255',
                'price' => 'required|integer',
                'stock' => 'required|integer',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $item = \App\Models\Item::find($id);
            
            $item->categories_id = $request->categories_id;
            $item->name = $request->name;
            $item->price = $request->price;
            $item->stock = $request->stock;
            $item->status = $request->status;
            $item->save();

            return response()->json([
                'success' => true,
                'message' => 'Item updated successfully',
                'data' => $item
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update item',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    // Alur untuk menghapus data item sesuai dengan id item, dengan kondisi user harus role_id = 1 / super admin
    public function destroy(string $id)
    {
        try {
            $user = auth()->user();
            if ($user->role_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You dont have permission to access this resource',
                ], 403);
            }

            $item = \App\Models\Item::find($id);
        
            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete item',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

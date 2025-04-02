<?php

namespace App\Http\Controllers\API\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ItemCategoryController extends Controller
{
    // Alur untuk melihat semua data Item Category dengan user role super admin / role_id = 1
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

            $itemCategory = \App\Models\ItemCategory::all();

            if ($itemCategory->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item Category data not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'List All Item Category',
                'data' => $itemCategory
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve item category data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    // Alur untuk menyimpan data Item Category baru untuk role super admin / role_id = 1
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
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $itemCategory = \App\Models\ItemCategory::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item Category created successfully',
                'data' => $itemCategory
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create item category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Alur untuk menampilkan data Item Category sesuai dengan id Item Category dengan user role super admin / role_id = 1
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

            $itemCategory = \App\Models\ItemCategory::find($id);

            if ($itemCategory->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item Category data not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Item Category retrieved successfully',
                'data' => $itemCategory
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve item category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Alur untuk memperbarui data Item Category menurut id yang dimasukkan dengan user role super admin / role_id = 1
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
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $itemCategory = \App\Models\ItemCategory::find($id);
            
            if ($itemCategory->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item Category data not found',
                ], 404);
            }

            $itemCategory->name = $request->name;
            $itemCategory->description = $request->description;
            $itemCategory->status = $request->status;
            $itemCategory->save();

            return response()->json([
                'success' => true,
                'message' => 'Item Category updated successfully',
                'data' => $itemCategory
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update item category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Alur untuk meng-hapus data sesuai dengan id nya dengan ketentuan user role super admin / role_id = 1
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

            $itemCategory = \App\Models\ItemCategory::find($id);
            
            if ($itemCategory->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item Category data not found',
                ], 404);
            }

            $itemCategory->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item Category deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete item category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

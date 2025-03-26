<?php

namespace App\Http\Controllers\API\Package;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class PackageCategoryController extends Controller
{
    // Alur untuk melihat semua data Package Category dengan user role super admin / role_id = 1
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

            $packageCategory = \App\Models\PackageCategory::all();
            return response()->json([
                'success' => true,
                'message' => 'List All Package Category',
                'data' => $packageCategory
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve package category data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Alur untuk menyimpan data package category dengan role super admin / role_id = 1
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
                'name' => 'required',
                'type_category' => 'required|in:1,2,3,4',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $packageCategory = \App\Models\PackageCategory::create([
                'name' => $request->name,
                'type_category' => $request->type_category,
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Package category created successfully',
                'data' => $packageCategory
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create package category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Alur untuk melihat data package category berdasarkan id dengan role super admin / role_id = 1
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

            $packageCategory = \App\Models\PackageCategory::find($id);
            return response()->json([
                'success' => true,
                'message' => 'Package Category retrieved successfully',
                'data' => $packageCategory
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve package category data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Alur untuk mengupdate data package category berdasarkan id dengan role super admin / role_id = 1
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
                'name' => 'required',
                'type_category' => 'required|in:1,2,3,4',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $packageCategory = \App\Models\PackageCategory::find($id);
            $packageCategory->name = $request->name;
            $packageCategory->type_category = $request->type_category;
            $packageCategory->status = $request->status;
            $packageCategory->save();

            return response()->json([
                'success' => true,
                'message' => 'Package category updated successfully',
                'data' => $packageCategory
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update package category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Alur untuk meng-hapus data package category berdasarkan id dengan role super admin / role_id = 1
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

            $packageCategory = \App\Models\PackageCategory::find($id);
            
            if(!$packageCategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package Category not found',
                ], 404);
            }
            
            $packageCategory->delete();

            return response()->json([
                'success' => true,
                'message' => 'Package Category deleted successfully',
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete package category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

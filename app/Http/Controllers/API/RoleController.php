<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        try {
            $role = Role::all();

            if(empty($role)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Role is empty',
                    'data' => $role
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'List All Roles',
                'data' => $role
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve role data',
                'error' => $e->getMessage(),
            ]);
        }
    }

    // Alur untuk menambahkan data baru untuk role
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $role = Role::create([
                'name' => $request->name,
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully',
                'data' => $role
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create role',
                'error' => $e->getMessage(),
            ]);
        }
    }

    // Alur untuk menampilkan data role sesuai dengan id role
    public function show($id)
    {
        try {
            $role = Role::find($id);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Role retrieved successfully',
                'data' => $role
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve role',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Alur untuk memperbarui data role
    public function update(Request $request, $id)
    {
        try {
            $role = Role::find($id);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role not found',
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $role->name = $request->name;
            $role->status = $request->status;
            $role->save();

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully',
                'data' => $role
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update role',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Alur untuk menghapus data role menurut ID nya
    public function destroy($id)
    {
        try {
            $role = Role::find($id);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role not found',
                ], 404);
            }

            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully',
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete role',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}

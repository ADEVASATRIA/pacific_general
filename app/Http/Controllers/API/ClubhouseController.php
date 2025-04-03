<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ClubhouseController extends Controller
{

    // Digunakan untuk menampilkan semua data clubhouse dengan user role super admin / role_id = 1
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

            $clubhouse = \App\Models\Clubhouse::all();

            return response()->json([
                'success' => true,
                'message' => 'List All Clubhouse',
                'data' => $clubhouse
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve clubhouse data',
                'error' => $e->getMessage(),
            ]);
        }
    }

    // Digunakan untuk membuat data clubhouse baru untuk role super admin / role_id = 1
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
                'location' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $clubhouse = \App\Models\Clubhouse::create([
                'name' => $request->name,
                'location' => $request->location,
                'phone' => $request->phone,
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Clubhouse created successfully',
                'data' => $clubhouse
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create clubhouse',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Digunakan untuk menampilkan Data clubhouse menurut id yang dimasukkan dengan user role super admin / role_id = 1
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

            $clubhouse = \App\Models\Clubhouse::find($id);

            return response()->json([
                'success' => true,
                'message' => 'Clubhouse retrieved successfully',
                'data' => $clubhouse
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve clubhouse',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Digunakan untuk meng-update Data Clubhouse menurut id yang dimasukkan dengan user role super admin / role_id = 1
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
                'location' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $clubhouse = \App\Models\Clubhouse::find($id);
            
            $clubhouse->name = $request->name;
            $clubhouse->location = $request->location;
            $clubhouse->phone = $request->phone;
            $clubhouse->status = $request->status;
            $clubhouse->save();

            return response()->json([
                'success' => true,
                'message' => 'Clubhouse updated successfully',
                'data' => $clubhouse
            ], 200);
        } catch (Exception $e) { 
            return response()->json([
                'success' => false,
                'message' => 'Failed to update clubhouse',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Digunakan untuk menghapus data clubhouse menurut id yang dimasukkan dengan user role super admin / role_id = 1
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

            $clubhouse = \App\Models\Clubhouse::find($id);
            $clubhouse->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Clubhouse deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete clubhouse',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\API\Promo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\Promo\Promo;


class PromoController extends Controller
{
    // Fungsi untuk melihat semua data dengan user role super admin / role_id = 1
    public function index(){
        try {
            $user = auth()->user();
            if ($user->role_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You dont have permission to access this resource',
                ], 403);
            }
            $promo = Promo::all();
            if ($promo->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Promo not found',
                ], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'List All Promo',
                'data' => $promo
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve promo data',
                'error' => $e->getMessage(),
            ]);
        }
    }

    // Fungsi untuk menyimpan data promo dengan role super admin / role_id = 1
    public function store(Request $request){
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
                'code' => 'required|string|max:255',
                'type' => 'required|in:1,2',
                'value' => 'required',
                'max_discount' => 'nullable',
                'min_order_value' => 'nullable',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $promo = Promo::create([
                'name' => $request->name,
                'code' => $request->code,
                'type' => $request->type,
                'value' => $request->value,
                'max_discount' => $request->max_discount,
                'min_order_value' => $request->min_order_value,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => $request->status,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Promo created successfully',
                'data' => $promo,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create promo',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // Fungsi untuk melihat data promo berdasarkan id dengan role super admin / role_id = 1
    public function show($id){
        try {
            $user = auth()->user();
            if ($user->role_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You dont have permission to access this resource',
                ], 403);
            }
            $promo = Promo::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Promo retrieved successfully',
                'data' => $promo
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve promo data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Fungsi untuk mengupdate data promo berdasarkan id dengan role super admin / role_id = 1
    public function update(Request $request, $id){
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
                'code' => 'required|string|max:255',
                'type' => 'required|in:1,2',
                'value' => 'required',
                'max_discount' => 'nullable',
                'min_order_value' => 'nullable',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 422);
            }
            $promo = Promo::find($id);
            
            $promo->name = $request->name;
            $promo->code = $request->code;
            $promo->type = $request->type;
            $promo->value = $request->value;
            $promo->max_discount = $request->max_discount;
            $promo->min_order_value = $request->min_order_value;
            $promo->start_date = $request->start_date;
            $promo->end_date = $request->end_date;
            $promo->status = $request->status;
            $promo->save();

            return response()->json([
                'success' => true,
                'message' => 'Promo updated successfully',
                'data' => $promo,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update promo',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Fungsi untuk menghapus data promo berdasarkan id dengan role super admin / role_id = 1
    public function destroy($id){
        try {
            $user = auth()->user();
            if ($user->role_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You dont have permission to access this resource',
                ], 403);
            }
            $promo = Promo::find($id);
            $promo->delete();
            return response()->json([
                'success' => true,
                'message' => 'Promo deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete promo',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

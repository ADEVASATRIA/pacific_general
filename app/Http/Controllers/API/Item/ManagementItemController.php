<?php

namespace App\Http\Controllers\API\Item;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManagementItemController extends Controller
{
    // Alur untuk mengupdate sebuah quantity dari item sesuai dengan id item, dengan kondisi user harus role_id = 1 / super admin
    public function updateQtyItem(Request $request, string $id)
    {
        try {
            $user = auth()->user();
            if ($user->role_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You don\'t have permission to access this resource',
                ], 403);
            }
    
            $validator = Validator::make($request->all(), [
                'quantity' => 'required|integer|min:0',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }
    
            $item = \App\Models\Item::find($id);
            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found'
                ], 404);
            }
    
            $oldQuantity = $item->stock;
            
            $item->stock = $request->quantity;
            $item->save();
    
            \App\Models\ItemLog::create([
                'item_id' => $item->id,
                'action'  => "Barang Masuk: {$item->name}, stok lama: {$oldQuantity}, stok baru: {$request->quantity}"
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Item quantity updated successfully',
                'data' => $item
            ], 200);
    
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update item quantity',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    // Alur untuk melihat Semua Item Log data, dengan kondisi user harus role_id = 1 / super admin
    public function showAllItemLog(){
        try {
            $user = auth()->user();
            if($user->role_id != 1){
                return response()->json([
                    'success' => false,
                    'message' => 'You don\'t have permission to access this resource',
                ], 403);
            }

            $itemLog = \App\Models\ItemLog::all();

            if($itemLog->isEmpty()){
                return response()->json([
                    'success' => false,
                    'message' => 'Item Log not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'List All Item Log Data',
                'data' => $itemLog
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve item log data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Alur untuk melihat data item log sesuai dengan id yang diberikan, dengan kondisi user harus role_id = 1 / super admin
    public function showItemLog(string $id){
        try {
            $user = auth()->user();
            if ($user->role_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You don\'t have permission to access this resource',
                ], 403);
            }
            $itemLog = \App\Models\ItemLog::find($id);

            if (!$itemLog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item Log not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Item Log Retrieved successfully',
                'data' => $itemLog,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve Item Log Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Alur untuk melihat data item log menggunakan base dari Item_id yang diberikan, kondisi user harus role_id = 1 / super admin
    public function showItemByItemID(Request $request, string $item_id)
    {
        try {
            $user = auth()->user();
            if ($user->role_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You don\'t have permission to access this resource',
                ], 403);
            }

            $item = \App\Models\Item::find($item_id);
            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found'
                ], 404);
            }
    
            $logs = \App\Models\ItemLog::where('item_id', $item_id)->orderBy('created_at', 'desc')->get();
    
            return response()->json([
                'success' => true,
                'message' => 'Item logs retrieved successfully',
                'data' => $logs
            ], 200);
    
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve item logs',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
}

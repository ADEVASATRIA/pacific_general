<?php

namespace App\Http\Controllers\API\Purchase;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

use App\Models\Customer;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\MemberPass;
use App\Models\TicketType;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\API\Purchase\CreatePurchaseController;

class CartController extends Controller
{
    // Alur untuk memasukkan data ke keranjang
    public function addToCart(Request $request, CreatePurchaseController $purchaseService)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'telephone' => 'required|string',
            'items' => 'required|array',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.type_ticket_id' => 'required|integer|exists:ticket_types,id',
        ]);
    
        // Ambil semua ticket_type_id dari request
        $ticketTypeIds = collect($request->items)->pluck('type_ticket_id');
    
        // Cek jumlah tiket yang memiliki type_ticket = 2
        $memberTickets = TicketType::whereIn('id', $ticketTypeIds)
            ->where('type_ticket', 2)
            ->count();
    
        if ($memberTickets > 1) {
            return response()->json([
                'success' => false,
                'message' => 'You can only have one membership ticket in the cart.',
            ], 422);
        }
    
        DB::beginTransaction();
        try {
            // 1. Cek atau buat Customer
            $customer = Customer::firstOrNew(
                ['phone' => $request->telephone],
                [
                    'customer_type_id' => $request->customer_type_id,
                    'name' => $request->customer_name,
                ]
            );
    
            if (!$customer->exists) {
                $customer->save();
            }
    
            // 2. Ambil data tiket berdasarkan type_ticket_id
            $items = collect($request->items)->map(function ($item) {
                $ticket = TicketType::findOrFail($item['type_ticket_id']);
                return [
                    'ticket_type_id' => $ticket->id,
                    'type_purchase' => $ticket->type_ticket,
                    'name' => $ticket->name,
                    'quantity' => $item['quantity'],
                    'price' => $ticket->price,
                    'clubhouse_id' => $ticket->clubhouse_id,
                    'duration' => $ticket->duration,
                ];
            });
    
            // 3. Jika ada type_ticket = 2 (member), periksa apakah customer sudah memiliki MemberPass
            foreach ($items as $item) {
                if ($item['type_purchase'] == 2) {
                    $existingMemberPass = MemberPass::where('customer_id', $customer->id)
                        ->where('status', 1)
                        ->first();
    
                    if ($existingMemberPass) {
                        // Jika sudah ada MemberPass, perbarui data
                        $existingMemberPass->update([
                            'ticket_type_id' => $item['ticket_type_id'],
                            'clubhouse_id' => $item['clubhouse_id'],
                            'start_date' => now(),
                            'end_date' => now()->addDays($item['duration']),
                        ]);
                    } else {
                        // Jika belum ada MemberPass, buat baru
                        MemberPass::create([
                            'customer_id' => $customer->id,
                            'ticket_type_id' => $item['ticket_type_id'],
                            'clubhouse_id' => $item['clubhouse_id'],
                            'start_date' => now(),
                            'end_date' => now()->addDays($item['duration']),
                            'status' => 1,
                        ]);
                    }
                }
            }
    
            // 4. Hitung total harga pembelian menggunakan controller baru
            $totals = $purchaseService->calculateTotal($items);
            // 5. Buat Purchase menggunakan controller baru
            $purchase = $purchaseService->createPurchase($customer->id, $totals);
            // 6. Batch Insert Purchase Details menggunakan controller baru
            $purchaseService->batchInsertPurchaseDetails($purchase->id, $items);
    
            DB::commit();
    
            return response()->json([
                'message' => 'Purchase successfully added to cart',
                'purchase_id' => $purchase->id,
                'invoice' => $purchase->invoice,
            ], 201);
    
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to add purchase to cart',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    // Function untuk melihat keranjang menurut purchase id nya
    public function getCartData($purchaseId){
        try {
            $purchase = Purchase::with('customer', 'purchaseDetails')->find($purchaseId);
            if (!$purchase) {
                return response()->json([
                    'success' => false,
                    'message' => 'Purchase not found',
                ], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'Purchase data retrieved successfully',
                'data' => $purchase
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve purchase data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

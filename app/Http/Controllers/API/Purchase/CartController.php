<?php

namespace App\Http\Controllers\API\Purchase;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\API\Purchase\CreatePurchaseController;

use App\Services\Purchase\CartItemService;
use App\Services\Purchase\CustomerService;
use App\Services\Purchase\MemberPassService;

class CartController extends Controller
{

    protected $customerService;
    protected $cartItemService;
    protected $memberPassService;

    public function __construct(CustomerService $customerService, CartItemService $cartItemService, MemberPassService $memberPassService)
    {
        $this->customerService = $customerService;
        $this->cartItemService = $cartItemService;
        $this->memberPassService = $memberPassService;
    }

    public function addToCart(Request $request, CreatePurchaseController $purchaseService)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'telephone' => 'required|string',
            'items' => 'required|array',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.type_ticket_id' => 'nullable|integer|exists:ticket_types,id',
            'items.*.item_id' => 'nullable|integer|exists:items,id',
            'items.*.package_id' => 'nullable|integer|exists:packages,id',
        ]);

        DB::beginTransaction();
        try {

            $customer = $this->customerService->findOrCreateCustomer($request->all());

            $items = $this->cartItemService->processItems($request->items);

            $this->memberPassService->handleMemberPass($customer->id, $items);

            $totals = $purchaseService->calculateTotal($items);
            $purchase = $purchaseService->createPurchase($customer->id, $totals);

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

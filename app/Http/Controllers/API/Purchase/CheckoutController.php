<?php

namespace App\Http\Controllers\API\Purchase;

use App\Http\Controllers\API\Ticket\TicketPackageController;
use App\Http\Controllers\Controller;
use App\Models\PurchaseDetail;
use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\API\Ticket\TicketController;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'purchase_id' => 'required|integer|exists:purchases,id',
            'payment' => 'required|integer|in:1,2,3,4,5,6,7,8',
            'approval_code' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $purchase = Purchase::where('id', $validated['purchase_id'])->firstOrFail();
            if (!$purchase) {
                return response()->json([
                    'message' => 'Purchase not found',
                ], 404);
            }

            $paymentMethodRequiresApproval = in_array($validated['payment'], [4, 5, 8]);

            if ($paymentMethodRequiresApproval && empty($validated['approval_code'])) {
                return response()->json([
                    'message' => 'Approval code is required for this payment method',
                ], 400);
            }

            $purchase->update([
                'payment' => $validated['payment'],
                'approval_code' => $validated['approval_code'] ?? null,
                'status' => 2,
            ]);

            $purchaseDetail = PurchaseDetail::where('purchase_id', $purchase->id)->get();
            
            $hasRegularTickets = false;
            $hasPackageTickets = false;

            foreach ($purchaseDetail as $detail) {
                if ($detail->type_purchase != 3) {
                    $hasRegularTickets = true;
                } else {
                    $hasPackageTickets = true;
                }
            }

            if ($hasRegularTickets) {
                TicketController::generateTickets($purchase->id);
            }
            if ($hasPackageTickets) {
                TicketPackageController::generateTicketPackage($purchase->id); 
            }

            DB::commit();
            return response()->json([
                'message' => 'Checkout successful',
                'purchase_id' => $purchase->id,
                'status' => 'Paid',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Checkout failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

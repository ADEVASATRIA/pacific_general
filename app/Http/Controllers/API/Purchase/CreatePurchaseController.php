<?php

namespace App\Http\Controllers\API\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Promo\Promo;

use Illuminate\Support\Collection;


class CreatePurchaseController extends Controller
{
    public function checkPromo(?int $promoId)
    {
        if (!$promoId) {
            \Log::info('⚠️ No promo ID provided');
            return null;
        }

        $promo = Promo::find($promoId);

        \Log::info('🔍 Promo fetched from DB', ['promo' => $promo]);

        $now = now();
        if (
            $promo &&
            $promo->status == 1 &&
            $promo->start_date <= $now &&
            $promo->end_date >= $now
        ) {
            \Log::info('✅ Promo is valid and active', ['promo_id' => $promo->id]);
            return $promo;
        }

        \Log::warning('❌ Promo is not valid or not found', [
            'promo_id' => $promoId,
            'status' => $promo->status ?? null,
            'start_date' => $promo->start_date ?? null,
            'end_date' => $promo->end_date ?? null,
            'now' => $now,
        ]);

        return null;
    }




    public function calculateTotal(Collection $items, ?Promo $promo = null)
    {
        $subTotal = $items->sum(fn($item) => $item['quantity'] * $item['price']);
        $discount = 0;

        if ($promo && $subTotal >= $promo->min_order_value) {
            if ($promo->type === '1') {
                $discount = ($promo->value / 100) * $subTotal;
                if ($promo->max_discount) {
                    $discount = min($discount, $promo->max_discount);
                }
            } elseif ($promo->type === '2') {
                $discount = $promo->value;
            }

        } else {
            \Log::info('❌ Discount not applied', [
                'reason' => $promo ? 'Minimum order not reached' : 'No promo passed'
            ]);
        }

        $tax = $subTotal * 0.10;
        $total = $subTotal - $discount - $tax;

        return compact('subTotal', 'tax', 'discount', 'total', 'promo');
    }




    // Buat Purchase
    public function createPurchase($customerId, $totals)
    {
        return Purchase::create([
            'customer_id' => $customerId,
            'invoice' => 'INV-' . strtoupper(substr(md5(time()), 0, 8)),
            'sub_total' => $totals['subTotal'],
            'tax' => $totals['tax'],
            'discount' => $totals['discount'],
            'total' => $totals['total'],
            'payment' => null,
            'approval_code' => null,
            'status' => 0,
        ]);
    }

    // Batch Insert Purchase Details
    public function batchInsertPurchaseDetails($purchaseId, Collection $items)
    {
        $data = $items->map(fn($item) => [
            'purchase_id' => $purchaseId,
            'ticket_type_id' => $item['ticket_type_id'] ?? null,
            'item_id' => $item['item_id'] ?? null,
            'package_id' => $item['package_id'] ?? null,
            'type_purchase' => $item['type_purchase'],
            'name' => $item['name'],
            'qty' => $item['quantity'],
            'price' => $item['quantity'] * $item['price'],
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        PurchaseDetail::insert($data);
    }

}

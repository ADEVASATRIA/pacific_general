<?php

namespace App\Http\Controllers\API\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Purchase;
use App\Models\PurchaseDetail;

use Illuminate\Support\Collection;


class CreatePurchaseController extends Controller
{
    // Hitung total harga
    public function calculateTotal(Collection $items)
    {
        $subTotal = $items->sum(fn($item) => $item['quantity'] * $item['price']);
        $tax = $subTotal * 0.10;
        $total = $subTotal - $tax;

        return compact('subTotal', 'tax', 'total');
    }

    // Buat Purchase
    public function createPurchase($customerId, $totals)
    {
        return Purchase::create([
            'customer_id' => $customerId,
            'invoice' => 'INV-' . strtoupper(substr(md5(time()), 0, 8)),
            'sub_total' => $totals['subTotal'],
            'tax' => $totals['tax'],
            'discount' => null,
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
            'ticket_type_id' => $item['ticket_type_id'],
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

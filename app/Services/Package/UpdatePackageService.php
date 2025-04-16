<?php

namespace App\Services\Package;

use App\Models\Package\Package;
use App\Models\Package\PackageDetail;
use App\Models\TicketType;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Exception;

class UpdatePackageService
{
    public function execute(Package $package, array $validatedData)
    {
        DB::beginTransaction();

        try {
            $package->update([
                'package_category_id' => $validatedData['package_category_id'],
                'name' => $validatedData['name'],
                'price' => $validatedData['price'],
                'duration' => $validatedData['duration'],
                'status' => $validatedData['status'],
            ]);

            // Delete old details
            $package->packageDetails()->delete();

            // Recreate package details
            foreach ($validatedData['items'] as $item) {
                $nameDetailItem = null;

                if (!empty($item['ticket_type_id'])) {
                    $ticketType = TicketType::find($item['ticket_type_id']);
                    $nameDetailItem = $ticketType ? 'Ticket Type: ' . $ticketType->name : 'Unknown Ticket Type';
                } elseif (!empty($item['item_id'])) {
                    $itemData = Item::find($item['item_id']);
                    $nameDetailItem = $itemData ? 'Item: ' . $itemData->name : 'Unknown Item';
                }

                PackageDetail::create([
                    'package_id' => $package->id,
                    'ticket_type_id' => $item['ticket_type_id'] ?? null,
                    'item_id' => $item['item_id'] ?? null,
                    'name_detail_item' => $nameDetailItem,
                    'qty' => $item['qty'],
                ]);
            }

            DB::commit();

            return $package->load('packageDetails');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Failed to update package: " . $e->getMessage());
        }
    }
}

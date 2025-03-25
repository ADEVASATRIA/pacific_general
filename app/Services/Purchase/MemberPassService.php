<?php

namespace App\Services\Purchase;

use App\Models\MemberPass;

class MemberPassService
{
    public function handleMemberPass($customerId, $items)
    {
        foreach ($items as $item) {
            if (!empty($item['ticket_type_id'])) {
                $existingMemberPass = MemberPass::where('customer_id', $customerId)
                    ->where('status', 1)
                    ->first();

                $data = [
                    'ticket_type_id' => $item['ticket_type_id'],
                    'clubhouse_id' => $item['clubhouse_id'],
                    'start_date' => now(),
                    'end_date' => now()->addDays($item['duration']),
                    'status' => 1,
                ];

                if ($existingMemberPass) {
                    $existingMemberPass->update($data);
                } else {
                    MemberPass::create(array_merge(['customer_id' => $customerId], $data));
                }
            }
        }
    }
}

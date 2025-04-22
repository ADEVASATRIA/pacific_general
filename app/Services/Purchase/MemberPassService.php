<?php

namespace App\Services\Purchase;

use App\Models\MemberPass;
use App\Models\TicketType;

class MemberPassService
{
    public function handleMemberPass($customerId, $items)
    {
        $ticketTypeIds = collect($items)
            ->pluck('ticket_type_id')
            ->filter()
            ->unique()
            ->toArray();

        $memberTickets = TicketType::whereIn('id', $ticketTypeIds)
            ->where('type_ticket', 2)
            ->get();

        if ($memberTickets->isEmpty()) {
            return;
        }

        foreach ($items as $item) {
            if (!empty($item['ticket_type_id']) && $memberTickets->pluck('id')->contains($item['ticket_type_id'])) {

                if (empty($item['clubhouse_id'])) {
                    throw new \Exception("Clubhouse ID is required for Member Pass.");
                }

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
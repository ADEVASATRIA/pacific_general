<?php

namespace App\Services\Purchase;

use App\Models\Item;
use App\Models\TicketType;
use Exception;

class CartItemService
{
    public function processItems($items)
    {
        return collect($items)->map(function ($item) {
            if (!empty($item['type_ticket_id'])) {
                $ticket = TicketType::findOrFail($item['type_ticket_id']);
                return [
                    'ticket_type_id' => $ticket->id,
                    'item_id' => null,
                    'type_purchase' => 1,
                    'name' => $ticket->name,
                    'quantity' => $item['quantity'],
                    'price' => $ticket->price,
                    'clubhouse_id' => $ticket->clubhouse_id,
                    'duration' => $ticket->duration,
                ];
            } elseif (!empty($item['item_id'])) {
                $product = Item::findOrFail($item['item_id']);
                if ($product->stock < $item['quantity']) {
                    throw new Exception("Stock for {$product->name} is not enough.");
                }
                $product->decrement('stock', $item['quantity']);
                return [
                    'ticket_type_id' => null,
                    'item_id' => $product->id,
                    'type_purchase' => 2,
                    'name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'clubhouse_id' => null,
                    'duration' => null,
                ];
            }
        });
    }
}

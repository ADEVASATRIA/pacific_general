<?php

namespace App\Http\Controllers\API\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PurchaseDetail;
use App\Models\Package\Package;
use App\Models\Package\PackageDetail;
use App\Models\Ticket;
use App\Models\TicketEntry;
use App\Models\TicketType;


use Illuminate\Support\Str;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
class TicketPackageController extends Controller
{
    // Alur logic untuk membuat ticket package
    public static function generateTicketPackage($purchaseId)
    {
        $purchaseDetails = PurchaseDetail::where('purchase_id', $purchaseId)->get();

        foreach ($purchaseDetails as $detail) {
            $package = Package::find($detail->package_id);
            if (!$package) {
                continue; 
            }

            $packageDetails = PackageDetail::where('package_id', $package->id)->get();

            foreach ($packageDetails as $packageDetail) {
                if (!$packageDetail->ticket_type_id) {
                    continue;
                }

                $ticketType = TicketType::find($packageDetail->ticket_type_id);
                if (!$ticketType) {
                    continue;
                }

                $code = 'PG' . '-' . strtoupper(Str::random(4));

                $dateStart = Carbon::now();
                $dateEnd = $dateStart->copy()->addDays($ticketType->duration)->endOfDay();

                if ($ticketType->type_ticket == 2) {
                    self::deactivateExistingMemberTickets($detail->purchase->customer_id);
                }

                $ticket = Ticket::create([
                    'purchase_detail_id' => $detail->id,
                    'customer_id' => $detail->purchase->customer_id,
                    'ticket_type_id' => $ticketType->id,
                    'code' => $code,
                    'date_start' => $dateStart,
                    'date_end' => $dateEnd,
                    'is_active' => true,
                ]);

                self::generateTicketEntries($ticket, $packageDetail->qty);
            }
        }
    }

    private static function deactivateExistingMemberTickets($customerId)
    {
        Ticket::where('customer_id', $customerId)
            ->whereHas('ticketType', function ($query) {
                $query->where('type_ticket', 2);
            })
            ->where('is_active', 1)
            ->update(['is_active' => 0]);
    }

    public static function generateTicketEntries(Ticket $ticket, $qty)
    {
        for ($i = 0; $i < $qty; $i++) {
            TicketEntry::create([
                'ticket_id' => $ticket->id,
                'date_valid' => $ticket->date_end,
                'code_qr' => Str::uuid(),
                'status' => 1,
            ]);
        }
    }
}

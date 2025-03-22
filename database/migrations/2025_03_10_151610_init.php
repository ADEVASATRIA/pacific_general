<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

use App\Models\Admin;
use App\Models\Role;
use App\Models\Customer;
use App\Models\Clubhouse;
use App\Models\TicketType;
use App\Models\CoachPass;
use App\Models\MemberPass;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Ticket;
use App\Models\TicketEntry;

return new class extends Migration
{
    public function up(): void
    {
        Role::tableInit();
        Admin::tableInit();
        Customer::tableInit();
        Clubhouse::tableInit();
        TicketType::tableInit();
        CoachPass::tableInit();
        MemberPass::tableInit();
        Purchase::tableInit();
        PurchaseDetail::tableInit();
        Ticket::tableInit();
        TicketEntry::tableInit();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {        
        Role::tableDrop();
        Admin::tableDrop();
        Customer::tableDrop();
        Clubhouse::tableDrop();
        TicketType::tableDrop();
        CoachPass::tableDrop();
        MemberPass::tableDrop();
        Purchase::tableDrop();
        PurchaseDetail::tableDrop();
        Ticket::tableDrop();
        TicketEntry::tableDrop();
    }
};

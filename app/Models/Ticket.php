<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Ticket extends DefaultModel
{
    protected $fillable = [
        'purchase_detail_id',
        'customer_id',
        'ticket_type_id',
        'code',
        'date_start',
        'date_end',
        'is_active',
    ];

    public static function tableInit(){
        Schema::create(self::getTableName(), function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('purchase_detail_id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('ticket_type_id');
            $table->string('code');
            $table->date('date_start');
            $table->date('date_end');
            $table->boolean('is_active');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('purchase_detail_id')->references('id')->on(PurchaseDetail::getTableName())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('customer_id')->references('id')->on(Customer::getTableName())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ticket_type_id')->references('id')->on(TicketType::getTableName())->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function purchaseDetail(){
        return $this->belongsTo(PurchaseDetail::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function ticketType(){
        return $this->belongsTo(TicketType::class);
    }

    public function ticketEntries(){
        return $this->hasMany(TicketEntry::class);
    }
}

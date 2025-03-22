<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PurchaseDetail extends DefaultModel
{
    protected $fillable = [
        'purchase_id',
        'ticket_type_id',
        'type_purchase',
        'name',
        'qty',
        'price',
    ];

    public static function tableInit(){
        Schema::create(self::getTableName(), function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('purchase_id');
            $table->unsignedInteger('ticket_type_id')->nullable();
            $table->enum('type_purchase', [1, 2, 3])->comment('1:type_ticket 2:type_items 3:type_package');
            $table->string('name');
            $table->unsignedInteger('qty');
            $table->unsignedInteger('price');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('purchase_id')->references('id')->on(Purchase::getTableName())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ticket_type_id')->references('id')->on(TicketType::getTableName())->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function purchase(){
        return $this->belongsTo(Purchase::class);
    }
    
    public function ticketType(){
        return $this->belongsTo(TicketType::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoachPass extends DefaultModel
{
    protected $fillable = [
        'start_date',
        'end_date',
        'status',
    ];

    public static function tableInit(){
        Schema::create(self::getTableName(), function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('ticket_type_id');
            $table->unsignedInteger('clubhouse_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('status');

            $table->foreign('customer_id')->references('id')->on(Customer::getTableName())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ticket_type_id')->references('id')->on(TicketType::getTableName())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('clubhouse_id')->references('id')->on(Clubhouse::getTableName())->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function ticketType(){
        return $this->belongsTo(TicketType::class);
    }

    public function clubhouse(){
        return $this->belongsTo(Clubhouse::class);
    }
}

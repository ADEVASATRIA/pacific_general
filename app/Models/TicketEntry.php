<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TicketEntry extends DefaultModel
{
    protected $fillable = [
        'ticket_id',
        'date_valid',
        'code_qr',
        'status',
    ];

    public static function tableInit(){
        Schema::create(self::getTableName(), function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('ticket_id');
            $table->date('date_valid');
            $table->string('code_qr');
            $table->enum('status', [1, 2])->comment('1:new 2:inside');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ticket_id')->references('id')->on(Ticket::getTableName())->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function ticket(){
        return $this->belongsTo(Ticket::class);
    }
}

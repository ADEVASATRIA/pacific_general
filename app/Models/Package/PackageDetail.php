<?php

namespace App\Models\Package;

use App\Models\DefaultModel;
use App\Models\TicketType;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PackageDetail extends DefaultModel
{
    protected $fillable = [
        'package_id',
        'ticket_type_id',
        'item_id',
        'name_detail_item',
        'qty',
    ];

    public static function tableInit(){
        Schema::create(self::getTableName(), function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('package_id');
            $table->unsignedInteger('ticket_type_id')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->string('name_detail_item');
            $table->unsignedInteger('qty');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('package_id')->references('id')->on(\App\Models\Package\Package::getTableName())->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ticket_type_id')->references('id')->on(TicketType::getTableName())->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on(\App\Models\Item::getTableName())->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function package(){
        return $this->belongsTo(\App\Models\Package\Package::class);
    }
    public function ticketType(){
        return $this->belongsTo(TicketType::class);
    }
    public function item(){
        return $this->belongsTo(\App\Models\Item::class);
    }
}

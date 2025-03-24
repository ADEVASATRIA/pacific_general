<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ItemLog extends DefaultModel
{
    protected $fillable = [
        'item_id',
        'action',
    ];

    public static function tableInit(){
        Schema::create(self::getTableName(), function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('item_id');
            $table->string('action');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('item_id')->references('id')->on(Item::getTableName())->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function item(){
        return $this->belongsTo(Item::class);
    }
}

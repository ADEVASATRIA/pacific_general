<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Item extends DefaultModel
{
    protected $fillable = [
        'categories_id',
        'name',
        'price',
        'stock',
        'status',
    ];

    public static function tableInit(){
        Schema::create(self::getTableName(), function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('categories_id');
            $table->string('name');
            $table->unsignedInteger('price');
            $table->unsignedInteger('stock');
            $table->boolean('status');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('categories_id')->references('id')->on(ItemCategory::getTableName())->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function categories(){
        return $this->belongsTo(ItemCategory::class, 'categories_id');
    }

    public function itemlogs(){
        return $this->hasMany(ItemLog::class, 'item_id');
    }

    public function purchaseDetails(){
        return $this->hasMany(PurchaseDetail::class, 'item_id');
    }

    public function packageDetails(){
        return $this->hasMany(\App\Models\Package\PackageDetail::class, 'item_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Customer extends DefaultModel
{
    protected $fillable = [
        'name',
        'phone',
        'address',
    ];
    

    public static function tableInit() {
        Schema::create(self::getTableName(), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone');
            $table->string('address')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }
}

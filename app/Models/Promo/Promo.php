<?php

namespace App\Models\Promo;

use App\Models\DefaultModel;
use App\Models\Promo\PromoLog;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class Promo extends DefaultModel
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'max_discount',
        'min_order_value',
        'start_date',
        'end_date',
        'status',
    ];

    public static function tableInit()
    {
        Schema::create(self::getTableName(), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['1', '2'])->comment('1:percentage, 2:fixed');
            $table->unsignedInteger('value');
            $table->unsignedInteger('max_discount')->nullable();
            $table->unsignedInteger('min_order_value')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('status');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function promoLogs()
    {
        return $this->hasMany(PromoLog::class);
    }
}

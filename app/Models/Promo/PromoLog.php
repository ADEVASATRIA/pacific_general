<?php

namespace App\Models\Promo;

use App\Models\DefaultModel;
use App\Models\Promo\Promo;
use App\Models\Customer;
use App\Models\Purchase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PromoLog extends DefaultModel
{
    protected $fillable = [
        'promo_id',
        'customer_id',
        'purchase_id',
        'discount_applied',
    ];

    public static function tableInit(){
        Schema::create(self::getTableName(), function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('promo_id');
            $table->unsignedInteger('customer_id')->nullable();
            $table->unsignedInteger('purchase_id')->nullable();
            $table->string('discount_applied');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('promo_id')->references('id')->on(Promo::getTableName())->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on(Customer::getTableName())->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('purchase_id')->references('id')->on(Purchase::getTableName())->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class, 'promo_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
}

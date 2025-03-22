<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Purchase extends DefaultModel
{
    protected $fillable = [
        'customer_id', // Pastikan ada
        'invoice',
        'sub_total',
        'tax',
        'discount',
        'total',
        'payment',
        'approval_code',
        'status',
    ];
    

    public static function tableInit(){
        Schema::create(self::getTableName(), function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->string('invoice');
            $table->unsignedInteger('sub_total');
            $table->unsignedInteger('tax');
            $table->unsignedInteger('discount')->nullable();
            $table->unsignedInteger('total');
            $table->integer('payment')->nullable()->comment('1:cash, 2:qrisBca, 3:qrisMandiri, 4:debitBca, 5:debitMandiri, 6:transfer, 7:qrisBri, 8:debitBri');
            $table->string('approval_code')->nullable();
            $table->integer('status')->default(0)->comment('0:new, 1:pending, 2:paid');

            $table->foreign('customer_id')->references('id')->on(Customer::getTableName())->onDelete('cascade')->onUpdate('cascade');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function purchaseDetails(){
        return $this->hasMany(PurchaseDetail::class);
    }
}

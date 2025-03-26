<?php

namespace App\Models\Package;

use App\Models\DefaultModel;
use App\Models\PackageCategory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Package extends DefaultModel
{
    protected $fillable = [
        'package_category_id',
        'name',
        'price',
        'duration',
        'status',
    ];

    public static function tableInit(){
        Schema::create(self::getTableName(), function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('package_category_id');
            $table->string('name');
            $table->unsignedInteger('price');
            $table->unsignedInteger('duration')->comment('days');
            $table->boolean('status');
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('package_category_id')->references('id')->on(PackageCategory::getTableName())->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function packageCategory(){
        return $this->belongsTo(PackageCategory::class);
    }

    public function packageDetails(){
        return $this->hasMany(PackageDetail::class);
    }
}

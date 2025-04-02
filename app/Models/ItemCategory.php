<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ItemCategory extends DefaultModel
{
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public static function tableInit(){
        Schema::create(self::getTableName(), function (Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->boolean('status');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public static function tableSeed(){
        ItemCategory::insert([
            [
                'name' => 'Makanan',
                'description' => 'Makanan',
                'status' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'Minuman',
                'description' => 'Minuman',
                'status' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'Snack',
                'description' => 'Snack',
                'status' => true,
                'created_at' => now(),
            ]
        ]);
    }

    public function items(){
        return $this->hasMany(Item::class, 'categories_id');
    }
}

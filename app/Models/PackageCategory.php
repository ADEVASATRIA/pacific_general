<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PackageCategory extends DefaultModel
{
    protected $fillable = [
        'name',
        'type_category',
        'status',
    ];

    public static function tableInit(){
        Schema::create(self::getTableName(), function (Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->enum('type_category', [1, 2, 3, 4])->comment('1:ticket, 2:member , 3:items , 4:all');
            $table->boolean('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public static function tableSeed(){
        PackageCategory::insert([
            [
                'name' => 'Ticket',
                'type_category' => 1,
                'status' => true
            ],
            [
                'name' => 'Member',
                'type_category' => 2,
                'status' => true
            ],
            [
                'name' => 'Items',
                'type_category' => 3,
                'status' => true
            ],
            [
                'name' => 'All',
                'type_category' => 4,
                'status' => true
            ]
        ]);
    }
}
